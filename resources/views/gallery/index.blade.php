<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gallery') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $galleries->total() }} {{ Str::plural('item', $galleries->total()) }} available
            </div>
        </div>
    </x-slot>

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full">
            <button id="closeModal" class="absolute top-2 right-2 text-white hover:text-gray-300 text-2xl z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalImage" class="max-h-[80vh] max-w-full mx-auto" src="" alt="">
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Filter Tabs -->
            <div class="mb-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                <ul class="flex flex-wrap justify-center gap-2 text-sm font-medium">
                    <li>
                        <a href="{{ route('gallery.index', ['type' => 7]) }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->input('type') == 7 || !request()->has('type') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Images
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gallery.index', ['type' => 8]) }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->input('type') == 8 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Videos
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($galleries->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Gallery Items Found</h3>
                            <p class="text-gray-400">Gallery items will appear here once they are added to the system.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($galleries as $gallery)
                            <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                @if($gallery->type == 7 && $gallery->file)
                                    <div class="h-48 relative overflow-hidden cursor-pointer image-preview-trigger" data-src="{{ $gallery->file }}">
                                        <img src="{{ $gallery->file }}" alt="{{ $gallery->title }}" 
                                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute top-3 right-3">
                                            <span class="px-2 py-1 text-xs font-medium text-blue-300 bg-blue-900/30 backdrop-blur-sm rounded-lg border border-blue-500/20">
                                                IMAGE
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:text-blue-400 transition-colors duration-200">
                                        {{ $gallery->title }}
                                    </h3>
                                    
                                    @if($gallery->type == 8 && $gallery->link)
                                        @php
                                            $videoId = null;
                                            // Enhanced regex to handle more YouTube URL formats
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $gallery->link, $match)) {
                                                $videoId = $match[1];
                                            }
                                        @endphp
                                        
                                        @if($videoId)
                                            <div class="h-48 bg-black relative overflow-hidden">
                                                <iframe 
                                                    src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen
                                                    class="w-full h-48"
                                                ></iframe>
                                                <div class="absolute top-3 right-3">
                                                    <span class="px-2 py-1 text-xs font-medium text-purple-300 bg-purple-900/30 backdrop-blur-sm rounded-lg border border-purple-500/20">
                                                        VIDEO
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-purple-600/20 to-blue-600/20 p-6">
                                                <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $gallery->title }}</h4>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <a href="{{ $gallery->link }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm">
                                                    {{ $gallery->link }}
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="flex justify-between items-center mt-6">
                                        <span class="text-sm text-gray-500">{{ $gallery->created_at->diffForHumans() }}</span>
                                        
                                        <div class="flex items-center gap-3">
                                            @if($gallery->type == 7)
                                                <button class="image-preview-trigger text-gray-400 hover:text-blue-400 transition-colors duration-200" data-src="{{ $gallery->file }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            @if($gallery->type == 8 && $gallery->link && !$videoId)
                                                <a href="{{ $gallery->link }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Watch Video
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for image preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // If no type is specified in the URL, redirect to images (type 7)
            if (!window.location.href.includes('type=')) {
                window.location.href = "{{ route('gallery.index', ['type' => 7]) }}";
            }
            
            // Get modal elements
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const closeModal = document.getElementById('closeModal');
            
            // Get all images that can be previewed
            const previewImages = document.querySelectorAll('.image-preview-trigger');
            
            // Add click event to all preview images
            previewImages.forEach(function(image) {
                image.addEventListener('click', function() {
                    // Get the full-size image URL
                    const imgSrc = this.getAttribute('data-src');
                    
                    // Set the modal image source
                    modalImage.src = imgSrc;
                    
                    // Show the modal
                    imageModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
            
            // Close modal when clicking the close button
            closeModal.addEventListener('click', function() {
                imageModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
            
            // Close modal when clicking outside the image
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    imageModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            // Close modal when pressing ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                    imageModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>
</x-app-layout>