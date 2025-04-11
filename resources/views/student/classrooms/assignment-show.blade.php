<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-0">{{ $assignment->title }}</h1>
                        <a href="{{ route('student.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-700/70 hover:bg-gray-600/70 backdrop-blur-sm text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Classroom
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <!-- Assignment Status Badge -->
                        @php
                            $now = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $now->isAfter($dueDate);
                            
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
                                    $statusText = "Graded";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                } else {
                                    $statusClass = "bg-blue-900/30 text-blue-300 border-blue-500/30";
                                    $statusText = "Submitted";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                                }
                            } elseif($isOverdue) {
                                $statusClass = "bg-red-900/30 text-red-300 border-red-500/30";
                                $statusText = "Overdue";
                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            } else {
                                $statusClass = "bg-yellow-900/30 text-yellow-300 border-yellow-500/30";
                                $statusText = "Due " . $dueDate->diffForHumans();
                                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
                            }
                        @endphp
                        
                        <div class="inline-flex items-center px-3 py-1 {{ $statusClass }} backdrop-blur-sm rounded-lg text-sm border">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $statusIcon !!}
                            </svg>
                            {{ $statusText }}
                        </div>
                        
                        <div class="inline-flex items-center px-3 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-sm text-gray-300 border border-gray-600/30">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Due: {{ $dueDate->format('M d, Y, h:i A') }}
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
                            Assignment Materials
                        </h2>
                        <div class="flex items-center p-4 bg-gray-900/50 rounded-lg hover:bg-gray-900/70 transition-colors">
                            <div class="bg-purple-900/40 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white font-medium">{{ basename($assignment->file) }}</h4>
                                <p class="text-sm text-gray-400">Assignment instructions or resources</p>
                            </div>
                            <a href="{{ route('student.classrooms.assignments.download', ['classroom_id' => $classroom->id, 'id' => $assignment->id]) }}" class="bg-purple-700 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium">
                                Download
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
                            Your Submission
                        </h2>e

                        @if($submission)
                            <!-- Shows existing submission details -->
                            <div id="submission-display" class="mb-6 bg-gray-900/40 rounded-lg p-5 border border-gray-700/50">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="text-gray-300">Submitted: {{ Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y, h:i A') }}</p>
                                        @if($submission->graded)
                                            <p class="text-green-400 mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Grade: {{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Graded submissions cannot be edited</p>
                                        @else
                                            <p class="text-yellow-400 mt-1">Not graded yet</p>
                                        @endif
                                    </div>
                                    
                                    @if(!$isOverdue && !$submission->graded)
                                    <button type="button" id="edit-submission-btn" class="px-3 py-1 bg-blue-600/70 hover:bg-blue-500/70 text-white text-sm rounded-lg transition-colors duration-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Submission
                                        </div>
                                    </button>
                                    @elseif($isOverdue)
                                    <div class="px-3 py-1 bg-red-900/30 text-red-300 text-sm rounded-lg border border-red-700/50">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Overdue
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($submission->content)
                                    <div class="bg-gray-900/30 rounded-lg p-4 mb-4">
                                        <h4 class="text-white text-sm font-medium mb-2">Your Notes:</h4>
                                        <p class="text-gray-300">{{ $submission->content }}</p>
                                    </div>
                                @endif
                                
                                @if($submission->file)
                                    <div class="bg-gray-900/30 rounded-lg p-4">
                                        <h4 class="text-white text-sm font-medium mb-2">Your File:</h4>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-gray-300">{{ basename($submission->file) }}</span>
                                            <a href="{{ route('student.classrooms.assignments.submissions.download', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id, 'id' => $submission->id]) }}" class="ml-auto text-blue-400 hover:text-blue-300">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Edit form is hidden initially -->
                            @if(!$isOverdue && !$submission->graded)
                            <div id="edit-submission-form" class="hidden">
                                <form method="POST" action="{{ route('student.classrooms.assignments.submissions.store', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id]) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Submission Notes</label>
                                        <textarea id="content" name="content" rows="4" class="w-full bg-gray-800/50 border border-gray-700 rounded-lg p-3 text-gray-200 focus:ring-blue-500 focus:border-blue-500">{{ $submission->content }}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="file" class="block text-sm font-medium text-gray-300 mb-1">Upload File (Optional)</label>
                                        <div class="flex items-center justify-center w-full">
                                            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-700/30 hover:bg-gray-700/50">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-400">
                                                        @if($submission->file)
                                                            Replace current file or leave empty to keep existing
                                                        @else
                                                            Click to upload or drag and drop
                                                        @endif
                                                    </p>
                                                </div>
                                                <input id="file" name="file" type="file" class="hidden" />
                                            </label>
                                        </div>
                                        <div id="file-info" class="mt-2 {{ $submission->file ? '' : 'hidden' }}">
                                            <p class="text-sm text-gray-300">
                                                @if($submission->file)
                                                    Current file: <span class="font-medium">{{ basename($submission->file) }}</span>
                                                @else
                                                    Selected file: <span id="file-name" class="font-medium"></span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 pt-4">
                                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                            </svg>
                                            Update Submission
                                        </button>
                                        <button type="button" id="cancel-edit-btn" class="inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg">
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
                                        <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Submission Notes</label>
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
                                                    <p class="mb-2 text-sm text-gray-400">Click to upload or drag and drop</p>
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
                                            Submit Assignment
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-red-900/20 text-red-300 p-4 rounded-lg border border-red-800/30">
                                    <p class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        The deadline for this assignment has passed and submissions are no longer accepted.
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
                    const fileName = document.getElementById('file-name');
                    
                    if (this.files.length > 0) {
                        if (fileName) {
                            fileName.textContent = this.files[0].name;
                        }
                        fileInfo.classList.remove('hidden');
                    } else {
                        fileInfo.classList.add('hidden');
                    }
                });
            }
            
            // Toggle between view and edit submission
            const editBtn = document.getElementById('edit-submission-btn');
            const cancelBtn = document.getElementById('cancel-edit-btn');
            const submissionDisplay = document.getElementById('submission-display');
            const editSubmissionForm = document.getElementById('edit-submission-form');
            
            if (editBtn && cancelBtn && submissionDisplay && editSubmissionForm) {
                editBtn.addEventListener('click', function() {
                    submissionDisplay.classList.add('hidden');
                    editSubmissionForm.classList.remove('hidden');
                });
                
                cancelBtn.addEventListener('click', function() {
                    editSubmissionForm.classList.add('hidden');
                    submissionDisplay.classList.remove('hidden');
                });
            }
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
    </style>
</x-app-layout>