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
                            
                            // Correct the submission variable - get from classroom_submission
                            $submission = $assignment->classroom_submission ?? $assignment->submission ?? null;
                            
                            if($submission) {
                                if($submission->graded) {
                                    $statusClass = "bg-green-900/30 text-green-300 border-green-500/30";
                                    $statusText = "Graded: {$submission->grade}/100";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                } else {
                                    $statusClass = "bg-blue-900/30 text-blue-300 border-blue-500/30";
                                    $statusText = "Submitted";
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />';
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
                        </h2>

                        @if($submission)
                            <!-- Existing Submission Display -->
                            <div class="mb-6 bg-gray-900/40 rounded-lg p-5 border border-gray-700/50">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-900/40 p-2 rounded-lg mr-3">
                                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-white">Submitted {{ $submission->created_at->format('M d, Y, h:i A') }}</h4>
                                            <p class="text-sm text-gray-400">{{ $submission->file ? basename($submission->file) : 'No file attached' }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($submission->file)
                                        <a href="{{ route('student.classrooms.assignments.submissions.download', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id, 'id' => $submission->id]) }}" class="bg-blue-700 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-colors duration-200 text-sm">
                                            Download
                                        </a>
                                    @endif
                                </div>
                                
                                @if($submission->content)
                                    <div class="bg-gray-900/60 rounded-lg p-4 mb-4 text-gray-300">
                                        {!! $submission->content !!}
                                    </div>
                                @endif
                                
                                @if($submission->graded)
                                    <div class="mt-6 border-t border-gray-700/50 pt-4">
                                        <h4 class="text-lg font-medium text-white mb-2 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Graded
                                        </h4>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-400">Score:</span>
                                            <span class="text-lg font-bold text-green-400">{{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}</span>
                                        </div>
                                        
                                        @if($submission->feedback)
                                            <div class="mt-4">
                                                <h5 class="text-gray-300 font-medium mb-2">Feedback:</h5>
                                                <div class="bg-gray-900/60 rounded-lg p-4 text-gray-300">
                                                    {!! $submission->feedback !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- No Submission Yet -->
                            @if(!$isOverdue)
                                <!-- Submission Form -->
                                <form action="{{ route('student.classrooms.submissions.store', ['classroom_id' => $classroom->id, 'assignment_id' => $assignment->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Submission Text (Optional)</label>
                                        <textarea id="content" name="content" rows="5" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-3 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" placeholder="Write your answer or comments here..."></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="file" class="block text-sm font-medium text-gray-300 mb-2">Attachment (Optional)</label>
                                        <div class="relative border-2 border-dashed border-gray-600 rounded-lg px-6 py-8 text-center hover:border-gray-500 transition-colors duration-200">
                                            <input id="file" name="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-400">
                                                    <span class="font-medium text-blue-400">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    PDF, DOC, DOCX, ZIP, etc. (Max 10MB)
                                                </p>
                                            </div>
                                            <div id="file-info" class="hidden mt-4 text-left">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span id="file-name" class="text-sm text-gray-300"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            Submit Assignment
                                        </button>
                                    </div>
                                </form>
                            @else
                                <!-- Assignment is overdue message -->
                                <div class="bg-red-900/20 backdrop-blur-sm border border-red-500/30 rounded-lg p-4 text-center">
                                    <svg class="w-12 h-12 text-red-500/70 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-red-300 mb-1">Assignment Overdue</h3>
                                    <p class="text-gray-400">This assignment was due on {{ $dueDate->format('M d, Y, h:i A') }}.</p>
                                    <p class="text-gray-400 mt-2">Contact your instructor if you need to submit late work.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileInfo = document.getElementById('file-info');
                    const fileName = document.getElementById('file-name');
                    
                    if (this.files.length > 0) {
                        fileName.textContent = this.files[0].name;
                        fileInfo.classList.remove('hidden');
                    } else {
                        fileInfo.classList.add('hidden');
                    }
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