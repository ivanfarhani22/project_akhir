<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning SRMA</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#561020">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8f8f8;
        }

        /* Banner Carousel */
        .banner-slide {
            opacity: 0;
            transition: opacity 0.9s ease-in-out;
        }

        .banner-slide.active {
            opacity: 1;
        }

        /* Dot indicator */
        .banner-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .banner-dot.active {
            background: #ffffff;
            width: 24px;
        }

        /* Form focus ring */
        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }

        /* Animasi masuk */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp 0.55s cubic-bezier(.22,1,.36,1) both;
        }

        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }
        .delay-4 { animation-delay: 0.32s; }

        /* Tombol submit hover */
        .btn-submit {
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            background: #b91c1c;
            box-shadow: 0 4px 20px rgba(185, 28, 28, 0.35);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: scale(0.98) translateY(0);
        }

        /* Password toggle */
        .toggle-password {
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #dc2626;
        }

        /* Scrollbar kiri tersembunyi */
        .left-panel {
            scrollbar-width: none;
        }
    </style>
</head>
<body>
<div class="min-h-screen flex">

    
    
    
    <div class="left-panel hidden lg:flex lg:w-[52%] xl:w-1/2 relative overflow-hidden bg-red-700">

        
        <div class="absolute inset-0" id="bannerCarousel">
            <?php if($banners && $banners->count() > 0): ?>
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="banner-slide <?php echo e($index === 0 ? 'active' : ''); ?> absolute inset-0"
                         data-index="<?php echo e($index); ?>">
                        <img src="<?php echo e(asset($banner->image_path)); ?>"
                             alt="Banner <?php echo e($index + 1); ?>"
                             class="w-full h-full object-cover">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="banner-slide active absolute inset-0" data-index="0">
                    <img src="https://via.placeholder.com/800x1000/C41E3A/ffffff?text=E-Learning+SRMA"
                         alt="Default Banner"
                         class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
        </div>

        
        <div class="absolute inset-0 bg-gradient-to-t from-red-900/80 via-red-800/30 to-transparent z-10"></div>

        
        <div class="absolute bottom-0 left-0 right-0 z-20 px-10 pb-12">
            <p class="text-white/70 text-sm font-semibold uppercase tracking-widest mb-2">
                Platform Pembelajaran
            </p>
            <h2 class="text-white text-4xl xl:text-5xl font-extrabold leading-tight mb-2"
                style="text-shadow: 0 2px 16px rgba(0,0,0,0.4);">
                Belajar Lebih<br>Menyenangkan
            </h2>
            <p class="text-white/80 text-base font-medium mt-3">
                SRMA 25 Lamongan
            </p>

            
            <?php if($banners && $banners->count() > 1): ?>
                <div class="flex gap-2 mt-6" id="bannerDots">
                    <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="banner-dot <?php echo e($index === 0 ? 'active' : ''); ?>"
                             data-index="<?php echo e($index); ?>"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    
    
    <div class="w-full lg:w-[48%] xl:w-1/2 flex flex-col justify-center
                px-6 sm:px-10 md:px-16 lg:px-12 xl:px-16 py-10 bg-white min-h-screen">

        <div class="w-full max-w-[400px] mx-auto">

            
            <div class="animate-slide-up mb-8 flex flex-col items-center lg:items-start">
                <img src="<?php echo e(asset('images/logo.png')); ?>"
                     alt="Logo SRMA"
                     class="h-16 sm:h-20 mb-4 object-contain">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-red-600 tracking-tight">
                    E-Learning
                </h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">
                    SRMA 25 Lamongan
                </p>
            </div>

            
            <div class="animate-slide-up delay-1 mb-7">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                    Masuk ke akun Anda
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Silakan masukkan email dan password untuk melanjutkan
                </p>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="animate-slide-up mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start gap-2 text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                            <span><?php echo e($error); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                
                <div class="animate-slide-up delay-2">
                    <label for="email"
                           class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo e(old('email')); ?>"
                            placeholder="contoh@email.com"
                            required
                            autocomplete="email"
                            class="form-input w-full pl-11 pr-4 py-3.5 bg-gray-50 border
                                   <?php echo e($errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200'); ?>

                                   rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   transition duration-200"
                        >
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i><?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="animate-slide-up delay-3">
                    <label for="password"
                           class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                            class="form-input w-full pl-11 pr-12 py-3.5 bg-gray-50 border
                                   <?php echo e($errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200'); ?>

                                   rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   transition duration-200"
                        >
                        <button type="button"
                                onclick="togglePassword()"
                                class="toggle-password absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i><?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="animate-slide-up delay-4 pt-2">
                    <button type="submit"
                            class="btn-submit w-full py-3.5 bg-red-600 text-white
                                   font-bold text-sm rounded-xl flex items-center justify-center gap-2.5">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk ke E-Learning
                    </button>
                </div>
            </form>

            
            <p class="animate-slide-up text-center text-gray-400 text-xs mt-8 flex items-center justify-center gap-1.5">
                <i class="fas fa-shield-halved text-red-400"></i>
                Halaman ini aman dan terlindungi
            </p>
        </div>
    </div>

</div>

<!-- PWA: Install UX + Service Worker -->
<script src="/js/pwa-install.js" defer></script>

<script>
    // ── Toggle password visibility ────────────────────────────
    function togglePassword() {
        const input   = document.getElementById('password');
        const icon    = document.getElementById('eyeIcon');
        const visible = input.type === 'text';
        input.type    = visible ? 'password' : 'text';
        icon.className = visible ? 'fas fa-eye text-sm' : 'fas fa-eye-slash text-sm';
    }

    // ── Banner Carousel ───────────────────────────────────────
    const carousel = document.getElementById('bannerCarousel');
    const dotsWrap = document.getElementById('bannerDots');
    const slides   = carousel  ? Array.from(carousel.querySelectorAll('.banner-slide'))  : [];
    const dots     = dotsWrap  ? Array.from(dotsWrap.querySelectorAll('.banner-dot'))    : [];

    let current  = 0;
    let interval = null;

    function goTo(index) {
        if (!slides.length) return;
        index = ((index % slides.length) + slides.length) % slides.length;

        slides.forEach(s => s.classList.remove('active'));
        slides[index].classList.add('active');

        dots.forEach(d => d.classList.remove('active'));
        if (dots[index]) dots[index].classList.add('active');

        current = index;
    }

    function startAutoplay() {
        if (slides.length > 1) interval = setInterval(() => goTo(current + 1), 5000);
    }

    function stopAutoplay() {
        clearInterval(interval);
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { goTo(i); stopAutoplay(); startAutoplay(); });
    });

    if (carousel) {
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
    }

    startAutoplay();
</script>
</body>
</html><?php /**PATH E:\xampp\htdocs\Project Akhir\elearning-srma\resources\views/auth/login.blade.php ENDPATH**/ ?>