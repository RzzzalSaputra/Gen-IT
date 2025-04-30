<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('SubmissionSaya') }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $submissions->total() }} {{ Str::plural('submisi', $submissions->total()) }} terkirim
                </div>
                <a href="{{ route('submissions.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    SubmissionBaru
                </a>
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

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if(session('success'))
                <div class="mb-6 bg-green-900/30 text-green-300 p-4 rounded-lg border border-green-500/30">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Create New Submission Card -->
            <div class="bg-gradient-to-r from-blue-900/50 to-purple-900/50 backdrop-blur-sm rounded-2xl border border-blue-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-100 mb-2">
                                Ingin berbagi sesuatu?
                            </h3>
                            <p class="text-gray-300 mb-4">
                                Kirim konten Anda dan kami akan meninjau dalam 24-48 jam.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-blue-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('submissions.create') }}" 
                        class="mt-2 inline-flex w-full sm:w-auto justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-blue-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Submission Baru
                    </a>
                </div>
            </div>

            <!-- Submissions History Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-200">Riwayat Submission Anda</h3>
                </div>
                <div class="p-6">
                    @if($submissions->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">Belum Ada Submisi</h3>
                            <p class="text-gray-400 mb-6">Mulai dengan membuat Submission pertama Anda.</p>
                            <a href="{{ route('submissions.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-xl hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Submission Pertama
                            </a>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($submissions as $submission)
                                <div class="bg-gray-800/30 rounded-xl overflow-hidden shadow-lg border border-gray-700/30 transition-all duration-300 hover:border-blue-500/50 hover:shadow-blue-500/5 cursor-pointer submission-item" 
                                     data-url="{{ route('submissions.show', $submission->id) }}">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-100 mb-3">
                                                    {{ $submission->title }}
                                                </h3>
                                                <div class="text-sm text-gray-400 mb-4">
                                                    Dikirim {{ $submission->created_at->locale('id')->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div>
                                                @php
                                                    $statusClass = '';
                                                    if ($submission->statusOption->value == 'pending') {
                                                        $statusClass = 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30';
                                                    } elseif ($submission->statusOption->value == 'accepted') {
                                                        $statusClass = 'bg-green-900/30 text-green-300 border-green-500/30';
                                                    } elseif ($submission->statusOption->value == 'declined') {
                                                        $statusClass = 'bg-red-900/30 text-red-300 border-red-500/30';
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $statusClass }} backdrop-blur-sm border">
                                                    <span class="w-2 h-2 rounded-full mr-2 {{ 
                                                        $submission->statusOption->value == 'pending' ? 'bg-yellow-400' : 
                                                        ($submission->statusOption->value == 'accepted' ? 'bg-green-400' : 'bg-red-400') 
                                                    }}"></span>
                                                    {{ $submission->statusOption->value == 'pending' ? 'Pending' : 
                                                       ($submission->statusOption->value == 'accepted' ? 'Diterima' : 'Ditolak') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Content Section First -->
                                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4 text-gray-300">
                                            {{ $submission->content }}
                                        </div>

                                        <!-- Preview Section After Content -->
                                        @if($submission->img)
                                            <button class="image-preview-trigger inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors gap-1.5" 
                                                    data-src="{{ Storage::url($submission->img) }}"
                                                    onclick="event.stopPropagation();">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Lihat Gambar
                                            </button>
                                        @elseif($submission->link && str_contains($submission->link, 'youtube.com'))
                                            @php
                                                $videoId = null;
                                                if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $submission->link, $match)) {
                                                    $videoId = $match[1];
                                                }
                                            @endphp
                                            @if($videoId)
                                                <button class="video-preview-trigger inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors gap-1.5" 
                                                        data-video-id="{{ $videoId }}"
                                                        onclick="event.stopPropagation();">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Putar Video
                                                </button>
                                            @endif
                                        @endif

                                        @if($submission->file)
                                            @php
                                                $fileExtension = strtolower(pathinfo($submission->file, PATHINFO_EXTENSION));
                                                $fileName = basename($submission->file);
                                            @endphp
                                            
                                            <div class="mt-4 mb-4">
                                                <div class="bg-gray-900/50 rounded-xl p-4">
                                                    <!-- File Info -->
                                                    <div class="flex items-center justify-between bg-gray-800/50 rounded-lg p-3 border border-gray-700/50 mb-4"
                                                         onclick="event.stopPropagation();">
                                                        <div class="flex items-center gap-3">
                                                            <div class="p-2 bg-blue-600/20 rounded-lg">
                                                                @switch($fileExtension)
                                                                    @case('pdf')
                                                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                        </svg>
                                                                        @break
                                                                    @case('doc')
                                                                    @case('docx')
                                                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                        @break
                                                                    @default
                                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                @endswitch
                                                            </div>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-200">{{ $fileName }}</div>
                                                                <div class="text-xs text-gray-400">File {{ strtoupper($fileExtension) }}</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ route('preview.show', $submission->id) }}" 
                                                               target="_blank"
                                                               class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1.5"
                                                               onclick="event.stopPropagation();">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                                Preview
                                                            </a>
                                                            
                                                            <a href="{{ Storage::url($submission->file) }}" 
                                                               download="{{ $fileName }}"
                                                               class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1.5"
                                                               onclick="event.stopPropagation();">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($submission->approve_at)
                                            <div class="mt-6">
                                                <h4 class="text-lg font-medium text-gray-200 mb-2 flex items-center">
                                                    @if($submission->statusOption->value == 'declined')
                                                        <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Detail Penolakan
                                                    @else
                                                        <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Detail Persetujuan
                                                    @endif
                                                </h4>
                                                <div class="{{ $submission->statusOption->value == 'declined' ? 'bg-red-900/20 border-red-500/20' : 'bg-blue-900/20 border-blue-500/20' }} rounded-xl p-4 border">
                                                    <p class="text-gray-300">
                                                        @if($submission->statusOption->value == 'declined')
                                                            Ditolak oleh: {{ $submission->approvedBy->name ?? 'Sistem' }}<br>
                                                            Tanggal: {{ $submission->approve_at->format('F j, Y H:i') }}
                                                        @else
                                                            Disetujui oleh: {{ $submission->approvedBy->name ?? 'Sistem' }}<br>
                                                            Tanggal: {{ $submission->approve_at->format('F j, Y H:i') }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center mt-6">
                                            <div class="flex gap-4 text-sm text-gray-500">
                                                <span>Tipe: {{ $submission->typeOption->value }}</span>
                                                <span>Dibuat oleh: {{ $submission->createdBy->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="mt-8">
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add File Preview Modal -->
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
            
            // Image Preview Functionality
            previewImages.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
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
                    e.stopPropagation();
                    const videoId = this.getAttribute('data-video-id');
                    videoFrame.src = `https://www.youtube.com/embed/${videoId}`;
                    videoModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
            
            closeVideoModal.addEventListener('click', function() {
                videoModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                videoFrame.src = '';
            });
            
            // Close modals on outside click
            [imageModal, videoModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        if (modal === videoModal) {
                            videoFrame.src = '';
                        } else {
                            modalImage.src = '';
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
                }
            });

            // File Preview Modal
            const filePreviewButtons = document.querySelectorAll('.file-preview-trigger');
            const filePreviewModal = document.getElementById('filePreviewModal');
            const filePreviewFrame = document.getElementById('filePreviewFrame');
            const closeFilePreviewButtons = document.querySelectorAll('.close-file-preview');

            filePreviewButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const previewUrl = button.getAttribute('data-preview-url');
                    
                    // Show loading state
                    filePreviewFrame.src = previewUrl;
                    filePreviewModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    
                    // Add loading indicator
                    filePreviewFrame.insertAdjacentHTML('beforebegin', `
                        <div class="loading-indicator absolute inset-0 flex items-center justify-center bg-gray-900/50">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
                        </div>
                    `);
                    
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

            filePreviewModal.addEventListener('click', (e) => {
                if (e.target === filePreviewModal) {
                    closeFilePreview();
                }
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

            // Add to existing keydown event listener for ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !filePreviewModal.classList.contains('hidden')) {
                    closeFilePreview();
                }
            });

            // Add this to your existing script section
            const submissionItems = document.querySelectorAll('.submission-item');
            submissionItems.forEach(item => {
                item.addEventListener('click', function() {
                    window.location.href = this.getAttribute('data-url');
                });
            });
        });
    </script>
</x-app-layout>