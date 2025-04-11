@extends('layouts.teacher')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-medium text-gray-500 dark:text-gray-400">Total Classrooms</h2>
                <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $classroomCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-medium text-gray-500 dark:text-gray-400">Total Students</h2>
                <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $studentCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                <svg class="h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-medium text-gray-500 dark:text-gray-400">Pending Submissions</h2>
                <p class="text-2xl font-semibold text-gray-700 dark:text-white">{{ $pendingSubmissions }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- My Classrooms -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg lg:col-span-2">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Classrooms</h3>
            <a href="{{ route('teacher.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                Create New
            </a>
        </div>
        <div class="p-6">
            @if(count($classrooms) === 0)
                <div class="text-center py-4">
                    <p class="text-gray-500 dark:text-gray-400">You haven't created any classrooms yet.</p>
                    <a href="{{ route('teacher.classrooms.create') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        Create Your First Classroom
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($classrooms as $classroom)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $classroom->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $classroom->students->count() }} students</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classroom->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($classroom->status ?? 'active') }}
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Recent Submissions -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Submissions</h3>
        </div>
        <div class="p-6">
            @if(count($recentSubmissions) === 0)
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent submissions.</p>
            @else
                <div class="space-y-4">
                    @foreach($recentSubmissions as $submission)
                        <div class="border-l-4 {{ $submission->graded ? 'border-green-500' : 'border-yellow-500' }} pl-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $submission->student->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->assignment->title }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->graded ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $submission->graded ? 'Graded' : 'Pending' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
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