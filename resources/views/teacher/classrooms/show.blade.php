@extends('layouts.teacher')

@section('title', ($classroom ? $classroom->name : 'Classroom') . ' Dashboard')

@section('content')
<div class="container py-4">
    <!-- Classroom Header -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 space-y-3 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-white">{{ $classroom ? $classroom->name : 'Classroom' }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Kode Kelas: {{ $classroom ? $classroom->code : 'N/A' }}</p>
        </div>
        <button data-modal-target="editClassroomModal" data-modal-toggle="editClassroomModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Kelas
        </button>
    </div>

    <!-- Content Tabs -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
            <nav class="-mb-px flex min-w-full" aria-label="Tabs">
                <button id="materials-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm flex items-center flex-1 sm:flex-none justify-center sm:justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 sm:h-5 w-4 sm:w-5 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Materi
                </button>
                <button id="assignments-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm flex items-center flex-1 sm:flex-none justify-center sm:justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 sm:h-5 w-4 sm:w-5 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Tugas
                </button>
                <button id="members-tab" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm flex items-center flex-1 sm:flex-none justify-center sm:justify-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 sm:h-5 w-4 sm:w-5 mr-1 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Anggota Kelas
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-4 sm:p-6">
            <!-- Materials Content -->
            <div id="materials-content" class="space-y-4 scrollbar-hide" id="materials-section">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-3 sm:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Materi Pelajaran</h3>
                    <button data-modal-target="addMaterialModal" data-modal-toggle="addMaterialModal" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Materi
                    </button>
                </div>
                
                <!-- Materials content remains mostly the same, just ensure grid is responsive -->
                @if(isset($materials) && $materials->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($materials as $material)
                            <div class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600">
                                <!-- Material Type Indicator -->
                                <div class="h-2 bg-blue-500"></div>
                                
                                <div class="p-5">
                                    <!-- Title with cleaner spacing -->
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ $material->title }}</h4>
                                    
                                    <!-- Content preview with better text formatting -->
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">{{ Str::limit($material->content, 120) }}</p>
                                    
                                    <!-- Cleaner date display -->
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $material->create_at ? $material->create_at->format('M d, Y') : 'No date' }}
                                    </div>
                                    
                                    <!-- Actions row with cleaner layout -->
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex space-x-1">
                                            <button data-modal-target="editMaterialModal{{ $material->id }}" data-modal-toggle="editMaterialModal{{ $material->id }}" class="p-1.5 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button class="delete-material-btn p-1.5 rounded-full text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 dark:text-gray-400 dark:hover:text-red-400 transition-colors"
                                                data-material-id="{{ $material->id }}"
                                                data-material-title="{{ $material->title }}"
                                                data-delete-url="{{ route('teacher.materials.destroy', [$classroom->id, $material->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- More prominent "View Details" button -->
                                        <a href="{{ route('teacher.materials.show', [$classroom->id, $material->id]) }}" 
                                           class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Include the edit and delete modals for this specific material -->
                            @include('teacher.classrooms.partials.modals.edit-material')
                            @include('teacher.classrooms.partials.modals.delete-material')
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-500 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No materials yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Get started by adding your first learning material.</p>
                        <button onclick="openModal('addMaterialModal')" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Material
                        </button>
                    </div>
                @endif
            </div>

            <!-- Assignments Content (hidden by default) -->
            <div id="assignments-content" class="hidden space-y-4 scrollbar-hide" id="assignments-section">                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-3 sm:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tugas Kelas</h3>
                    <button data-modal-target="addAssignmentModal" data-modal-toggle="addAssignmentModal" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Tugas
                    </button>
                </div>
                
                @if(isset($assignments) && $assignments->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 scrollbar-hide">
                        @foreach($assignments as $assignment)
                            <div class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-700 
    @php
        $now = \Carbon\Carbon::now();
        $due = \Carbon\Carbon::parse($assignment->due_date);
        
        // Calculate hours difference directly
        $hoursDiff = $now->diffInHours($due, false);
        
        if ($now->gt($due)) {
            // Past due date - Green
            echo 'hover:border-green-300 dark:hover:border-green-600';
            $statusColor = 'bg-green-500';
        } else if ($hoursDiff >= 24) {
            // More than 24 hours (1 day) - Blue
            echo 'hover:border-blue-300 dark:hover:border-blue-600';
            $statusColor = 'bg-blue-500';
        } else {
            // Less than 24 hours - Yellow
            echo 'hover:border-yellow-300 dark:hover:border-yellow-600';
            $statusColor = 'bg-yellow-500';
        }
    @endphp">
    <!-- Assignment Type Indicator - Dynamic color based on status -->
    <div class="h-2 {{ $statusColor }}"></div>
    
    <div class="p-5">
        <!-- Title with cleaner spacing -->
        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ $assignment->title }}</h4>
        
        <!-- Content preview with better text formatting -->
        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">{{ Str::limit($assignment->description, 120) }}</p>
        
        <!-- Due date display -->
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Tenggat: <span class="text-orange-600 dark:text-orange-400">{{ $assignment->due_date ? $assignment->due_date->format('M d, Y, H:i') : 'No due date' }}</span>
        </div>
        
        <!-- Time remaining or status -->
        @if($assignment->due_date)
    <div class="text-xs mb-4">
        @php
            $now = \Carbon\Carbon::now();
            $due = \Carbon\Carbon::parse($assignment->due_date);
            
            if ($now->gt($due)) {
                // Past due date
                $status = 'Selesai';
                $colorClass = 'text-green-600 dark:text-green-400';
                $timeDisplay = '';
            } else {
                // Still time remaining
                $status = '';

                // Calculate hours difference directly
                $hoursDiff = $now->diffInHours($due, false);

                // Change color based on remaining time
                if ($hoursDiff >= 24) {
                    // More than 24 hours (1 day) - blue
                    $colorClass = 'text-blue-600 dark:text-blue-400';
                } else {
                    // Less than 24 hours - yellow
                    $colorClass = 'text-yellow-600 dark:text-yellow-400';
                }
                
                // Fix: Use Carbon's built-in diff methods for correct calculation
                $diffHours = $now->diffInHours($due, false); // Total hours difference
                $diffDays = floor($diffHours / 24); // Full days
                $remainingHours = $diffHours % 24; // Remaining hours after days are calculated
                $diffMinutes = $now->diffInMinutes($due, false) % 60; // Minutes remainder
                
                // Build time display string
                if ($diffDays > 0) {
                    // More than 1 day - show days and hours only
                    $timeDisplay = $diffDays . ' hari' . ', ' . $remainingHours . ' jam';
                } else {
                    // Less than 1 day - show hours and minutes only
                    $timeDisplay = $remainingHours . ' jam' . ', ' . $diffMinutes . ' menit';
                }
                
                // Handle edge cases
                if ($diffDays == 0 && $remainingHours == 0 && $diffMinutes == 0) {
                    $timeDisplay = 'kurang dari semenit';
                }
                
                $timeDisplay .= ' tersisa';
            }
        @endphp
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="{{ $colorClass }}">{{ $status }}{{ $timeDisplay }}</span>
        </div>
    </div>
