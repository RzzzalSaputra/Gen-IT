@extends('layouts.teacher')

@section('title', 'View Submission')

@section('content')
<div class="container py-3 sm:py-4">
    <div class="max-w-7xl mx-auto px-3 sm:px-0">
        <!-- Responsive Breadcrumb Navigation -->
        <div class="mb-3 sm:mb-4 flex flex-wrap items-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('teacher.classrooms.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Classrooms</a>
            <span class="mx-1 sm:mx-2">›</span>
            <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 truncate max-w-[100px] sm:max-w-xs">{{ $classroom->name }}</a>
            <span class="mx-1 sm:mx-2">›</span>
            <a href="{{ route('teacher.assignments.show', [$classroom->id, $assignment->id]) }}" class="hover:text-blue-600 dark:hover:text-blue-400 truncate max-w-[100px] sm:max-w-xs">{{ $assignment->title }}</a>
            <span class="mx-1 sm:mx-2">›</span>
            <span class="hidden sm:inline">
                <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="hover:text-blue-600 dark:hover:text-blue-400">Submissions</a>
                <span class="mx-1 sm:mx-2">›</span>
            </span>
            <span class="text-gray-700 dark:text-gray-300 truncate max-w-[150px] sm:max-w-xs">{{ $submission->user->name }}'s Submission</span>
        </div>

        <!-- Responsive Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Submission Information - Full width on mobile, 2/3 on desktop -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-4 sm:mb-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center gap-2">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white">Submission Details</h2>
                        <div class="flex flex-wrap items-center gap-2">
                            @if($isLate)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Late
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    On Time
                                </span>
                            @endif

                            @if($submission->graded)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    Graded: {{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    Not Graded
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        <!-- Submission Metadata - Responsive Grid -->
                        <div class="mb-4 sm:mb-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4 text-xs sm:text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Submitted by</p>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $submission->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Submission date</p>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ date('F j, Y, g:i a', strtotime($submission->submitted_at)) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Assignment due date</p>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ date('F j, Y, g:i a', strtotime($assignment->due_date)) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Submission status</p>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        @if($isLate)
                                            <span class="text-red-600 dark:text-red-400">
                                                Late ({{ $submissionDate->diffForHumans($dueDate, true) }} after deadline)
                                            </span>
                                        @else
                                            <span class="text-green-600 dark:text-green-400">
                                                On time ({{ $submissionDate->diffForHumans($dueDate, true) }} before deadline)
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 sm:my-6 border-gray-200 dark:border-gray-700">

                        <!-- Submission Content -->
                        <div class="mb-4 sm:mb-6">
                            <h3 class="text-base sm:text-lg font-medium text-gray-800 dark:text-white mb-3 sm:mb-4">
                                Student Answer
                            </h3>
                            
                            <!-- Text Content - Using content field -->
                            @if($submission->content)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                                    <div class="text-gray-700 dark:text-gray-300 prose max-w-none dark:prose-invert text-sm sm:text-base">
                                        {!! nl2br(e($submission->content)) !!}
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                                    <p class="text-gray-500 dark:text-gray-400 italic text-sm">No written response provided.</p>
                                </div>
                            @endif

                            <!-- Submitted File(s) - Responsive Layout -->
                            @if($submission->file)
                                <div class="mt-3 sm:mt-4">
                                    <h4 class="text-sm sm:text-md font-medium text-gray-800 dark:text-white mb-2">Attached File:</h4>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center p-3 sm:p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center mb-2 sm:mb-0 sm:mr-4">
                                            <div class="flex-shrink-0 mr-3">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ basename($submission->file) }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Uploaded on {{ date('M d, Y, h:i A', strtotime($submission->submitted_at)) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <a href="{{ route('student.classrooms.assignments.submissions.download', [$classroom->id, $assignment->id, $submission->id]) }}" class="w-full sm:w-auto flex justify-center items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Teacher Feedback Section (if already graded) -->
                        @if($submission->graded && $submission->feedback)
                            <hr class="my-4 sm:my-6 border-gray-200 dark:border-gray-700">
                            <div class="mb-4 sm:mb-6">
                                <h3 class="text-base sm:text-lg font-medium text-gray-800 dark:text-white mb-3 sm:mb-4">
                                    Teacher Feedback
                                </h3>
                                <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-3 sm:p-4">
                                    <div class="text-gray-700 dark:text-gray-300 prose max-w-none dark:prose-invert text-sm sm:text-base">
                                        {!! nl2br(e($submission->feedback)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Assignment Materials Section -->
                <div class="p-4 rounded-lg bg-gray-800/60">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-white">Assignment Materials</h2>
                    </div>
                    
                    <!-- Material Card with Responsive Layout -->
                    <div class="p-4 rounded-lg bg-gray-900/80">
            <!-- Grading Panel - Full width on mobile, 1/3 on desktop -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden lg:sticky lg:top-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-white">Grading</h2>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        <form action="{{ route('teacher.submissions.grade', [$classroom->id, $assignment->id, $submission->id]) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="grade" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Grade (out of {{ $assignment->max_points ?? 100 }})
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
                            
                            <div class="mb-4">
                                <label for="feedback" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Feedback to Student
                                </label>
                                <textarea 
                                    name="feedback" 
                                    id="feedback" 
                                    rows="4" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                >{{ $submission->feedback ?? '' }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Provide constructive feedback to help the student understand their grade.
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ $submission->graded ? 'Update Grade' : 'Submit Grade' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back to Submissions Button - Only shown on larger screens -->
                <div class="mt-6 text-center hidden lg:block">
                    <a href="{{ route('teacher.submissions.index', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to All Submissions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection