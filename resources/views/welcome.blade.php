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
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://img.freepik.com/free-photo/cool-geometric-triangular-figure-neon-laser-light-great-backgrounds_181624-11068.jpg?t=st=1739365548~exp=1739369148~hmac=b15bd6a3bde915d42fdca2128b7e7fe4763ba95b4bed77573f53026a1bc14795&w=2000');
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
            margin-top: -100px;
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
            padding: 0.5rem 2rem;
            margin-bottom: 1rem;
        }
        .hero-subtitle {
            background: #435DEEFF;
            display: inline-block;
            padding: 0.5rem 2rem;
            color: white;
        }
        .gallery-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .gallery-info {
            padding: 1rem;
            background-color: rgba(17, 24, 39, 0.8);
            border-radius: 0 0 8px 8px;
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
                <div class="relative h-full flex items-center justify-start content-wrapper px-20">
                    <div class="w-full max-w-3xl">
                        <h1 class="text-5xl md:text-7xl font-bold mb-8">
                            <div class="hero-title">
                                Kami Adalah
                            </div>
                            <div class="hero-subtitle">
                                GEN-IT
                            </div>
                        </h1>
                        <p class="text-xl text-white max-w-2xl">
                            Generasi Teknologi Informasi Kab. Katingan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Gallery Slider -->
            <div class="bg-gradient-to-b from-black/90 to-black/40 backdrop-blur-sm py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="gallery-slider max-w-5xl mx-auto">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @foreach (App\Models\Gallery::latest()->where('type', 7)->where('file', '!=', null)->take(5)->get() as $gallery)
                                <div class="swiper-slide">
                                    <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10">
                                        @if($gallery->file)
                                            <img src="{{ $gallery->file }}" alt="{{ $gallery->title }}" class="gallery-image">
                                        @endif
                                        <div class="gallery-info">
                                            <h3 class="text-lg font-semibold text-white text-center">{{ $gallery->title }}</h3>
                                            <div class="flex justify-center items-center mt-2 text-sm text-gray-400">
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
            slidesPerView: 3,
            spaceBetween: 24,
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