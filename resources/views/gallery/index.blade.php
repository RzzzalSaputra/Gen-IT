<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gallery') }}
        </h2>
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

    <div class="pt-16 bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-12">
            <!-- Filter Tabs -->
            <div class="mb-8 border-b border-gray-700 text-center">
                <ul class="flex flex-wrap -mb-px text-sm font-medium justify-center">
                    <li class="mx-4">
                        <a href="{{ route('gallery.index', ['type' => 7]) }}" class="inline-block p-4 {{ request()->input('type') == 7 || !request()->has('type') ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-300 hover:text-blue-300 hover:border-gray-600' }}">
                            Images
                        </a>
                    </li>
                    <li class="mx-4">
                        <a href="{{ route('gallery.index', ['type' => 8]) }}" class="inline-block p-4 {{ request()->input('type') == 8 ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-300 hover:text-blue-300 hover:border-gray-600' }}">
                            Videos
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-900 overflow-hidden shadow-2xl rounded-lg">
                <div class="p-6">
                    @if($galleries->isEmpty())
                        <div class="text-center py-12">
                            <h3 class="text-lg font-semibold mb-2 text-gray-200">No Gallery Items Found</h3>
                            <p class="text-gray-400">Gallery items will appear here once they are added.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($galleries as $gallery)
                            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                                @if($gallery->type == 7 && $gallery->file)
                                    <div class="aspect-w-16 aspect-h-9 overflow-hidden cursor-pointer image-preview-trigger" data-src="{{ $gallery->file }}">
                                        <img src="{{ $gallery->file }}" alt="{{ $gallery->title }}" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                                
                                <div class="p-5">
                                    <h3 class="text-xl font-bold text-gray-100 mb-3 text-center">{{ $gallery->title }}</h3>
                                    
                                    @if($gallery->type == 8 && $gallery->link)
                                        @php
                                            $videoId = null;
                                            // Enhanced regex to handle more YouTube URL formats
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $gallery->link, $match)) {
                                                $videoId = $match[1];
                                            }
                                        @endphp
                                        
                                        @if($videoId)
                                            <div class="aspect-w-16 aspect-h-9 mt-2 mb-3">
                                                <iframe 
                                                    src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen
                                                    class="w-full h-56 rounded-lg"
                                                ></iframe>
                                            </div>
                                        @else
                                            <a href="{{ $gallery->link }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm mb-4 block text-center">
                                                {{ $gallery->link }}
                                            </a>
                                        @endif
                                    @endif
                                    
                                    <div class="flex justify-center items-center mt-4 text-sm text-gray-400">
                                        <span>{{ $gallery->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 flex justify-center">
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