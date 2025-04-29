@extends('layouts.teacher')

@section('title', $assignment->title)

@section('content')
<div class="container py-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <!-- Header with actions -->
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $assignment->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Tenggat: {{ date('d M Y, H:i', strtotime($assignment->due_date)) }}
                        @php
                            $now = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $now->isAfter($dueDate);
                            
                            // Calculate submission statistics
                            $totalStudents = $classroom->members()->where('role', 'student')->count();
                            $submittedCount = $assignment->submissions()->count();
                            $notSubmittedCount = $totalStudents - $submittedCount;
                            
                            if($isOverdue) {
                                if($notSubmittedCount === 0) {
                                    // All submissions received
                                    echo '<span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium rounded-full">Selesai</span>';
                                } else {
                                    // Some students haven\'t submitted
                                    echo '<span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium rounded-full">Terlambat</span>';
                                }
                            } else {
                                $diff = $now->diffInDays($dueDate, false);
                                if($diff <= 3 && $diff >= 0) {
                                    echo '<span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium rounded-full">Segera berakhir</span>';
                                }
                            }
                        @endphp
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <button data-modal-target="editAssignmentModal{{ $assignment->id }}" data-modal-toggle="editAssignmentModal{{ $assignment->id }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ubah
                    </button>
                    <button data-modal-target="deleteAssignmentModal{{ $assignment->id }}" data-modal-toggle="deleteAssignmentModal{{ $assignment->id }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                    <a href="{{ route('teacher.classrooms.show', $classroom->id) }}#assignments" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Kelas
                    </a>
                </div>
            </div>

            <!-- Assignment Content -->
            <div class="p-6 text-gray-800 dark:text-white">
                <!-- Assignment Description -->
                <div class="prose prose-lg max-w-none dark:prose-invert mb-6">
                    {!! $assignment->description !!}
                </div>

                <!-- Assignment File -->
                @if($assignment->file)
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">File Terlampir:</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-200">{{ basename($assignment->file) }}</span>
                        <a href="{{ route('teacher.assignments.download', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh
                        </a>
                    </div>
                </div>
                @endif

                <!-- Submission Statistics -->
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Statistik Pengumpulan</h3>
                        <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Lihat Semua Jawaban
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @php
                            $gradedCount = $assignment->submissions()->where('graded', true)->count();
                            $ungradedCount = $submittedCount - $gradedCount;
                        @endphp
                        
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600 h-24 flex flex-col justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Siswa</h4>
                                </div>
                            </div>
                            <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $totalStudents }}</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600 h-24 flex flex-col justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Dikumpulkan</h4>
                                </div>
                            </div>
                            <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $submittedCount }}</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600 h-24 flex flex-col justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-full p-3">
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Belum Dikumpulkan</h4>
                                </div>
                            </div>
                            <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $notSubmittedCount }}</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600 h-24 flex flex-col justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 rounded-full p-3">
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Perlu Penilaian</h4>
                                </div>
                            </div>
                            <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $ungradedCount }}</p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar for Submissions -->
                    @if($totalStudents > 0)
                    <div class="mt-6">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tingkat Pengumpulan</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ round(($submittedCount / $totalStudents) * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                            <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ ($submittedCount / $totalStudents) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Recent Submissions -->
                @if($assignment->submissions->count() > 0)
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Pengumpulan Terbaru</h3>
                    <div class="space-y-4">
                        @foreach($assignment->submissions->take(5) as $submission)
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $submission->user->name ?? 'Siswa' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Dikumpulkan {{ $submission->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @if($submission->graded)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Dinilai: {{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}
                                    </span>
                                    @else
                                    <a href="{{ route('teacher.submissions.show', [$classroom->id, $assignment->id, $submission->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Nilai
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- View all submissions link -->
                    @if($assignment->submissions->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Lihat Semua Pengumpulan
                            <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('teacher.classrooms.partials.modals.edit-assignment', ['assignment' => $assignment])
@include('teacher.classrooms.partials.modals.delete-assignment', ['assignment' => $assignment])

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
</script>
@endsection