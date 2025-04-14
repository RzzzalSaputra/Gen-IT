<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-0">{{ $material->title }}</h1>
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-700/70 hover:bg-gray-600/70 backdrop-blur-sm text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        
                        <div class="inline-flex items-center px-3 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-sm text-gray-300 border border-gray-600/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ date('M d, Y', strtotime($material->created_at ?? $material->create_at ?? now())) }}
                        </div>
                        
                        @if($material->creator)
                            <div class="inline-flex items-center px-3 py-1 bg-indigo-900/30 backdrop-blur-sm rounded-lg text-sm text-indigo-300 border border-indigo-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $material->creator->name }}
                            </div>
                        @endif
                        
                        @if($material->read_counter > 0)
                        <div class="inline-flex items-center px-3 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-sm text-gray-300 border border-gray-600/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $material->read_counter }} views
                        </div>
                        @endif
                        
                        @if($material->file && $material->download_counter > 0)
                            <div class="inline-flex items-center px-3 py-1 bg-green-900/30 backdrop-blur-sm rounded-lg text-sm text-green-300 border border-green-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                                {{ $material->download_counter }} downloads
                            </div>
                        @endif
                    </div>

                    <!-- Main Content - MOVED BEFORE MEDIA -->
                    <div class="bg-gray-900/30 backdrop-blur-sm rounded-xl p-6 mb-8">
                        <div class="prose prose-lg max-w-none text-gray-200 leading-relaxed">
                            {!! $material->content !!}
                        </div>
                    </div>
                    
                    <!-- Featured Image (if exists) -->
                    @if($material->img)
                        <div class="mb-8 text-center">
                            <img 
                                src="{{ asset('storage/' . $material->img) }}" 
                                alt="{{ $material->title }}" 
                                class="rounded-xl max-h-96 mx-auto object-contain cursor-pointer image-preview" 
                                onclick="openImageModal(this.src)"
                            >
                        </div>
                    @endif

                    <!-- Video Embed (if exists) - MADE SMALLER WITH MAX-WIDTH -->
                    @if($material->link && (strpos($material->link, 'youtube.com') !== false || strpos($material->link, 'youtu.be') !== false))
                        <div class="mb-8 mx-auto max-w-2xl">
                            <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden">
                                @php
                                    // Extract YouTube video ID
                                    $videoId = '';
                                    if (strpos($material->link, 'youtube.com/watch?v=') !== false) {
                                        $parts = parse_url($material->link);
                                        parse_str($parts['query'], $query);
                                        $videoId = $query['v'] ?? '';
                                    } elseif (strpos($material->link, 'youtu.be/') !== false) {
                                        $videoId = substr($material->link, strrpos($material->link, '/') + 1);
                                    }
                                @endphp
                                
                                @if($videoId)
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $videoId }}" 
                                        class="w-full h-full rounded-xl" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen
                                    ></iframe>
                                @else
                                    <a href="{{ $material->link }}" target="_blank" class="block text-blue-400 hover:text-blue-300 transition-colors duration-200">
                                        {{ $material->link }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Download Section -->
                    @if($material->file)
                        <div class="flex justify-center mt-8">
                            <a href="{{ route('materials.download', $material->id) }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-base font-medium rounded-lg transition-colors duration-200 shadow-lg shadow-green-500/20">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Material
                            </a>
                        </div>
                    @endif

                    <!-- External Link (if exists and not YouTube) -->
                    @if($material->link && strpos($material->link, 'youtube.com') === false && strpos($material->link, 'youtu.be') === false)
                        <div class="flex justify-center mt-8">
                            <a href="{{ $material->link }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg transition-colors duration-200 shadow-lg shadow-blue-500/20">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Visit Resource
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm hidden" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-screen p-4">
            <button onclick="event.stopPropagation(); closeImageModal()" class="absolute top-2 right-2 bg-gray-800/80 rounded-full p-2 text-white hover:bg-gray-700/80 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-[80vh] object-contain">
        </div>
    </div>

    <!-- CSS Fixes for content readability -->
    <style>
        /* Make content more readable */
        .prose p, .prose li, .prose blockquote {
            color: #e2e8f0; /* text-gray-200 */
            font-size: 1.05rem;
            line-height: 1.75;
        }
        
        .prose h1, .prose h2, .prose h3, .prose h4 {
            color: #f8fafc; /* text-gray-100 */
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }
        
        .prose a {
            color: #93c5fd; /* text-blue-300 */
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        
        .prose a:hover {
            color: #60a5fa; /* text-blue-400 */
        }
        
        .prose code {
            color: #f9a8d4; /* text-pink-300 */
            background-color: rgba(31, 41, 55, 0.5); /* bg-gray-800/50 */
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
        }
        
        .prose pre {
            background-color: rgba(17, 24, 39, 0.7) !important; /* bg-gray-900/70 */
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
        }
        
        .prose img {
            border-radius: 0.5rem;
            margin: 1.5rem auto;
        }
        
        .prose table {
            border-collapse: collapse;
            margin: 1.5rem 0;
            overflow: hidden;
            border-radius: 0.5rem;
        }
        
        .prose table th {
            background-color: rgba(31, 41, 55, 0.7); /* bg-gray-800/70 */
            color: #f8fafc; /* text-gray-100 */
            font-weight: 600;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        
        .prose table td {
            padding: 0.75rem 1rem;
            border-top: 1px solid rgba(55, 65, 81, 0.5); /* border-gray-700/50 */
        }
        
        .prose table tr {
            background-color: rgba(31, 41, 55, 0.3); /* bg-gray-800/30 */
        }
        
        .prose table tr:nth-child(even) {
            background-color: rgba(31, 41, 55, 0.5); /* bg-gray-800/50 */
        }
        
        /* Aspect ratio for video embedding */
        .aspect-w-16 {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        }
        
        .aspect-w-16 iframe {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
    </style>

    <script>
        // Image modal functionality
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
        
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = ''; // Enable scrolling
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>