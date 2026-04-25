(function () {
  const PROMPT_DELAY_MS = 4000;
  const STORAGE_KEY = 'pwa-banner-dismissed';

  let deferredPrompt = null;

  function isStandalone() {
    return (
      window.matchMedia('(display-mode: standalone)').matches ||
      window.navigator.standalone === true
    );
  }

  // Jangan tampilkan lagi jika pengguna sudah pernah klik "Nanti" hari ini
  function isDismissedToday() {
    try {
      const ts = localStorage.getItem(STORAGE_KEY);
      if (!ts) return false;
      return Date.now() - parseInt(ts, 10) < 24 * 60 * 60 * 1000;
    } catch (_) {
      return false;
    }
  }

  function setDismissed() {
    try { localStorage.setItem(STORAGE_KEY, String(Date.now())); } catch (_) {}
  }

  function createBanner() {
    const wrap = document.createElement('div');
    wrap.id = 'pwa-install-banner';
    wrap.setAttribute('role', 'region');
    wrap.setAttribute('aria-label', 'Instal aplikasi');
    Object.assign(wrap.style, {
      position: 'fixed',
      left: '12px',
      right: '12px',
      bottom: '12px',
      zIndex: '99999',
      maxWidth: '480px',
      margin: '0 auto',
      padding: '12px 14px',
      borderRadius: '16px',
      background: 'rgba(255,255,255,0.97)',
      color: '#0f172a',
      backdropFilter: 'blur(12px)',
      WebkitBackdropFilter: 'blur(12px)',
      border: '0.5px solid rgba(148,163,184,0.4)',
      boxShadow: '0 8px 32px rgba(2,6,23,0.12)',
      display: 'flex',
      alignItems: 'center',
      gap: '12px',
      fontFamily: 'ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif',
      boxSizing: 'border-box',
    });

    // Ikon placeholder (ganti dengan <img> jika icon tersedia)
    const iconWrap = document.createElement('div');
    Object.assign(iconWrap.style, {
      width: '44px', height: '44px', flexShrink: '0',
      borderRadius: '12px',
      background: '#eff6ff',
      border: '0.5px solid rgba(59,130,246,0.2)',
      display: 'flex', alignItems: 'center', justifyContent: 'center',
      overflow: 'hidden',
    });

    // Coba muat ikon asli; fallback ke inisial
    const iconImg = document.createElement('img');
    iconImg.src = '/images/pwa/icon-192.png';
    iconImg.width = 44;
    iconImg.height = 44;
    iconImg.alt = '';
    iconImg.style.cssText = 'width:44px;height:44px;object-fit:cover;border-radius:11px;';
    iconImg.onerror = () => {
      iconImg.replaceWith((() => {
        const fb = document.createElement('span');
        fb.textContent = 'EL';
        fb.style.cssText = 'font-size:13px;font-weight:600;color:#1d4ed8;letter-spacing:-0.5px;';
        return fb;
      })());
    };
    iconWrap.appendChild(iconImg);

    // Teks
    const textCol = document.createElement('div');
    textCol.style.cssText = 'flex:1;min-width:0;display:flex;flex-direction:column;gap:2px;';

    const title = document.createElement('p');
    title.textContent = 'E‑Learning SRMA';
    title.style.cssText = 'margin:0;font-size:13.5px;font-weight:600;line-height:1.3;color:#0f172a;';

    const desc = document.createElement('p');
    desc.textContent = 'Akses lebih cepat langsung dari layar utama, tersedia offline.';
    desc.style.cssText = 'margin:0;font-size:12px;line-height:1.4;color:#475569;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';

    textCol.appendChild(title);
    textCol.appendChild(desc);

    // Tombol
    const btnWrap = document.createElement('div');
    btnWrap.style.cssText = 'display:flex;gap:8px;align-items:center;flex-shrink:0;';

    const btnLater = document.createElement('button');
    btnLater.type = 'button';
    btnLater.textContent = 'Nanti';
    btnLater.style.cssText = [
      'background:transparent',
      'color:#475569',
      'border:0.5px solid rgba(148,163,184,0.55)',
      'border-radius:10px',
      'padding:7px 12px',
      'font-weight:400',
      'font-size:13px',
      'cursor:pointer',
      'line-height:1',
      'white-space:nowrap',
    ].join(';');

    const btnInstall = document.createElement('button');
    btnInstall.type = 'button';
    btnInstall.textContent = 'Pasang';
    btnInstall.style.cssText = [
      'background:#1d4ed8',
      'color:#fff',
      'border:none',
      'border-radius:10px',
      'padding:7px 14px',
      'font-weight:500',
      'font-size:13px',
      'cursor:pointer',
      'line-height:1',
      'white-space:nowrap',
      'transition:background 0.15s',
    ].join(';');
    btnInstall.onmouseenter = () => { btnInstall.style.background = '#1e40af'; };
    btnInstall.onmouseleave = () => { btnInstall.style.background = '#1d4ed8'; };

    btnWrap.appendChild(btnLater);
    btnWrap.appendChild(btnInstall);

    wrap.appendChild(iconWrap);
    wrap.appendChild(textCol);
    wrap.appendChild(btnWrap);

    // Hint teks (muncul jika prompt tidak tersedia)
    let hintEl = null;
    const showHint = (msg) => {
      if (!hintEl) {
        hintEl = document.createElement('p');
        hintEl.style.cssText = [
          'margin:6px 0 0 56px',
          'font-size:11.5px',
          'color:#64748b',
          'line-height:1.4',
        ].join(';');
        // Banner jadi flex-col saat ada hint
        wrap.style.flexWrap = 'wrap';
        wrap.appendChild(hintEl);
      }
      hintEl.textContent = msg;
    };

    return { wrap, btnInstall, btnLater, showHint };
  }

  function showBanner() {
    if (document.getElementById('pwa-install-banner')) return;

    const { wrap, btnInstall, btnLater, showHint } = createBanner();
    document.body.appendChild(wrap);

    const removeBanner = () => wrap.remove();

    btnLater.addEventListener('click', () => {
      setDismissed();
      removeBanner();
    });

    btnInstall.addEventListener('click', async () => {
      if (!deferredPrompt) {
        // Beri petunjuk manual jika prompt belum ditangkap
        const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
        if (isIOS) {
          showHint('Di Safari: ketuk ikon Bagikan lalu pilih "Tambah ke Layar Utama".');
        } else {
          showHint('Buka menu browser (⋮) lalu pilih "Pasang aplikasi" atau "Tambah ke layar utama".');
        }
        return;
      }

      try {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') removeBanner();
      } catch (err) {
        console.warn('[PWA] prompt() error:', err);
      } finally {
        deferredPrompt = null;
      }
    });

    // Expose ke event beforeinstallprompt agar bisa diperbarui
    wrap.__refresh = () => {
      btnInstall.style.opacity = '1';
    };
  }

  // Daftarkan Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/service-worker.js').catch((err) => {
        console.warn('[PWA] SW gagal didaftarkan:', err);
      });
    });
  }

  // Tampilkan banner setelah delay
  window.addEventListener('load', () => {
    if (isStandalone() || isDismissedToday()) return;
    window.setTimeout(() => {
      if (!isStandalone()) showBanner();
    }, PROMPT_DELAY_MS);
  });

  // Tangkap event prompt dari browser sesegera mungkin
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    const banner = document.getElementById('pwa-install-banner');
    if (banner && typeof banner.__refresh === 'function') banner.__refresh();
  });

  window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    const banner = document.getElementById('pwa-install-banner');
    if (banner) banner.remove();
  });
})();