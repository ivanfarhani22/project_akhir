/*
  E-Learning SRMA - Service Worker
  - Cache First: static assets
  - Network First: pages
  - Offline fallback: /offline
*/

const CACHE_VERSION = 'v1';
const STATIC_CACHE = `srma-static-${CACHE_VERSION}`;
const PAGE_CACHE = `srma-pages-${CACHE_VERSION}`;

const OFFLINE_URL = '/offline';

// Precache (basic shell)
const PRECACHE_PAGES = ['/', '/login', '/dashboard', OFFLINE_URL];

self.addEventListener('install', (event) => {
  event.waitUntil(
    (async () => {
      const pageCache = await caches.open(PAGE_CACHE);
      await pageCache.addAll(PRECACHE_PAGES);
      await self.skipWaiting();
    })()
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    (async () => {
      const keys = await caches.keys();
      await Promise.all(
        keys
          .filter((k) => k.startsWith('srma-') && ![STATIC_CACHE, PAGE_CACHE].includes(k))
          .map((k) => caches.delete(k))
      );
      await self.clients.claim();
    })()
  );
});

function isNavigationRequest(request) {
  return request.mode === 'navigate' || (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

function isStaticAsset(requestUrl) {
  // Treat these as static assets (Cache First)
  return (
    requestUrl.pathname.startsWith('/build/') ||
    requestUrl.pathname.startsWith('/images/') ||
    requestUrl.pathname.startsWith('/storage/') ||
    requestUrl.pathname.endsWith('.css') ||
    requestUrl.pathname.endsWith('.js') ||
    requestUrl.pathname.endsWith('.png') ||
    requestUrl.pathname.endsWith('.jpg') ||
    requestUrl.pathname.endsWith('.jpeg') ||
    requestUrl.pathname.endsWith('.webp') ||
    requestUrl.pathname.endsWith('.svg') ||
    requestUrl.pathname.endsWith('.ico') ||
    requestUrl.pathname.endsWith('.woff') ||
    requestUrl.pathname.endsWith('.woff2')
  );
}

function shouldBypassCache(url) {
  // Hindari caching untuk area yang biasanya butuh auth / sering berubah.
  // Ini mencegah fetch error berulang akibat cache-first ke halaman dinamis.
  return (
    url.pathname.startsWith('/admin/') ||
    url.pathname.startsWith('/guru/') ||
    url.pathname.startsWith('/siswa/') ||
    url.pathname.startsWith('/api/')
  );
}

function safeFetch(request) {
  // Beberapa browser/devtools bisa menampilkan "Uncaught (in promise)" kalau fetch reject.
  // Wrapper ini memastikan reject ditangani dan tetap melempar agar strategi bisa fallback.
  return fetch(request).catch((err) => {
    throw err;
  });
}

async function cacheFirst(request) {
  const cache = await caches.open(STATIC_CACHE);
  const cached = await cache.match(request);
  if (cached) return cached;

  const fresh = await safeFetch(request);
  // Only cache successful responses
  if (fresh && fresh.ok) {
    cache.put(request, fresh.clone());
  }
  return fresh;
}

async function networkFirst(request) {
  const cache = await caches.open(PAGE_CACHE);
  try {
    const fresh = await safeFetch(request);
    if (fresh && fresh.ok) {
      cache.put(request, fresh.clone());
    }
    return fresh;
  } catch (err) {
    const cached = await cache.match(request);
    if (cached) return cached;
    const offline = await cache.match(OFFLINE_URL);
    return offline || new Response('Offline', { status: 503, statusText: 'Offline' });
  }
}

self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Only handle same-origin requests
  if (url.origin !== self.location.origin) return;

  // Bypass non-GET requests
  if (request.method !== 'GET') return;

  // Bypass caching for dynamic/auth areas
  if (shouldBypassCache(url)) {
    // Untuk navigasi, tetap pakai networkFirst supaya ada fallback offline.
    if (isNavigationRequest(request)) {
      event.respondWith(networkFirst(request));
      return;
    }

    // Untuk request non-navigasi (mis. fetch JSON), langsung ke network.
    event.respondWith(safeFetch(request));
    return;
  }

  // Cache First for assets
  if (isStaticAsset(url)) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // Network First for navigation / HTML pages
  if (isNavigationRequest(request)) {
    event.respondWith(networkFirst(request));
    return;
  }

  // Default: try cache first, then network
  event.respondWith(cacheFirst(request));
});
