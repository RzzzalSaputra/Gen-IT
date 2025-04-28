<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-background {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://img.freepik.com/free-photo/cool-geometric-triangular-figure-neon-laser-light-great-backgrounds_181624-11068.jpg?t=st=1745809710~exp=1745813310~hmac=5ca37140640ea42d670aed6846ec52478ed4c0c8d15b5dcfdaa8375098e4d608&w=2000');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            position: relative;
        }
        .gallery-slider {
            width: 100%;
            overflow: hidden;
            position: relative;
            margin-top: -80px;
        }
        .swiper-slide {
            transition: all 0.5s ease-in-out;
        }
        .swiper-slide-active {
            transform: scale(1.05);
        }
        .swiper-slide-prev, .swiper-slide-next {
            transform: scale(0.9);
            opacity: 0.7;
        }
        .content-wrapper {
            position: relative;
            z-index: 10;
        }
        .hero-title {
            background: white;
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin-bottom: 0.75rem;
        }
        .hero-subtitle {
            background: #435DEEFF;
            display: inline-block;
            padding: 0.75rem 1.5rem;
            color: white;
        }
        .gallery-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .gallery-info {
            padding: 1rem;
            background-color: rgba(17, 24, 39, 0.8);
            border-radius: 0 0 8px 8px;
        }
        
        /* Responsive styles */
        @media (max-width: 639px) {
            .hero-title, .hero-subtitle {
                padding: 0.5rem 1.5rem;
                min-width: 200px;
                text-align: center;
            }
            .gallery-slider {
                margin-top: -50px;
            }
        }
        
        @media (min-width: 640px) {
            .gallery-slider {
                margin-top: -90px;
            }
            .gallery-image {
                height: 260px;
            }
            .hero-title, .hero-subtitle {
                padding: 0.5rem 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .gallery-slider {
                margin-top: -100px;
            }
            .gallery-image {
                height: 280px;
            }
            .hero-title, .hero-subtitle {
                padding: 0.5rem 2rem;
            }
        }
    </style>
</head>
<body class="bg-black min-h-screen flex flex-col">
    <!-- Include the universal navigation -->
    @include('layouts.navigation')

    <div class="flex-grow">
        <!-- Hero Section -->
        <div class="relative">
            <!-- Background Image -->
            <div class="hero-background">
                <!-- Content -->
                <div class="relative h-full flex items-center justify-start content-wrapper px-6 sm:px-8 md:px-12 lg:px-20">
                    <div class="w-full max-w-3xl">
                        <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold mb-4 md:mb-8">
                            <div class="hero-title">
                                Kami Adalah
                            </div>
                            <div class="hero-subtitle">
                                GEN-IT
                            </div>
                        </h1>
                        <p class="text-lg sm:text-lg md:text-xl text-white max-w-2xl">
                            Generasi Teknologi Informasi Kab. Katingan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Gallery Slider -->
            <div class="bg-gradient-to-b from-black/90 to-black/40 backdrop-blur-sm py-10 md:py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gallery-slider max-w-5xl mx-auto">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @foreach (App\Models\Gallery::latest()->where('type', 7)->where('file', '!=', null)->take(5)->get() as $gallery)
                                <div class="swiper-slide">
                                    <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10">
                                        @if($gallery->file)
                                        <img src="{{ Storage::url($gallery->file) }}" alt="{{ $gallery->title }}" class="gallery-image">
                                        @endif
                                        <div class="gallery-info">
                                            <h3 class="text-base sm:text-lg font-semibold text-white text-center">{{ $gallery->title }}</h3>
                                            <div class="flex justify-center items-center mt-2 text-xs sm:text-sm text-gray-400">
                                                <span>{{ $gallery->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the footer component -->
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            speed: 1000,
            effect: 'slide',
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 16
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24
                }
            }
        });
    </script>
</body>
</html>