@extends('layouts.teacher')

@section('title', 'My Classrooms')

@section('content')
<div class="container py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">My Classrooms</h1>
        <a href="{{ route('teacher.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create New Classroom
        </a>
    </div>

    <!-- Removed the success message display since it's already in the layout -->

    <!-- Changed to 3 columns max instead of 4 and increased gap -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($classrooms as $classroom)
            <!-- Increased overall card size with larger padding -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                <div class="p-8">
                    <!-- Increased text size and spacing -->
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-3">{{ $classroom->name }}</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Code: {{ $classroom->code }}</p>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 text-base leading-relaxed">{{ Str::limit($classroom->description ?? 'No description', 120) }}</p>
                    
                    <!-- Added more spacing between elements -->
                    <div class="flex items-center justify-between mb-5 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            @if(method_exists($classroom, 'students'))
                                {{ $classroom->students()->count() }} students
                            @else
                                0 students
                            @endif
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            @if(method_exists($classroom, 'assignments'))
                                {{ $classroom->assignments()->whereNull('delete_at')->count() }} assignments
                            @else
                                0 assignments
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Increased footer padding -->
                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Created: {{ $classroom->create_at ? $classroom->create_at->format('M d, Y') : 'N/A' }}</span>
                        <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            Manage
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-8 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-12 w-12 text-blue-500 mr-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-xl font-medium mb-3">No classrooms found</p>
                            <p class="mb-5 text-base">You haven't created any classrooms yet. Get started by creating your first classroom!</p>
                            <a href="{{ route('teacher.classrooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create Your First Classroom
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection