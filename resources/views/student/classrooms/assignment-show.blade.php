<x-app-layout>
    <!-- SweetAlert CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-0">{{ $assignment->title }}</h1>
                        <a href="{{ route('student.classrooms.show', $classroom->id) }}#assignments" class="inline-flex items-center px-4 py-2 bg-gray-700/70 hover:bg-gray-600/70 backdrop-blur-sm text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke kelas
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <!-- Assignment Status Badge -->
                        @php
                            // Set Carbon locale to Indonesian
                            \Carbon\Carbon::setLocale('id');
                            
                            $now = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $now->isAfter($dueDate);
                            
                            // Create Indonesian due time text
                            $dueTimeText = "" . $dueDate->diffForHumans();
                            
                            // Ensure we're getting the correct submission - fixed retrieval logic
                            if (isset($assignment->classroom_submission)) {
                                $submission = $assignment->classroom_submission;
                            } elseif (isset($assignment->submission)) {
                                $submission = $assignment->submission;
                            } elseif (isset($assignment->submissions) && $assignment->submissions->count() > 0) {
                                // If there's a submissions collection, get the authenticated user's submission
                                $submission = $assignment->submissions->where('user_id', auth()->id())->first();
                            } else {
                                $submission = null;
                            }
                            
                            if($submission) {
                                if($submission->graded) {
                                    $statusClass = "bg-green-900/30 text-green-300 border-green-500/30";
                                    $statusText = "Dinilai";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                } else {
                                    $statusClass = "bg-blue-900/30 text-blue-300 border-blue-500/30";
                                    $statusText = "Telah Dikumpulkan";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                                }
                            } elseif($isOverdue) {
                                $statusClass = "bg-red-900/30 text-red-300 border-red-500/30";
                                $statusText = "Terlambat";
                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            } else {
                                $statusClass = "bg-yellow-900/30 text-yellow-300 border-yellow-500/30";
                                $statusText = "Tenggat " . $dueDate->diffForHumans();
                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            }
                        @endphp
                        
                        <div class="inline-flex items-center px-3 py-1 {{ $statusClass }} backdrop-blur-sm rounded-lg text-sm border">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $statusIcon !!}
                            </svg>
                            {{ $dueTimeText }}
                        </div>
                        
                        <div class="inline-flex items-center px-3 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-sm text-gray-300 border border-gray-600/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tenggat: {{ $dueDate->format('M d, Y, H:i') }}
                        </div>
                        
                        @if($assignment->max_points)
                        <div class="inline-flex items-center px-3 py-1 bg-purple-900/30 backdrop-blur-sm rounded-lg text-sm text-purple-300 border border-purple-500/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            {{ $assignment->max_points }} Points
                        </div>
                        @endif
                    </div>

                    <!-- Main Content - Description -->
                    <div class="bg-gray-900/30 backdrop-blur-sm rounded-xl p-6 mb-8">
                        <div class="prose prose-lg max-w-none text-gray-200 leading-relaxed">
                            {!! $assignment->description !!}
                        </div>
                    </div>

                    <!-- Attachment Section (if any) -->
                    @if($assignment->file)
                    <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 mb-8 border border-gray-700/50">
                        <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            File Lampiran
                        </h2>
                        <div class="flex items-center p-4 bg-gray-900/50 rounded-lg hover:bg-gray-900/70 transition-colors">
                            <div class="bg-purple-900/40 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white font-medium">{{ basename($assignment->file) }}</h4>
                                <p class="text-sm text-gray-400">Instruksi tugas atau sumber</p>
                            </div>
                            <a href="{{ route('student.classrooms.assignments.download', ['classroom_id' => $classroom->id, 'id' => $assignment->id]) }}" class="bg-purple-700 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium">
                                Unduh
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Submission Section -->
                    <div class="bg-gray-800/30 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                        <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Tugas yang kamu kumpulkan
                        </h2>

                        @if($submission)
                            <!-- Shows existing submission details -->
                            <div id="submission-display" class="mb-6 bg-gray-900/40 rounded-lg p-5 border border-gray-700/50">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4">
                                    <div class="mb-3 sm:mb-0">
                                        <p class="text-gray-300">Dikumpul pada : {{ Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y, h:i A') }}</p>
                                        @if($submission->graded)
                                            <p class="text-green-400 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Grade: {{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Tugas yang sudah dinilai tidak dapat diedit</p>
                                        @else
                                            <p class="text-yellow-400 mt-1">Belum Dinilai</p>
                                        @endif
                                    </div>
                                    
                                    @if(!$isOverdue && !$submission->graded)
                                    <button type="button" id="edit-submission-btn" class="px-3 py-2 w-full sm:w-auto bg-blue-600/70 hover:bg-blue-500/70 text-white text-sm rounded-lg transition-colors duration-200">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Tugas
                                        </div>
                                    </button>
                                    @elseif($isOverdue && \Carbon\Carbon::parse($submission->submitted_at)->isAfter($dueDate))
                                    <div class="px-3 py-2 w-full sm:w-auto bg-red-900/30 text-red-300 text-sm rounded-lg border border-red-700/50 text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Terlambat
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Teacher Feedback (if graded) - no changes needed here as it's already responsive -->
                                @if($submission->graded && $submission->feedback)
                                <div class="mb-6 bg-green-900/30 border border-green-700/50 rounded-lg p-5">
                                    <h4 class="text-white text-base font-medium mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Feedback dari Guru:
                                    </h4>
                                    <div class="prose prose-sm max-w-none bg-gray-800/50 rounded-lg p-4 text-gray-200">
                                        {!! nl2br(e($submission->feedback)) !!}
                                    </div>
                                </div>
                                @endif
                                <!-- Submission Content/Notes Display -->
                                @if($submission->content)
                                    <div class="bg-gray-900/30 rounded-lg p-4 mb-3">
                                        <h4 class="text-white text-sm font-medium mb-2">Jawaban</h4>
                                        <div class="prose prose-sm max-w-none bg-gray-800/50 rounded-lg p-4 text-gray-200">
                                            {!! nl2br(e($submission->content)) !!}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($submission->file)
                                    <div class="bg-gray-900/30 rounded-lg p-4">
                                        <h4 class="text-white text-sm font-medium mb-2">File Yang Dikirim:</h4>
                                        <div class="flex flex-col sm:flex-row sm:items-center">
                                            <div class="flex items-center mb-2 sm:mb-0">
                                                <svg class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                </svg>
                                                <span class="text-gray-300 text-sm truncate">{{ basename($submission->file) }}</span>
                                            </div>
                                            <div class="sm:ml-auto flex flex-wrap gap-2 mt-2 sm:mt-0">
                                                <a href="{{ route('student.classrooms.assignments.submissions.download', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id, 'id' => $submission->id]) }}" 
                                                   class="text-blue-400 bg-blue-900/30 hover:bg-blue-800/40 px-3 py-2 rounded-lg flex items-center justify-center w-full sm:w-auto">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m3-4v12" />
                                                    </svg>
                                                    Download
                                                </a>
                                                @if(!$isOverdue && !$submission->graded)
                                                <button type="button" class="text-red-400 bg-red-900/30 hover:bg-red-800/40 px-3 py-2 rounded-lg flex items-center justify-center w-full sm:w-auto remove-file-btn">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Edit form is hidden initially -->
                            @if(!$isOverdue && !$submission->graded)
                            <div id="edit-submission-form" class="hidden">
                                <form method="POST" action="{{ route('student.classrooms.assignments.submissions.store', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id]) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <!-- Hidden field to track file removal -->
                                    <input type="hidden" name="remove_file" id="remove_file" value="0">
                                    
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Jawaban</label>
                                        <textarea id="content" name="content" rows="4" class="w-full bg-gray-800/50 border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-blue-500 focus:border-blue-500">{{ $submission->content }}</textarea>
                                    </div>
                                    
                                    <!-- File Upload Section in the form -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">
                                            Submission File
                                        </label>
                                        
                                        @if($submission->file)
                                            <div class="flex items-center p-4 bg-gray-900/40 rounded-lg border border-gray-700/50 mb-3">
                                                <div class="flex-shrink-0 mr-3">
                                                    <svg class="w-8 h-8 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-200 truncate">
                                                        Current file: {{ basename($submission->file) }}
                                                    </p>
                                                </div>
                                                <button type="button" id="remove-current-file" class="text-red-400 hover:text-red-300 text-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div id="new-file-preview" class="hidden flex items-center p-4 bg-indigo-900/30 rounded-lg border border-indigo-700/50 mb-3 transform transition-all duration-300 ease-in-out">
                                                <div class="flex-shrink-0 mr-3">
                                                    <svg class="w-8 h-8 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-indigo-200 truncate">
                                                        New file selected: <span id="new-file-name"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="relative">
                                            <label for="file" class="flex justify-center items-center px-4 py-3 bg-gray-800/50 hover:bg-gray-700/50 text-gray-300 rounded-lg border border-dashed border-gray-600 cursor-pointer transition duration-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <span id="upload-label">{{ $submission->file ? 'Ganti File' : 'Choose file' }}</span>
                                            </label>
                                            <input id="file" name="file" type="file" class="hidden">
                                        </div>
                                        
                                        @if($submission->file)
                                            <p class="mt-2 text-xs text-gray-400 italic">
                                                Biarkan kosong apabila tidak ingin menganti file
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-4">
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m3-4v12" />
                                            </svg>
                                            Update Submission
                                        </button>
                                        <button type="button" id="cancel-edit-btn" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        @else
                            <!-- First-time submission form -->
                            @if(!$isOverdue)
                                <!-- Submission Form -->
                                <form method="POST" action="{{ route('student.classrooms.assignments.submissions.store', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id]) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Masukan Jawaban</label>
                                        <textarea id="content" name="content" rows="4" class="w-full bg-gray-800/50 border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="file" class="block text-sm font-medium text-gray-300 mb-1">Upload File (Optional)</label>
                                        <div class="flex items-center justify-center w-full">
                                            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-700/30 hover:bg-gray-700/50">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-400">Klik untuk mengunggah atau seret dan lepas</p>
                                                </div>
                                                <input id="file" name="file" type="file" class="hidden" />
                                            </label>
                                        </div>
                                        <div id="file-info" class="mt-2 hidden">
                                            <p class="text-sm text-gray-300">Selected file: <span id="file-name" class="font-medium"></span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Kirim Tugas
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-red-900/20 text-red-300 p-4 rounded-lg border border-red-800/30">
                                    <p class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Batas waktu untuk tugas ini telah lewat dan pengumpulan tugas tidak lagi diterima.
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File upload preview and form toggling
        document.addEventListener('DOMContentLoaded', function() {
            // File upload preview
            const fileInput = document.getElementById('file');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileInfo = document.getElementById('file-info');
                    const currentFileInfo = document.getElementById('current-file-info');
                    const newFileInfo = document.getElementById('new-file-info');
                    const fileName = document.getElementById('file-name');
                    
                    if (this.files.length > 0) {
                        if (fileName) {
                            fileName.textContent = this.files[0].name;
                        }
                        fileInfo.classList.remove('hidden');
                        
                        // When replacing an existing file
                        if (currentFileInfo) {
                            currentFileInfo.classList.add('hidden');
                            newFileInfo.classList.remove('hidden');
                        }
                    } else {
                        // If file selection is cancelled
                        if (currentFileInfo && currentFileInfo.classList.contains('hidden')) {
                            currentFileInfo.classList.remove('hidden');
                            newFileInfo.classList.add('hidden');
                        } else {
                            fileInfo.classList.add('hidden');
                        }
                    }
                });
            }
            
            // More resilient event binding
            const editBtn = document.getElementById('edit-submission-btn');
            const submissionDisplay = document.getElementById('submission-display');
            const editSubmissionForm = document.getElementById('edit-submission-form');
            
            if (editBtn) {
                editBtn.addEventListener('click', function() {
                    if (submissionDisplay) submissionDisplay.classList.add('hidden');
                    if (editSubmissionForm) editSubmissionForm.classList.remove('hidden');
                });
            }
            
            const cancelBtn = document.getElementById('cancel-edit-btn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (editSubmissionForm) editSubmissionForm.classList.add('hidden');
                    if (submissionDisplay) submissionDisplay.classList.remove('hidden');
                    // Reset the remove_file value when canceling
                    if (document.getElementById('remove_file')) {
                        document.getElementById('remove_file').value = "0";
                    }
                });
            }
            
            // File upload preview in the script section
            // Using the already defined fileInput variable
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const newFilePreview = document.getElementById('new-file-preview');
                    const newFileName = document.getElementById('new-file-name');
                    const uploadLabel = document.getElementById('upload-label');
                    
                    if (this.files.length > 0) {
                        // Show the new file preview with transition
                        if (newFilePreview) {
                            newFileName.textContent = this.files[0].name;
                            newFilePreview.classList.remove('hidden');
                            // Add a small delay for the transition to take effect after removing 'hidden'
                            setTimeout(() => {
                                newFilePreview.classList.add('opacity-100');
                                newFilePreview.classList.remove('opacity-0', '-translate-y-2');
                            }, 10);
                        }
                        
                        // Update the upload button text
                        if (uploadLabel) {
                            uploadLabel.textContent = 'Change file';
                        }
                        
                        // Reset remove_file flag if new file is uploaded
                        if (document.getElementById('remove_file')) {
                            document.getElementById('remove_file').value = "0";
                        }
                    } else {
                        // If file selection is cancelled
                        if (newFilePreview) {
                            newFilePreview.classList.add('opacity-0', '-translate-y-2');
                            setTimeout(() => {
                                newFilePreview.classList.add('hidden');
                            }, 300); // Match duration with CSS transition
                        }
                        
                        // Reset the upload button text
                        if (uploadLabel) {
                            uploadLabel.textContent = 'Ganti File';
                        }
                    }
                });
            }
            
            // Handle file removal - in edit form
            const removeCurrentFileBtn = document.getElementById('remove-current-file');
            if (removeCurrentFileBtn) {
                removeCurrentFileBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Hapus File?',
                        text: 'Apakah anda yakin ingin menghapus file ini??',
                        icon: 'warning',
                        showCancelButton: true,
                        background: '#1f2937',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Set the hidden field to indicate file removal
                            document.getElementById('remove_file').value = "1";
                            
                            // Hide the current file info
                            const currentFileContainer = this.closest('.flex.items-center');
                            if (currentFileContainer) {
                                currentFileContainer.style.display = 'none';
                            }
                            
                            Swal.fire(
                                'File Ditandai untuk Dihapus',
                                'File akan dihapus ketika Kamu Menyimpan perubahan.',
                                'success'
                            );
                        }
                    });
                });
            }
            
            // Handle remove button in submission display view
            const removeFileBtns = document.querySelectorAll('.remove-file-btn');
            removeFileBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Hapus File??',
                        text: 'Ini akan beralih ke mode edit. Anda yakin?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        background: '#1f2937',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Switch to edit mode
                            if (editBtn) {
                                editBtn.click();
                                
                                // Set the hidden field to indicate file removal
                                document.getElementById('remove_file').value = "1";
                                
                                // Hide the current file info
                                const currentFileContainer = document.querySelector('.flex.items-center.p-4.bg-gray-900\\/40');
                                if (currentFileContainer) {
                                    currentFileContainer.style.display = 'none';
                                }
                                
                                Swal.fire({
                                    title: 'Edit Mode',
                                    text: 'Anda sekarang dapat mengirimkan perubahan Anda untuk menghapus file.',
                                    icon: 'info',
                                    background: '#1f2937',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        }
                    });
                });
            });
            
            // Show success messages with SweetAlert if present in the session
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    background: '#1f2937',
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    background: '#1f2937',
                    confirmButtonColor: '#3085d6'
                });
            @endif
        });
    </script>
    <style>
        /* Improved content readability for assignment description */
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
        
        .prose ul, .prose ol {
            margin-left: 1.25rem;
        }

        /* Transitions for file preview */
        #new-file-preview {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(-0.5rem);
        }
        
        #new-file-preview.opacity-100 {
            opacity: 1;
            transform: translateY(0);
        }

        /* SweetAlert Dark Theme Overrides */
        .swal2-popup {
            border-radius: 0.75rem !important;
            border: 1px solid rgba(75, 85, 99, 0.3) !important;
        }
        
        .swal2-title {
            color: #f3f4f6 !important;
        }
        
        .swal2-html-container {
            color: #d1d5db !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
        }
        
        .swal2-icon {
            border-color: rgba(96, 165, 250, 0.3) !important;
        }
        
        .swal2-icon.swal2-warning {
            border-color: rgba(251, 191, 36, 0.3) !important;
            color: #fbbf24 !important;
        }
        
        .swal2-icon.swal2-error {
            border-color: rgba(239, 68, 68, 0.3) !important;
            color: #ef4444 !important;
        }
        
        .swal2-icon.swal2-success {
            border-color: rgba(34, 197, 94, 0.3) !important;
            color: #22c55e !important;
        }
        
        .swal2-icon.swal2-info {
            border-color: rgba(14, 165, 233, 0.3) !important;
            color: #0ea5e9 !important;
        }
    </style>
</x-app-layout>