@extends('layouts.teacher')

@section('title', 'Lihat Pengumpulan')

@section('content')
<div class="container py-3 sm:py-4">
    <div class="max-w-7xl mx-auto px-3 sm:px-0">
        <!-- Responsive Breadcrumb Navigation -->
        <div class="mb-3 sm:mb-4 flex flex-wrap items-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('teacher.classrooms.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Kelas</a>
            <span class="mx-1 sm:mx-2">›</span>
            <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 truncate max-w-[100px] sm:max-w-xs">{{ $classroom->name }}</a>
            <span class="mx-1 sm:mx-2">›</span>
            <a href="{{ route('teacher.assignments.show', [$classroom->id, $assignment->id]) }}" class="hover:text-blue-600 dark:hover:text-blue-400 truncate max-w-[100px] sm:max-w-xs">{{ $assignment->title }}</a>
            <span class="mx-1 sm:mx-2">›</span>
            <span class="hidden sm:inline">
                <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="hover:text-blue-600 dark:hover:text-blue-400">Pengumpulan</a>
                <span class="mx-1 sm:mx-2">›</span>
            </span>
            <span class="text-gray-700 dark:text-gray-300 truncate max-w-[150px] sm:max-w-xs">Pengumpulan {{ $submission->user->name }}</span>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Left Column: Student Information & Answer -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden h-full">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white">Detail & Jawaban</h2>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($isLate)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                Terlambat
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Tepat Waktu
                            </span>
                        @endif

                        @if($submission->graded)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                Dinilai: {{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Belum Dinilai
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <!-- Submission Metadata -->
                    <div class="mb-4 sm:mb-6">
                        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-3 sm:mb-4 text-xs sm:text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Dikumpulkan oleh</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $submission->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Tanggal pengumpulan</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    {{ date('j F Y, H:i', strtotime($submission->submitted_at)) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Tenggat tugas</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    {{ date('j F Y, H:i', strtotime($assignment->due_date)) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Status pengumpulan</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    @if($isLate)
                                        <span class="text-red-600 dark:text-red-400">
                                            Terlambat ({{ $submissionDate->diffForHumans($dueDate, true) }} setelah tenggat)
                                        </span>
                                    @else
                                        <span class="text-green-600 dark:text-green-400">
                                            Tepat waktu ({{ $submissionDate->diffForHumans($dueDate, true) }} sebelum tenggat)
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 sm:my-6 border-gray-200 dark:border-gray-700">

                    <!-- Student Answer -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-800 dark:text-white mb-3 sm:mb-4">
                            Jawaban Siswa
                        </h3>
                        
                        @if($submission->content)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                                <div class="text-gray-700 dark:text-gray-300 prose max-w-none dark:prose-invert text-sm sm:text-base">
                                    {!! nl2br(e($submission->content)) !!}
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                                <p class="text-gray-500 dark:text-gray-400 italic text-sm">Tidak ada jawaban tertulis.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- External Link (if provided) -->
                    @if($submission->link)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-2">Link Terlampir:</h4>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <a href="{{ $submission->link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    {{ $submission->link }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: File Preview -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden h-full">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white">File Terlampir</h2>
                </div>
                
                <div class="p-4 sm:p-6">
                    @if($submission->file)
                        @php
                            $filePath = 'storage/' . $submission->file;
                            $fileName = basename($submission->file);
                            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            $isPdf = $fileExtension === 'pdf';
                        @endphp

                        <!-- File info section (modified) -->
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-3">
                                    @if($isImage)
                                        <svg class="w-8 h-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($isPdf)
                                        <svg class="w-8 h-8 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $fileName }}</p>
                                </div>
                            </div>
                            
                            <!-- Download button moved to be next to filename -->
                            <a href="{{ route('teacher.submissions.download', [$classroom->id, $assignment->id, $submission->id]) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh
                            </a>
                        </div>

                        <!-- File Preview -->
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                            @if($isImage)
                                <!-- Image preview -->
                                <div class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                    <img src="{{ asset($filePath) }}" alt="Preview" class="max-w-full max-h-[400px] object-contain" onclick="openImagePreview('{{ asset($filePath) }}')">
                                </div>
                                <p class="p-2 text-xs text-center text-gray-500 dark:text-gray-400">Klik gambar untuk memperbesar</p>
                            @elseif($isPdf)
                                <!-- PDF preview -->
                                <div class="relative bg-gray-100 dark:bg-gray-900">
                                    <iframe src="{{ asset($filePath) }}#toolbar=0" class="w-full h-[400px]" frameborder="0"></iframe>
                                </div>
                                <p class="p-2 text-xs text-center text-gray-500 dark:text-gray-400">Jika PDF tidak tampil dengan baik, silakan unduh file.</p>
                            @else
                                <!-- Generic file type indicator -->
                                <div class="py-16 px-6 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700">
                                    <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                                        File {{ strtoupper($fileExtension) }} tidak dapat ditampilkan pratinjau.<br>
                                        Silakan unduh file untuk melihat isinya.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-16 px-6 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Tidak ada file yang dilampirkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grading Section (Below the preview) -->
        <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white">Penilaian</h2>
            </div>
            
            <div class="p-4 sm:p-6">
                <form action="{{ route('teacher.submissions.grade', [$classroom->id, $assignment->id, $submission->id]) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="grade" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nilai (dari {{ $assignment->max_points ?? 100 }})
                            </label>
                            <input 
                                type="number" 
                                name="grade" 
                                id="grade" 
                                min="0" 
                                max="{{ $assignment->max_points ?? 100 }}"
                                value="{{ $submission->grade ?? '' }}" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                required
                            >
                        </div>
                        
                        <div class="md:text-right self-end">
                            <button type="submit" class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $submission->graded ? 'Perbarui Nilai' : 'Kirim Nilai' }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="feedback" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Umpan Balik untuk Siswa
                        </label>
                        <textarea 
                            name="feedback" 
                            id="feedback" 
                            rows="4" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                        >{{ $submission->feedback ?? '' }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Berikan umpan balik yang konstruktif untuk membantu siswa memahami nilainya.
                        </p>
                    </div>
                </form>

                <!-- Navigation button -->
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Semua Pengumpulan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 hidden" onclick="closeImagePreview()">
    <div class="relative max-w-4xl max-h-screen p-4">
        <button onclick="event.stopPropagation(); closeImagePreview()" class="absolute top-2 right-2 bg-gray-800/80 rounded-full p-2 text-white hover:bg-gray-700/80 transition-colors">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-[80vh] object-contain">
    </div>
</div>

<script>
// Image preview functions
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
@endsection