@endif

<!-- Status badge -->
<div class="mb-4">
    @php
        $now = \Carbon\Carbon::now();
        $due = \Carbon\Carbon::parse($assignment->due_date);
        
        // Calculate hours difference directly
        $hoursDiff = $now->diffInHours($due, false);
        
        if ($now->gt($due)) {
            // Completed/Expired
            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
            $statusText = 'Selesai';
        } else if ($hoursDiff >= 24) {
            // Active, more than 24 hours
            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
            $statusText = 'Aktif';
        } else {
            // Less than 24 hours - approaching deadline
            $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300';
            $statusText = 'Segera Berakhir';
        }
    @endphp
    <span class="px-2 py-1 rounded-full text-xs {{ $statusClass }}">
        {{ $statusText }}
    </span>
    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
        {{ $assignment->submissions()->count() }} submissions
    </span>
</div>
                        
                        <!-- Actions row with cleaner layout -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex space-x-1">
                                <button data-modal-target="editAssignmentModal{{ $assignment->id }}" data-modal-toggle="editAssignmentModal{{ $assignment->id }}" class="p-1.5 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button class="delete-assignment-btn p-1.5 rounded-full text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 dark:text-gray-400 dark:hover:text-red-400 transition-colors"
                                    data-assignment-id="{{ $assignment->id }}"
                                    data-assignment-title="{{ $assignment->title }}"
                                    data-delete-url="{{ route('teacher.assignments.destroy', [$classroom->id, $assignment->id]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- More prominent "View Details" button -->
                            <a href="{{ route('teacher.assignments.show', [$classroom->id, $assignment->id]) }}" 
                               class="px-3 py-1 text-xs font-medium bg-orange-50 text-orange-600 rounded-full hover:bg-orange-100 dark:bg-orange-900/30 dark:text-orange-400 dark:hover:bg-orange-900/50 transition-colors">
                               Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Include modals for this specific assignment -->
                @include('teacher.classrooms.partials.modals.edit-assignment')
                @include('teacher.classrooms.partials.modals.delete-assignment')
            @endforeach
        </div>
    @else
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-500 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Belum ada Assignment</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Mulailah dengan membuat Assignment pertama Anda.</p>
            <button onclick="openModal('addAssignmentModal')" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Assignment
            </button>
        </div>
    @endif
