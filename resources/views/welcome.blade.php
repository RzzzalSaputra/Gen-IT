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
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('background/background-welcome.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            position: relative;
        }
        .content-slider {
            width: 100%;
            overflow: hidden;
            position: relative;
            margin-top: -80px;
        }
        .swiper-slide {
            transition: all 0.5s ease-in-out;
            height: auto;
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
        .content-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .content-info {
            padding: 1rem;
            background-color: rgba(17, 24, 39, 0.8);
            border-radius: 0 0 8px 8px;
            height: 150px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .no-image-placeholder {
            width: 100%;
            height: 200px;
            background-color: #1e2a4a;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .section-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #435DEEFF;
        }
        .card-container {
            height: 350px;
        }
        .content-title {
            margin-bottom: 0.5rem;
        }
        .content-excerpt {
            flex-grow: 1;
        }
        .content-meta {
            margin-top: auto;
        }
        .file-icon {
            width: 80px;
            height: 80px;
            color: #5b9bd5;
        }
        
        /* Responsive styles */
        @media (max-width: 639px) {
            .hero-title, .hero-subtitle {
                padding: 0.5rem 1.5rem;
                min-width: 200px;
                text-align: center;
            }
            .content-slider {
                margin-top: -50px;
            }
        }
        
        @media (min-width: 640px) {
            .content-slider {
                margin-top: -90px;
            }
            .hero-title, .hero-subtitle {
                padding: 0.5rem 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .content-slider {
                margin-top: -100px;
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
                            Generasi Teknologi Informasi Kabupaten Katingan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content Sliders Section -->
            <div class="bg-gradient-to-b from-black/90 to-black/40 backdrop-blur-sm py-10 md:py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6">
                        <!-- Articles Section -->
                        <div class="content-slider">
                            <h2 class="section-title">Artikel Terbaru</h2>
                            <div class="swiper-articles">
                                <div class="swiper-wrapper">
                                    @forelse (App\Models\Article::latest()->take(5)->get() as $article)
                                    <div class="swiper-slide">
                                        <a href="{{ route('articles.show', $article->id) }}" class="block">
                                            <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                                <div class="no-image-placeholder">
                                                    <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2M18 20H6V4H13V9H18V20M10 13H8V11H10V13M10 17H8V15H10V17M14 13H12V11H14V13M14 17H12V15H14V17Z"/>
                                                    </svg>
                                                </div>
                                                <div class="content-info">
                                                    <h3 class="text-base sm:text-lg font-semibold text-white content-title">{{ Str::limit($article->title, 40) }}</h3>
                                                    <p class="text-gray-300 text-sm content-excerpt">{{ Str::limit($article->summary, 80) }}</p>
                                                    <div class="flex justify-between items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                        <span>{{ $article->writer }}</span>
                                                        <span>{{ \Carbon\Carbon::parse($article->created_at)->locale('id')->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                            <div class="no-image-placeholder">
                                                <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2M18 20H6V4H13V9H18V20M10 13H8V11H10V13M10 17H8V15H10V17M14 13H12V11H14V13M14 17H12V15H14V17Z"/>
                                                </svg>
                                            </div>
                                            <div class="content-info">
                                                <h3 class="text-base sm:text-lg font-semibold text-white text-center content-title">Tidak Ada Artikel</h3>
                                                <div class="flex justify-center items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                    <span>Artikel belum ditambahkan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="swiper-pagination swiper-pagination-articles"></div>
                            </div>
                        </div>

                        <!-- Posts Section -->
                        <div class="content-slider">
                            <h2 class="section-title">Postingan Terbaru</h2>
                            <div class="swiper-posts">
                                <div class="swiper-wrapper">
                                    @forelse (App\Models\Post::latest()->take(5)->get() as $post)
                                    <div class="swiper-slide">
                                        <a href="{{ route('posts.show', $post->id) }}" class="block">
                                            <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                                @if($post->img)
                                                    <img src="{{ Storage::url($post->img) }}" alt="{{ $post->title }}" class="content-image">
                                                @else
                                                    <div class="no-image-placeholder">
                                                        <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19,5V19H5V5H19M21,3H3V21H21V3M17,17H7V7H17V17Z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="content-info">
                                                    <h3 class="text-base sm:text-lg font-semibold text-white content-title">{{ Str::limit($post->title, 40) }}</h3>
                                                    <p class="text-gray-300 text-sm content-excerpt">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                                                    <div class="flex justify-end items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                        <span>{{ \Carbon\Carbon::parse($post->created_at)->locale('id')->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                            <div class="no-image-placeholder">
                                                <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19,5V19H5V5H19M21,3H3V21H21V3M17,17H7V7H17V17Z"/>
                                                </svg>
                                            </div>
                                            <div class="content-info">
                                                <h3 class="text-base sm:text-lg font-semibold text-white text-center content-title">Tidak Ada Postingan</h3>
                                                <div class="flex justify-center items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                    <span>Postingan belum ditambahkan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="swiper-pagination swiper-pagination-posts"></div>
                            </div>
                        </div>

                        <!-- Galleries Section -->
                        <div class="content-slider">
                            <h2 class="section-title">Galeri Kegiatan</h2>
                            <div class="swiper-gallery">
                                <div class="swiper-wrapper">
                                    @forelse (App\Models\Gallery::latest()->where('type', 7)->where('file', '!=', null)->take(5)->get() as $gallery)
                                    <div class="swiper-slide">
                                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                            @if($gallery->file)
                                                @php
                                                    $extension = pathinfo(Storage::url($gallery->file), PATHINFO_EXTENSION);
                                                @endphp
                                                
                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ Storage::url($gallery->file) }}" alt="{{ $gallery->title }}" class="content-image">
                                                @else
                                                    <div class="no-image-placeholder">
                                                        @if(in_array($extension, ['pdf']))
                                                            <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M20,2H8C6.9,2 6,2.9 6,4V16C6,17.1 6.9,18 8,18H20C21.1,18 22,17.1 22,16V4C22,2.9 21.1,2 20,2M20,16H8V4H20V16M4,6H2V20C2,21.1 2.9,22 4,22H18V20H4V6M16,12V9C16,8.4 15.6,8 15,8H13V13H15C15.6,13 16,12.6 16,12M14,9H15V12H14V9Z"/>
                                                            </svg>
                                                        @elseif(in_array($extension, ['doc', 'docx']))
                                                            <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M9.5,11A1.5,1.5 0 0,0 8,12.5V16.5A1.5,1.5 0 0,0 9.5,18A1.5,1.5 0 0,0 11,16.5V12.5A1.5,1.5 0 0,0 9.5,11M9.5,16.5V12.5H10.5V16.5H9.5M12.5,11A1.5,1.5 0 0,0 11,12.5V16.5A1.5,1.5 0 0,0 12.5,18A1.5,1.5 0 0,0 14,16.5V12.5A1.5,1.5 0 0,0 12.5,11M12.5,16.5V12.5H13.5V16.5H12.5Z"/>
                                                            </svg>
                                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                                            <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12,19L17,12L19,14V10H15V12L12,9L7,14L9,17L12,13L15,17L12,19Z"/>
                                                            </svg>
                                                        @elseif(in_array($extension, ['mp4', 'avi', 'mov', 'wmv']))
                                                            <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M18,4L20,8H17L15,4H13L15,8H12L10,4H8L10,8H7L5,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V4H18Z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <div class="no-image-placeholder">
                                                    <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="content-info">
                                                <h3 class="text-base sm:text-lg font-semibold text-white text-center content-title">{{ $gallery->title }}</h3>
                                                <p class="text-gray-300 text-sm content-excerpt">{{ Str::limit($gallery->description ?? 'Galeri kegiatan Gen-IT', 80) }}</p>
                                                <div class="flex justify-end items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                    <span>{{ \Carbon\Carbon::parse($gallery->created_at)->locale('id')->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-white/10 card-container">
                                            <div class="no-image-placeholder">
                                                <svg class="file-icon" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z"/>
                                                </svg>
                                            </div>
                                            <div class="content-info">
                                                <h3 class="text-base sm:text-lg font-semibold text-white text-center content-title">Tidak Ada Galeri</h3>
                                                <p class="text-gray-300 text-sm content-excerpt text-center">Dokumentasi kegiatan belum tersedia</p>
                                                <div class="flex justify-end items-center text-xs sm:text-sm text-gray-400 content-meta">
                                                    <span>Galeri belum ditambahkan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="swiper-pagination swiper-pagination-gallery"></div>
                            </div>
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
        // Initialize Swipers with same configuration for consistent behavior
        const swiperOptions = {
            slidesPerView: 1,
            spaceBetween: 16,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            speed: 1000,
            effect: 'slide',
            pagination: {
                clickable: true
            }
        };
        
        // Initialize Gallery Swiper
        const gallerySwiper = new Swiper('.swiper-gallery', {
            ...swiperOptions,
            pagination: {
                el: '.swiper-pagination-gallery',
                clickable: true
            }
        });
        
        // Initialize Articles Swiper
        const articlesSwiper = new Swiper('.swiper-articles', {
            ...swiperOptions,
            pagination: {
                el: '.swiper-pagination-articles',
                clickable: true
            }
        });
        
        // Initialize Posts Swiper
        const postsSwiper = new Swiper('.swiper-posts', {
            ...swiperOptions,
            pagination: {
                el: '.swiper-pagination-posts',
                clickable: true
            }
        });
    </script>
</body>
</html>