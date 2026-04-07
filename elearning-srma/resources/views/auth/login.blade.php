<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning SRMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --color-primary: #C41E3A; /* Merah */
            --color-secondary: #4A4A4A; /* Abu-abu gelap */
            --color-accent: #6B6B6B; /* Abu-abu sedang */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-primary {
            background-color: var(--color-primary);
        }

        .bg-secondary {
            background-color: var(--color-secondary);
        }

        .bg-accent {
            background-color: var(--color-accent);
        }

        .text-primary {
            color: var(--color-primary);
        }

        .border-primary {
            border-color: var(--color-primary);
        }

        .focus-primary:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
            border-color: var(--color-primary);
        }

        .btn-primary {
            background-color: var(--color-primary);
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #a01a31;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
        }

        .banner-section {
            flex: 1;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            position: relative;
            overflow: hidden;
        }

        .banner-carousel {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .banner-slide {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .banner-slide.active {
            opacity: 1;
        }

        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.35) 0%, rgba(196, 30, 58, 0.40) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .banner-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 20;
        }

        .banner-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .banner-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .banner-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 450px;
            margin: 0 auto;
            width: 100%;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-img {
            height: 80px;
            margin: 0 auto 1rem auto;
            display: block;
        }

        .form-title {
            color: var(--color-primary);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--color-accent);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--color-secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
        }

        .form-submit {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .alert-error {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }

        .test-credentials {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-top: 1.5rem;
            font-size: 0.85rem;
        }

        .test-credentials-title {
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .test-credentials-item {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .test-credentials-item strong {
            display: inline-block;
            min-width: 70px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .banner-section {
                display: none;
            }

            .form-section {
                padding: 1.5rem;
            }

            .form-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Banner Section (Sebelah Kiri) -->
        <div class="banner-section">
            <div class="banner-carousel" id="bannerCarousel">
                @if($banners && $banners->count() > 0)
                    @foreach($banners as $index => $banner)
                    <div class="banner-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                        <img src="{{ asset($banner->image_path) }}" alt="Banner {{ $index + 1 }}" class="banner-image">
                    </div>
                    @endforeach
                @else
                    <div class="banner-slide active" data-index="0">
                        <img src="https://via.placeholder.com/600x800/C41E3A/ffffff?text=Banner+E-Learning" alt="Default Banner" class="banner-image">
                    </div>
                @endif
            </div>
            
            <div class="banner-overlay">
                <div style="text-align: center; color: white; z-index: 10;">
                    <h2 style="
                        font-size: 3.5rem; 
                        font-weight: 900; 
                        margin-bottom: 1rem;
                        text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7), 0 0 30px rgba(255, 255, 255, 0.2);
                        letter-spacing: 1px;
                        animation: fadeInDown 0.8s ease-out;
                    ">
                        Selamat Datang
                    </h2>
                    <p style="
                        font-size: 1.25rem; 
                        opacity: 1;
                        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
                        font-weight: 600;
                        line-height: 1.6;
                        animation: fadeInUp 0.8s ease-out 0.2s both;
                    ">
                        Platform Pembelajaran Online<br>SRMA 25 Lamongan
                    </p>
                </div>
            </div>

            @if($banners && $banners->count() > 1)
            <div class="banner-dots" id="bannerDots">
                @foreach($banners as $index => $banner)
                <div class="banner-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Form Section (Sebelah Kanan) -->
        <div class="form-section">
            <div class="form-container">
                <!-- Logo -->
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA" class="logo-img">
                    <h1 class="form-title">E-Learning</h1>
                    <p class="form-subtitle">SRMA 25 Lamongan</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">  Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="form-input focus-primary"
                            placeholder="Masukkan email Anda" 
                            required
                            autocomplete="email"
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">  Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="form-input focus-primary"
                            placeholder="Masukkan password" 
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="form-submit btn-primary">
                        Masuk ke E-Learning
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Banner Carousel JavaScript
        const bannerCarousel = document.getElementById('bannerCarousel');
        const bannerDots = document.getElementById('bannerDots');
        const slides = bannerCarousel ? Array.from(bannerCarousel.querySelectorAll('.banner-slide')) : [];
        const dots = bannerDots ? Array.from(bannerDots.querySelectorAll('.banner-dot')) : [];
        
        let currentSlide = 0;
        let autoplayInterval = null;

        function showSlide(index) {
            if (slides.length === 0) return;

            // Ensure index is within bounds
            index = index % slides.length;
            if (index < 0) index = slides.length + index;

            // Update slides
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');

            // Update dots
            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[index]) {
                dots[index].classList.add('active');
            }

            currentSlide = index;
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function startAutoplay() {
            if (slides.length > 1) {
                autoplayInterval = setInterval(nextSlide, 3000); // Auto-rotate every 3 seconds
            }
        }

        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
        }

        function resetAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        // Click handlers for dots
        if (dots.length > 0) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    resetAutoplay();
                });
            });
        }

        // Start autoplay on page load
        if (slides.length > 1) {
            startAutoplay();

            // Pause on hover, resume on mouse leave
            if (bannerCarousel) {
                bannerCarousel.addEventListener('mouseenter', stopAutoplay);
                bannerCarousel.addEventListener('mouseleave', startAutoplay);
            }
        }
    </script>
</body>
</html>
