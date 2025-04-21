<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('View Submission') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('submissions.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Added mt-16 class to add top margin avoiding navbar overlap -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 h-[calc(100vh-64px)] overflow-auto mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="grid grid-cols-12 gap-4 p-4">
                    <!-- Left Column: Submission Details -->
                    <div class="col-span-12 md:col-span-4 space-y-3">
                        <div class="bg-gray-900/50 rounded-xl p-4">
                            <h3 class="text-xl font-bold text-gray-100 truncate">{{ $submission->title }}</h3>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400 mb-2">
                                <span>{{ $submission->created_at->format('M j, Y') }}</span>
                                <span>•</span>
                                <span>{{ $submission->createdBy->name ?? 'Unknown' }}</span>
                                <span>•</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $submission->statusOption->value === 'pending' ? 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30' : 
                                       ($submission->statusOption->value === 'accepted' ? 'bg-green-900/30 text-green-300 border-green-500/30' : 
                                       'bg-red-900/30 text-red-300 border-red-500/30') }} border">
                                    {{ ucfirst($submission->statusOption->value) }}
                                </span>
                            </div>
                            
                            @if($submission->content)
                                <div class="text-gray-300 text-sm max-h-24 overflow-y-auto">
                                    {{ $submission->content }}
                                </div>
                            @endif
                        </div>

                        <!-- Approval/Rejection Details -->
                        @if($submission->approve_at)
                            <div class="{{ $submission->statusOption->value === 'declined' ? 'bg-red-900/20 border-red-500/20' : 'bg-blue-900/20 border-blue-500/20' }} rounded-xl p-3 border">
                                <h4 class="text-sm font-medium text-gray-200 flex items-center mb-1">
                                    @if($submission->statusOption->value === 'declined')
                                        <svg class="w-4 h-4 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail Penolakan
                                    @else
                                        <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Detail Persetujuan
                                    @endif
                                </h4>
                                <p class="text-gray-300 text-xs">
                                    @if($submission->statusOption->value === 'declined')
                                        Ditolak oleh: {{ $submission->approvedBy->name ?? 'Sistem' }}<br>
                                    @else
                                        Oleh: {{ $submission->approvedBy->name ?? 'Sistem' }}<br>
                                    @endif
                                    {{ $submission->approve_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        @endif

                        <!-- Back to List Button (Bottom) -->
                        <div class="pt-2">
                            <a href="{{ route('submissions.index') }}" 
                               class="inline-flex items-center w-full justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Submissions
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: File Preview -->
                    <div class="col-span-12 md:col-span-8">
                        <div class="bg-gray-900/50 rounded-xl p-4 h-[calc(100vh-180px)]">
                            <!-- Link/Video Preview -->
                            @if($submission->link)
                                <div class="bg-gray-800/50 rounded-xl border border-gray-700/50 overflow-hidden h-full">
                                    <div class="relative w-full h-full bg-gray-900/50">
                                        @php
                                            $videoId = null;
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $submission->link, $match)) {
                                                $videoId = $match[1];
                                            }
                                        @endphp
                                        
                                        @if($videoId)
                                            <iframe 
                                                src="https://www.youtube.com/embed/{{ $videoId }}"
                                                class="absolute inset-0 w-full h-full"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <iframe 
                                                src="{{ $submission->link }}"
                                                class="absolute inset-0 w-full h-full"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        @endif
                                    </div>
                                </div>
                            
                            <!-- Image Preview -->
                            @elseif($submission->img)
                                <div class="bg-gray-800/50 rounded-xl border border-gray-700/50 overflow-hidden h-full">
                                    @php
                                        $imgPath = $submission->img;
                                        if (!str_contains($imgPath, 'storage/')) {
                                            if (strpos($imgPath, 'submissions/images/') !== 0) {
                                                $imgPath = 'submissions/images/' . $imgPath;
                                            }
                                            $fullImageUrl = Storage::url($imgPath);
                                        } else {
                                            $fullImageUrl = $imgPath;
                                        }
                                    @endphp
                                    <img src="{{ $fullImageUrl }}"
                                         alt="Submission Image"
                                         class="w-full h-full object-contain bg-gray-900/50 image-preview-trigger cursor-pointer"
                                         data-src="{{ $fullImageUrl }}">
                                </div>

                            <!-- File Preview -->
                            @elseif($submission->file)
                                @php
                                    $fileExtension = strtolower(pathinfo($submission->file, PATHINFO_EXTENSION));
                                    $fileName = basename($submission->file);
                                @endphp

                                <!-- File Info Header -->
                                <div class="flex items-center justify-between bg-gray-800/50 rounded-lg p-2 border border-gray-700/50 mb-2">
                                    <!-- File Icon and Name -->
                                    <div class="flex items-center gap-2">
                                        <div class="p-1 bg-blue-600/20 rounded-lg">
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
                                            <div class="text-sm font-medium text-gray-200 truncate max-w-[200px]">{{ $fileName }}</div>
                                            <div class="text-xs text-gray-400">{{ strtoupper($fileExtension) }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Download Button -->
                                    <a href="{{ Storage::url($submission->file) }}" 
                                       download="{{ $fileName }}"
                                       class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-1 text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </div>

                                <!-- File Preview -->
                                <div class="bg-gray-800/50 rounded-xl border border-gray-700/50 overflow-hidden h-[calc(100%-40px)]">
                                    @if(in_array($fileExtension, ['pdf', 'doc', 'docx']))
                                        <iframe src="{{ route('preview.show', $submission->id) }}"
                                                class="w-full h-full"
                                                frameborder="0">
                                        </iframe>
                                    @else
                                        <div class="p-8 text-center h-full flex items-center justify-center">
                                            <p class="text-gray-400">Preview tidak tersedia untuk jenis file ini.</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-8 text-center h-full flex items-center justify-center">
                                    <p class="text-gray-400">No content available for preview.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');
        const previewImages = document.querySelectorAll('.image-preview-trigger');
        
        previewImages.forEach(function(img) {
            img.addEventListener('click', function() {
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
        
        imageModal.addEventListener('click', function(e) {
            if (e.target === imageModal) {
                imageModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                modalImage.src = '';
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!imageModal.classList.contains('hidden')) {
                    imageModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    modalImage.src = '';
                }
            }
        });
    });
    </script>
</x-app-layout>