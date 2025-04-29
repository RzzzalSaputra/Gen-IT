@extends('layouts.teacher')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-4 sm:p-6 transform hover:-translate-y-1 transition-transform">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-none bg-blue-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Total Kelas</h2>
                <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">{{ $classroomCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-4 sm:p-6 transform hover:-translate-y-1 transition-transform">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-none bg-green-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Total Siswa</h2>
                <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">{{ $studentCount }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none p-4 sm:p-6 transform hover:-translate-y-1 transition-transform sm:col-span-2 md:col-span-1">
        <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-none bg-yellow-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600">
                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="font-bold text-gray-900 dark:text-gray-200">Pending Submissions</h2>
                <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">{{ $pendingSubmissions }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- My Classrooms -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none lg:col-span-2">
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-4 border-gray-900 dark:border-gray-600">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-3 sm:mb-0">Kelas Saya</h3>
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border-3 border-gray-900 rounded-none font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.8)] hover:-translate-y-1 transition-transform w-full sm:w-auto justify-center sm:justify-start">
            Buat Kelas Baru
            </button>
        </div>
        <div class="p-4 sm:p-6">
            @if(count($classrooms) === 0)
                <div class="text-center py-4 border-4 border-gray-900 dark:border-gray-600 p-4">
                    <p class="text-gray-700 dark:text-gray-300 font-bold">You haven't created any classrooms yet.</p>
                    <button onclick="openCreateModal()" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border-3 border-gray-900 rounded-none font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.8)] hover:-translate-y-1 transition-transform">
                        Create Your First Classroom
                    </button>
                </div>
            @else
                <div class="space-y-3 sm:space-y-4 max-h-72 sm:max-h-80 overflow-y-auto pr-1 sm:pr-2 scrollbar-hide">
                    @foreach($classrooms as $classroom)
                        <div class="border-4 border-gray-900 dark:border-gray-600 rounded-none p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] transform hover:-translate-y-1 transition-transform">
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
        <div class="p-4 sm:p-6">
            @if(count($recentSubmissions) === 0)
                <p class="text-gray-700 dark:text-gray-300 font-bold text-center py-4 border-4 border-gray-900 dark:border-gray-600 p-4">No recent submissions.</p>
            @else
                <div class="space-y-3 sm:space-y-4 max-h-72 sm:max-h-80 overflow-y-auto pr-1 sm:pr-2 scrollbar-hide">
                    @foreach($recentSubmissions as $submission)
                        <a href="{{ route('teacher.submissions.show', [
                            'classroom_id' => $submission->assignment->classroom->id,
                            'assignment_id' => $submission->assignment->id,
                            'id' => $submission->id
                        ]) }}" class="block cursor-pointer hover:no-underline">
                            <div class="border-4 {{ $submission->graded ? 'border-green-500' : 'border-yellow-500' }} pl-3 sm:pl-4 bg-white dark:bg-gray-800 p-3 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] transform hover:-translate-y-1 transition-transform">
                                <p class="font-black text-gray-900 dark:text-white">
                                    {{ $submission->user ? $submission->user->name : 'Unknown Student' }}
                                </p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $submission->assignment ? $submission->assignment->title : 'Unknown Assignment' }}</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                    Kelas: {{ $submission->assignment && $submission->assignment->classroom ? $submission->assignment->classroom->name : 'Unknown Classroom' }}
                                </p>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-2 space-y-2 sm:space-y-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-none text-xs font-black {{ $submission->graded ? 'bg-green-300 text-black' : 'bg-yellow-300 text-black' }} border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)] w-fit">
                                        {{ $submission->graded ? 'Graded' : 'Pending' }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                        {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include the create classroom modal -->
@include('teacher.classrooms.create-modal')

<!-- Add JavaScript for modal functionality -->
<script>
    function openCreateModal() {
        document.getElementById('createClassroomModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    function closeCreateModal() {
        document.getElementById('createClassroomModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
        
        // Reset form fields and errors
        document.getElementById('createClassroomForm').reset();
        document.getElementById('createModalError').classList.add('hidden');
        document.getElementById('nameError').classList.add('hidden');
        document.getElementById('descriptionError').classList.add('hidden');
    }
</script>
@endsection