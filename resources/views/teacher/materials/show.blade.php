@extends('layouts.teacher')

@section('title', $material->title)

@section('content')
<div class="container py-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <!-- Header with actions -->
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex items-center">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $material->title }}</h1>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ditambahkan pada {{ date('d M Y', strtotime($material->create_at)) }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <button data-modal-target="editMaterialModal{{ $material->id }}" data-modal-toggle="editMaterialModal{{ $material->id }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ubah
                    </button>
                    <button data-modal-target="deleteMaterialModal{{ $material->id }}" data-modal-toggle="deleteMaterialModal{{ $material->id }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                    <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Kelas
                    </a>
                </div>
            </div>

            <!-- Material Content -->
            <div class="p-6">
                <!-- Material Content with improved text color -->
                <div class="prose prose-lg max-w-none dark:prose-invert text-gray-800 dark:text-white mb-6">
                    {!! $material->content !!}
                </div>

                <!-- Material Link -->
                @if($material->link)
                    @php
                        $isYoutubeLink = strpos($material->link, 'youtube.com') !== false || strpos($material->link, 'youtu.be') !== false;
                        $videoId = '';
                        
                        if ($isYoutubeLink) {
                            if (strpos($material->link, 'youtube.com/watch?v=') !== false) {
                                $parts = parse_url($material->link);
                                parse_str($parts['query'] ?? '', $query);
                                $videoId = $query['v'] ?? '';
                            } elseif (strpos($material->link, 'youtu.be/') !== false) {
                                $videoId = substr($material->link, strrpos($material->link, '/') + 1);
                            }
                        }
                    @endphp

                    @if($isYoutubeLink && $videoId)
                        <div class="mt-6 mx-auto max-w-3xl">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video Tersemat:</h3>
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    class="absolute inset-0 w-full h-full rounded-lg"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <div class="mt-2">
                                <a href="{{ $material->link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center text-sm">
                                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Buka di YouTube
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tautan Eksternal:</h3>
                            <a href="{{ $material->link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                {{ $material->link }}
                            </a>
                        </div>
                    @endif
                @endif

                <!-- Material Image - Removed "Cover Image:" text -->
                @if($material->img)
                <div class="mt-6">
                    <img src="{{ asset('storage/' . $material->img) }}" alt="{{ $material->title }}" class="rounded-lg max-h-96 mx-auto">
                </div>
                @endif

                <!-- Material File -->
                @if($material->file)
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Berkas Terlampir:</h3>
                    @php
                        $fileExtension = strtolower(pathinfo($material->file, PATHINFO_EXTENSION));
                        $fileName = basename($material->file);
                    @endphp
                    
                    <!-- File Info Header -->
                    <div class="flex items-center justify-between mb-4">
                        <!-- File Icon and Name -->
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                @switch($fileExtension)
                                    @case('pdf')
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        @break
                                    @case('doc')
                                    @case('docx')
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @break
                                    @case('xls')
                                    @case('xlsx')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                @endswitch
                            </div>
                            <span class="text-gray-700 dark:text-gray-300">{{ $fileName }}</span>
                        </div>
                        
                        <!-- Download Button - FIXED ROUTE -->
                        <a href="{{ route('teacher.classrooms.materials.download', ['classroom_id' => $classroom->id, 'id' => $material->id]) }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh
                        </a>
                    </div>

                    <!-- File Preview -->
                    @if(in_array($fileExtension, ['pdf']))
                        <div class="bg-gray-50 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden h-[400px]">
                            <iframe src="{{ Storage::url($material->file) }}" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <div class="bg-gray-50 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden p-4 text-center">
                            <img src="{{ Storage::url($material->file) }}" alt="{{ $material->title }}" 
                                 class="max-h-[400px] mx-auto object-contain cursor-pointer" 
                                 onclick="openImagePreview(this.src)">
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 mb-3">Pratinjau tidak tersedia untuk jenis berkas ini.</p>
                            <a href="{{ route('teacher.classrooms.materials.download', ['classroom_id' => $classroom->id, 'id' => $material->id]) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh untuk Melihat
                            </a>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add these modals and script for the edit/delete functionality -->
@include('teacher.classrooms.partials.modals.edit-material', ['material' => $material])
@include('teacher.classrooms.partials.modals.delete-material', ['material' => $material])

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modalButtons = document.querySelectorAll('[data-modal-target]');
    modalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');
            openModal(modalId);
        });
    });
});

// Functions to open and close modals
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Image Preview functions
function openImagePreview(src) {
    document.getElementById('previewImage').src = src;
    document.getElementById('imagePreviewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeImagePreview() {
    document.getElementById('imagePreviewModal').classList.add('hidden');
    document.body.style.overflow = ''; // Enable scrolling
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
    }
});
</script>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 hidden" onclick="closeImagePreview()">
    <div class="relative max-w-4xl max-h-screen p-4">
        <button onclick="event.stopPropagation(); closeImagePreview()" class="absolute top-2 right-2 bg-gray-800 rounded-full p-2 text-white hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="previewImage" src="" alt="Pratinjau" class="max-w-full max-h-[80vh] object-contain">
    </div>
</div>
@endsection