</div>

            <!-- Members Content (hidden by default) -->
            <div id="members-content" class="hidden space-y-4 scrollbar-hide" id="members-section">                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-3 sm:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Anggota Kelas</h3>
                    <button data-modal-target="addMemberModal" data-modal-toggle="addMemberModal" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Anggota
                    </button>
                </div>
                
                @if(isset($members) && $members->count() > 0)
                    <div class="bg-white dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 sm:rounded-lg shadow-sm">
                        <div class="overflow-x-auto scrollbar-hide">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Tanggal Join</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                        <th scope="col" class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($members as $member)
                                    <tr>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                                    @php
                                                        $userName = $member->user ? $member->user->name : 'Unknown';
                                                        $firstName = explode(' ', $userName)[0];
                                                        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($firstName) . "&color=7F9CF5&background=EBF4FF&size=128";
                                                    @endphp
                                                    <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-full" 
                                                        src="{{ $avatarUrl }}" 
                                                        alt="{{ $userName }}">
                                                </div>
                                                <div class="ml-3 sm:ml-4">
                                                    <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">{{ $member->user ? $member->user->name : 'Unknown User' }}</div>
                                                    <!-- Show email on mobile since email column is hidden -->
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">{{ $member->user ? $member->user->email : 'No email' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $member->user ? $member->user->email : 'No email' }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $member->joined_at ? $member->joined_at->format('M d, Y') : 'Unknown date' }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $member->role == 'teacher' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                                {{ ucfirst($member->role ?? 'student') }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-1 sm:space-x-2">
                                                @if($member->user_id != auth()->id())
                                                    <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 change-role-btn p-1.5 sm:p-1" 
                                                        data-modal-target="changeRoleModal" 
                                                        data-modal-toggle="changeRoleModal"
                                                        data-member-id="{{ $member->id }}" 
                                                        data-member-name="{{ $member->user ? $member->user->name : 'Unknown User' }}"
                                                        data-current-role="{{ $member->role }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </button>
                                                    <button class="remove-member-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1.5 sm:p-1" 
                                                        data-member-id="{{ $member->id }}" 
                                                        data-member-name="{{ $member->user ? $member->user->name : 'Unknown User' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-500 dark:text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No members yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Add students and other teachers to your classroom.</p>
                        <button data-modal-target="addMemberModal" data-modal-toggle="addMemberModal" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Member
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('teacher.classrooms.partials.modals.edit-classroom')
@include('teacher.classrooms.partials.modals.add-material')
@include('teacher.classrooms.partials.modals.add-member')
@include('teacher.classrooms.partials.modals.add-assignment')
@include('teacher.classrooms.partials.modals.change-role')


<style>
/* Improved scrollbar hiding */
.scrollbar-hide {
    -ms-overflow-style: none !important;  /* IE and Edge */
    scrollbar-width: none !important;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none !important;  /* Chrome, Safari, Opera */
    width: 0 !important;
    height: 0 !important;
}

/* Hide scrollbars on the entire content area */
.bg-white.dark\:bg-gray-800.overflow-hidden {
    overflow: hidden !important;
}

/* Specifically hide scrollbar on tab navigation */
.border-b.border-gray-200.dark\:border-gray-700.overflow-x-auto {
    -ms-overflow-style: none !important;
    scrollbar-width: none !important;
    overflow-x: auto !important;
}
.border-b.border-gray-200.dark\:border-gray-700.overflow-x-auto::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
}

/* Ensure tab panels don't create unwanted scrollbars */
[id$="-content"] {
    overflow-x: hidden !important;
    overflow-y: auto !important;
}

/* Add some mobile-specific styles */
@media (max-width: 640px) {
    /* Make touch targets bigger on mobile */
    button, a {
        touch-action: manipulation;
    }
}

</style>

<!-- JavaScript unchanged -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = ['materials', 'assignments', 'members'];
    
    // Function to switch tabs
    function switchToTab(tabName) {
        // Hide all content
        tabs.forEach(t => {
            document.getElementById(`${t}-content`).classList.add('hidden');
            document.getElementById(`${t}-tab`).classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-500');
            document.getElementById(`${t}-tab`).classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        });
        
        // Show selected content
        document.getElementById(`${tabName}-content`).classList.remove('hidden');
        document.getElementById(`${tabName}-tab`).classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-500');
        document.getElementById(`${tabName}-tab`).classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        
        // Update URL hash without scrolling
        history.replaceState(null, null, `#${tabName}`);
        
        // Store the active tab in localStorage
        localStorage.setItem('classroom_active_tab', tabName);
    }
    
    // Add click event listeners to tabs
    tabs.forEach(tab => {
        document.getElementById(`${tab}-tab`).addEventListener('click', () => {
            switchToTab(tab);
        });
    });

    // Handle tab selection on page load - priority order:
    // 1. URL hash
    // 2. localStorage value
    // 3. Default to materials tab
    const hash = window.location.hash;
    const savedTab = localStorage.getItem('classroom_active_tab');
    
    if (hash && tabs.includes(hash.substring(1))) {
        // If hash exists and is a valid tab, switch to it
        switchToTab(hash.substring(1));
    } else if (savedTab && tabs.includes(savedTab)) {
        // If we have a saved tab in localStorage, use that
        switchToTab(savedTab);
    } else {
        // Default to materials tab if no hash or localStorage value
        switchToTab('materials');
    }

    // Listen for hashchange events (browser back/forward buttons)
    window.addEventListener('hashchange', function() {
        const newHash = window.location.hash;
        if (newHash && tabs.includes(newHash.substring(1))) {
            switchToTab(newHash.substring(1));
        }
    });

    // Add this code to handle all modals
    const modalButtons = document.querySelectorAll('[data-modal-target]');
    modalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');
            openModal(modalId);
        });
    });

    // Add event listeners for modal close buttons
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.closest('.modal').id;
            closeModal(modalId);
        });
    });
    
    // Handle clicks on the modal backdrop (optional)
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    // Replace modal handling with SweetAlert
    // For deleting materials
    document.querySelectorAll('.delete-material-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const materialId = this.getAttribute('data-material-id');
            const materialTitle = this.getAttribute('data-material-title');
            const deleteUrl = this.getAttribute('data-delete-url');
            
            Swal.fire({
                title: 'Hapus Materi?',
                text: `Apakah kamu yakin ingin menghapus "${materialTitle}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
    
    // For deleting assignments
    document.querySelectorAll('.delete-assignment-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const assignmentId = this.getAttribute('data-assignment-id');
            const assignmentTitle = this.getAttribute('data-assignment-title');
            const deleteUrl = this.getAttribute('data-delete-url');
            
            Swal.fire({
                title: 'Hapus Tugas?',
                text: `Apakah kamu yakin ingin menghapus "${assignmentTitle}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
    
    // For removing members
    document.querySelectorAll('.remove-member-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const memberId = this.getAttribute('data-member-id');
            const memberName = this.getAttribute('data-member-name');
            const deleteUrl = "{{ route('teacher.members.destroy', [$classroom->id, ':memberId']) }}".replace(':memberId', memberId);
            
            Swal.fire({
                title: 'Hapus Anggota?',
                text: `Apakah kamu yakin ingin mengeluarkan ${memberName} dari kelas ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluarkan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    
                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});

// Define the missing openModal and closeModal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}
</script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection