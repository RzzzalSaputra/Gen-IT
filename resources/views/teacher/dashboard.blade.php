@extends('layouts.teacher')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-6 transform hover:-translate-y-1 transition-transform">
        <div class="flex items-center">
            <div class="p-3 rounded-none bg-blue-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Total Classrooms</h2>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $classroomCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-6 transform hover:-translate-y-1 transition-transform">
        <div class="flex items-center">
            <div class="p-3 rounded-none bg-green-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Total Students</h2>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $studentCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-6 transform hover:-translate-y-1 transition-transform">
        <div class="flex items-center">
            <div class="p-3 rounded-none bg-yellow-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Pending Submissions</h2>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $pendingSubmissions }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- My Classrooms -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none lg:col-span-2">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b-4 border-gray-900 dark:border-gray-600">
            <h3 class="text-lg font-black text-gray-900 dark:text-white">My Classrooms</h3>
            <a href="{{ route('teacher.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border-3 border-gray-900 rounded-none font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.8)] hover:-translate-y-1 transition-transform">
                Create New
            </a>
        </div>
        <div class="p-6">
            @if(count($classrooms) === 0)
                <div class="text-center py-4 border-4 border-gray-900 dark:border-gray-600 p-4">
                    <p class="text-gray-700 dark:text-gray-300 font-bold">You haven't created any classrooms yet.</p>
                    <a href="{{ route('teacher.classrooms.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border-3 border-gray-900 rounded-none font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.8)] hover:-translate-y-1 transition-transform">
                        Create Your First Classroom
                    </a>
                </div>
            @else
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2 scrollbar-hide">
                    @foreach($classrooms as $classroom)
                        <div class="border-4 border-gray-900 dark:border-gray-600 rounded-none p-4 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] transform hover:-translate-y-1 transition-transform">
                            <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-black text-gray-900 dark:text-white">{{ $classroom->name }}</h4>
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">{{ $classroom->members()->where('role', 'student')->count() }} students</p>
                                </div>
                                @if($classroom->pending_submissions_count > 0)
                                    <span class="px-2 py-1 inline-flex items-center justify-center text-xs leading-5 font-black rounded-none bg-yellow-300 text-black border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        {{ $classroom->pending_submissions_count }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex items-center justify-center text-xs leading-5 font-black rounded-none bg-green-300 text-black border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Recent Submissions -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none">
        <div class="px-4 py-5 sm:px-6 border-b-4 border-gray-900 dark:border-gray-600">
            <h3 class="text-lg font-black text-gray-900 dark:text-white">Recent Submissions</h3>
        </div>
        <div class="p-6">
            @if(count($recentSubmissions) === 0)
                <p class="text-gray-700 dark:text-gray-300 font-bold text-center py-4 border-4 border-gray-900 dark:border-gray-600 p-4">No recent submissions.</p>
            @else
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2 scrollbar-hide">
                    @foreach($recentSubmissions as $submission)
                        <div class="border-4 {{ $submission->graded ? 'border-green-500' : 'border-yellow-500' }} pl-4 bg-white dark:bg-gray-800 p-3 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] transform hover:-translate-y-1 transition-transform">
                            <p class="font-black text-gray-900 dark:text-white">
                                {{ $submission->user ? $submission->user->name : 'Unknown Student' }}
                            </p>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $submission->assignment ? $submission->assignment->title : 'Unknown Assignment' }}</p>
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Kelas: {{ $submission->assignment && $submission->assignment->classroom ? $submission->assignment->classroom->name : 'Unknown Classroom' }}
                            </p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-none text-xs font-black {{ $submission->graded ? 'bg-green-300 text-black' : 'bg-yellow-300 text-black' }} border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]">
                                    {{ $submission->graded ? 'Graded' : 'Pending' }}
                                </span>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                    {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection