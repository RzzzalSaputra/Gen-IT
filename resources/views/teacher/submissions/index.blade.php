@extends('layouts.teacher')

@section('title', 'Submissions for ' . $assignment->title)

@section('content')
<div class="container py-4">
    <div class="max-w-7xl mx-auto">
        <!-- Assignment Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $assignment->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Due: {{ date('M d, Y, h:i A', strtotime($assignment->due_date)) }}
                        @php
                            $now = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $now->isAfter($dueDate);
                            
                            if($isOverdue) {
                                echo '<span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium rounded-full">Overdue</span>';
                            } else {
                                $diff = $now->diffInDays($dueDate, false);
                                if($diff <= 3 && $diff >= 0) {
                                    echo '<span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium rounded-full">Due soon</span>';
                                }
                            }
                        @endphp
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <a href="{{ route('teacher.assignments.show', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Assignment
                    </a>
                    <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Classroom
                    </a>
                </div>
            </div>

            <!-- Submission Statistics -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Submission Overview</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @php
                        $totalStudents = $classroom->members()->where('role', 'student')->count();
                        $submittedCount = $submissions->count();
                        $notSubmittedCount = $totalStudents - $submittedCount;
                        $gradedCount = $submissions->where('graded', true)->count();
                        $ungradedCount = $submittedCount - $gradedCount;
                        
                        $onTimeCount = $submissions->filter(function($submission) use ($assignment) {
                            return \Carbon\Carbon::parse($submission->created_at)->lessThanOrEqualTo(\Carbon\Carbon::parse($assignment->due_date));
                        })->count();
                        
                        $lateCount = $submittedCount - $onTimeCount;
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Students</h4>
                                <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $totalStudents }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Submitted</h4>
                                <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $submittedCount }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-full p-3">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Not Submitted</h4>
                                <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $notSubmittedCount }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 rounded-full p-3">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Need Grading</h4>
                                <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $ungradedCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar for Submissions -->
                @if($totalStudents > 0)
                <div class="mt-6">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Submission Rate</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ round(($submittedCount / $totalStudents) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" style="width: {{ $totalStudents > 0 ? ($submittedCount / $totalStudents) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Submitted Assignments -->
        @if($submissions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Submitted Assignments</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submission Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($submissions as $submission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $submission->user->name ?? 'Student' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $submission->user->email ?? 'No email' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ date('M d, Y, h:i A', strtotime($submission->created_at)) }}</div>
                                @php
                                    $submissionDate = \Carbon\Carbon::parse($submission->created_at);
                                    $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                    $isLate = $submissionDate->isAfter($dueDate);
                                @endphp
                                @if($isLate)
                                    <div class="text-xs text-red-500">Late ({{ $submissionDate->diffForHumans($dueDate, true) }} after deadline)</div>
                                @else
                                    <div class="text-xs text-green-500">On time ({{ $submissionDate->diffForHumans($dueDate, true) }} before deadline)</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Graded
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Needs Grading
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded)
                                    <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}</span>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Not graded</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('teacher.submissions.show', [$classroom->id, $assignment->id, $submission->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-4">View</a>
                                
                                @if(!$submission->graded)
                                    <a href="{{ route('teacher.submissions.grade', [$classroom->id, $assignment->id, $submission->id]) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Grade</a>
                                @else
                                    <a href="{{ route('teacher.submissions.grade', [$classroom->id, $assignment->id, $submission->id]) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">Update Grade</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Students who haven't submitted -->
        @if($studentsWithoutSubmissions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Students Without Submissions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($studentsWithoutSubmissions as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $member->user->name ?? 'Student' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->user->email ?? 'No email' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Not Submitted
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" onclick="sendReminder('{{ $member->user->email }}', '{{ $member->user->name }}')" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    Send Reminder
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Send Reminder Notification (could be implemented later) -->
<script>
    function sendReminder(email, name) {
        // This could send an AJAX request to send an email reminder
        // For now, just show an alert
        alert(`A reminder would be sent to ${name} (${email})`);
    }
</script>
@endsection