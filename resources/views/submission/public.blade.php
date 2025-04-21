<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Community Submissions') }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $submissions->total() }} {{ Str::plural('submission', $submissions->total()) }} available
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Image Preview Modal -->
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

    <!-- Video Preview Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full">
            <button id="closeVideoModal" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="aspect-w-16 aspect-h-9">
                <iframe id="videoFrame" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div id="filePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="inline-block align-middle h-screen">
                <div class="relative inline-block w-full max-w-6xl p-6 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-2xl">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" class="close-file-preview text-gray-400 hover:text-gray-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-8">
                        <iframe id="filePreviewFrame" class="w-full h-[80vh]" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- My Submissions Button - Positioned above search form -->
            @if(Auth::check())
            <div class="flex justify-end mb-6">
                <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-200 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    {{ __('My Submissions') }}
                </a>
            </div>
            @endif
            
            <!-- Search Form -->
            <div class="mb-8">
                <form action="{{ route('submissions.public') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                               placeholder="Cari Submission Komunitas ...">
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </form>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($submissions->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Submissions Found</h3>
                            <p class="text-gray-400">Accepted submissions will appear here once they are approved.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($submissions as $submission)
                                @php
                                    $isVideo = false;
                                    $videoId = null;
                                    if (isset($submission->link) && $submission->link) {
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $submission->link, $match)) {
                                            $isVideo = true;
                                            $videoId = $match[1];
                                        }
                                    }

                                    $hasFile = !empty($submission->file);
                                    $fileExtension = null;
                                    if ($hasFile) {
                                        $fileExtension = pathinfo($submission->file, PATHINFO_EXTENSION);
                                        $fileExtension = strtoupper($fileExtension);
                                    }
                                    
                                    $submissionType = $submission->typeOption->value ?? 'text';
                                @endphp

                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    @if($isVideo && $videoId)
                                        <!-- Video Content Card -->
                                        <div class="h-48 bg-black relative overflow-hidden">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <button class="video-preview-trigger w-full h-full flex items-center justify-center group" data-video-id="{{ $videoId }}">
                                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" 
                                                         alt="Video thumbnail" 
                                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-80 group-hover:opacity-90 transition-opacity">
                                                        <svg class="w-16 h-16 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="absolute top-3 right-3">
                                                <span class="px-2 py-1 text-xs font-medium text-purple-300 bg-purple-900/30 backdrop-blur-sm rounded-lg border border-purple-500/20">
                                                    VIDEO
                                                </span>
                                            </div>
                                        </div>
                                    @elseif($submission->img)
                                        <!-- Image Content Card -->
                                        <div class="h-48 relative overflow-hidden">
                                            <button class="image-preview-trigger w-full h-full block" data-src="{{ $submission->img }}">
                                                <img src="{{ $submission->img }}" 
                                                    alt="{{ $submission->title }}" 
                                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
                                                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                            </button>
                                        </div>
                                    @elseif($hasFile)
                                        <!-- File Content Card -->
                                        <div class="h-48 flex items-center justify-center bg-gradient-to-br from-purple-600/20 to-pink-600/20 p-6 relative overflow-hidden">
                                            <div class="absolute top-3 right-3">
                                                <span class="px-2 py-1 text-xs font-medium text-purple-300 bg-purple-900/30 backdrop-blur-sm rounded-lg border border-purple-500/20">
                                                    {{ $fileExtension ?? 'FILE' }}
                                                </span>
                                            </div>
                                            <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                                                    @if($fileExtension == 'PDF')
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @elseif($fileExtension == 'DOC' || $fileExtension == 'DOCX')
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $submission->title }}</h4>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Text Content Card -->
                                        <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6">
                                            <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $submission->title }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:text-blue-400 transition-colors duration-200">
                                            {{ $submission->title }}
                                        </h3>
                                        
                                        <div class="text-sm text-gray-400 mb-4 line-clamp-3">
                                            {{ strip_tags($submission->content) }}
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($hasFile)
                                                <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Downloadable
                                                </div>
                                            @endif
                                            
                                            @if($isVideo)
                                                <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Video
                                                </div>
                                            @endif
                                            
                                            @if($submission->typeOption)
                                                <div class="inline-flex items-center px-2 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-xs text-blue-300 border border-blue-500/30">
                                                    {{ $submission->typeOption->value }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-6">
                                            <span class="text-sm text-gray-500">{{ $submission->created_at->diffForHumans() }}</span>
                                            
                                            <div class="flex items-center gap-3">
                                                @if($hasFile)
                                                    <div class="flex space-x-2">
                                                        @if(in_array($fileExtension, ['PDF', 'DOC', 'DOCX']))
                                                            <a href="{{ route('preview.show', $submission->id) }}" 
                                                               target="_blank"
                                                               class="text-blue-400 hover:text-blue-300 inline-flex items-center text-sm font-medium transition-colors duration-200">
                                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                                Preview
                                                            </a>
                                                        @endif
                                                        
                                                        <a href="{{ $submission->file }}" download class="text-green-400 hover:text-green-300 inline-flex items-center text-sm font-medium transition-colors duration-200">
                                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </div>
                                                @endif
                                                
                                                @if($isVideo && $videoId)
                                                    <button class="video-preview-trigger text-red-400 hover:text-red-300 inline-flex items-center text-sm font-medium transition-colors duration-200" data-video-id="{{ $videoId }}">
                                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Tonton Video
                                                    </button>
                                                @endif
                                                
                                                @if($submission->img)
                                                    <button class="image-preview-trigger text-blue-400 hover:text-blue-300 inline-flex items-center text-sm font-medium transition-colors duration-200" data-src="{{ $submission->img }}">
                                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Liat Gambar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $submissions->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview Modal
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const closeModal = document.getElementById('closeModal');
            const previewImages = document.querySelectorAll('.image-preview-trigger');
            
            // Video Preview Modal
            const videoModal = document.getElementById('videoModal');
            const videoFrame = document.getElementById('videoFrame');
            const closeVideoModal = document.getElementById('closeVideoModal');
            const videoPreviewButtons = document.querySelectorAll('.video-preview-trigger');
            
            // File Preview Modal
            const filePreviewModal = document.getElementById('filePreviewModal');
            const filePreviewFrame = document.getElementById('filePreviewFrame');
            const closeFilePreviewButtons = document.querySelectorAll('.close-file-preview');
            const filePreviewButtons = document.querySelectorAll('.file-preview-trigger');
            
            // Image Preview Functionality
            previewImages.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const imgSrc = this.getAttribute('data-src');
                    modalImage.src = imgSrc;
                    imageModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
            
            closeModal.addEventListener('click', function() {
                imageModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                modalImage.src = '';
            });
            
            // Video Preview Functionality
            videoPreviewButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const videoId = this.getAttribute('data-video-id');
                    videoFrame.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                    videoModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
            
            closeVideoModal.addEventListener('click', function() {
                videoModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                videoFrame.src = '';
            });
            
            // File Preview Functionality
            filePreviewButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const previewUrl = button.getAttribute('data-preview-url');
                    
                    // Show loading state
                    filePreviewFrame.src = previewUrl;
                    filePreviewModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    
                    // Add loading indicator
                    const loadingIndicator = document.createElement('div');
                    loadingIndicator.className = 'loading-indicator absolute inset-0 flex items-center justify-center bg-gray-900/50';
                    loadingIndicator.innerHTML = '<div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>';
                    
                    const container = filePreviewFrame.parentNode;
                    container.insertBefore(loadingIndicator, filePreviewFrame);
                    
                    // Remove loading indicator once frame is loaded
                    filePreviewFrame.onload = () => {
                        const loadingIndicator = filePreviewModal.querySelector('.loading-indicator');
                        if (loadingIndicator) {
                            loadingIndicator.remove();
                        }
                    };
                });
            });

            closeFilePreviewButtons.forEach(button => {
                button.addEventListener('click', closeFilePreview);
            });

            function closeFilePreview() {
                filePreviewModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                filePreviewFrame.src = '';
                
                // Remove any remaining loading indicators
                const loadingIndicator = filePreviewModal.querySelector('.loading-indicator');
                if (loadingIndicator) {
                    loadingIndicator.remove();
                }
            }
            
            // Close modals on outside click
            [imageModal, videoModal, filePreviewModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        if (modal === imageModal) {
                            imageModal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                            modalImage.src = '';
                        } else if (modal === videoModal) {
                            videoModal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                            videoFrame.src = '';
                        } else if (modal === filePreviewModal) {
                            closeFilePreview();
                        }
                    }
                });
            });
            
            // Close modals on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!imageModal.classList.contains('hidden')) {
                        imageModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        modalImage.src = '';
                    }
                    if (!videoModal.classList.contains('hidden')) {
                        videoModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        videoFrame.src = '';
                    }
                    if (!filePreviewModal.classList.contains('hidden')) {
                        closeFilePreview();
                    }
                }
            });
        });
    </script>
</x-app-layout>