<x-app-layout>
    <!-- SweetAlert CDN -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <!-- Main container with padding that pushes content below fixed navbar -->
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <!-- Spacing div that accounts for the navbar height -->
        <div class="h-16"></div>
        
        <!-- Purple Classroom Header - Positioned directly below navbar -->
        <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 border-b border-gray-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-3">{{ $classroom->name }}</h1>
                        <div class="flex flex-wrap gap-3 mb-4">
                            <div class="inline-flex items-center px-3 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-sm text-blue-300 border border-blue-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Teacher: {{ $classroom->creator->name ?? 'Unknown' }}
                            </div>
                            <div class="inline-flex items-center px-3 py-1 bg-indigo-900/30 backdrop-blur-sm rounded-lg text-sm text-indigo-300 border border-indigo-500/30">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Student
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                        <!-- Leave Classroom Button -->
                        <button 
                            type="button" 
                            onclick="confirmLeaveClassroom()"
                            class="inline-flex items-center px-4 py-2 bg-red-600/70 hover:bg-red-500/70 backdrop-blur-sm text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Leave Classroom
                        </button>

                        <!-- Back to Classrooms Button -->
                        <a href="{{ route('student.classrooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700/70 hover:bg-gray-600/70 backdrop-blur-sm text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Classrooms
                        </a>
                    </div>
                </div>
                <div class="bg-gray-900/30 backdrop-blur-sm rounded-xl p-4 mt-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">About this classroom</h3>
                    <p class="text-gray-400">{{ $classroom->description }}</p>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="bg-green-900/50 backdrop-blur-sm border-l-4 border-green-500 text-green-300 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900/50 backdrop-blur-sm border-l-4 border-red-500 text-red-300 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6" x-data="{ activeTab: 'materials' }">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50 mb-6">
                    <ul class="flex flex-wrap justify-center gap-2 text-sm font-medium">
                        <li>
                            <button @click="activeTab = 'materials'" 
                                :class="activeTab === 'materials' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200'"
                                class="inline-flex items-center px-6 py-3 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Learning Materials
                            </button>
                        </li>
                        <li>
                            <button @click="activeTab = 'assignments'" 
                                :class="activeTab === 'assignments' ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200'"
                                class="inline-flex items-center px-6 py-3 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Assignments
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Materials Tab Content -->
                <div x-show="activeTab === 'materials'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-white">Learning Materials</h2>
                            </div>

                            @if(count($materials) > 0)
                                <div class="space-y-4">
                                    @foreach($materials as $material)
                                        @php
                                            // Determine material type
                                            $isTextOnly = empty($material->file) && empty($material->link);
                                            $isDownloadable = !empty($material->file);
                                            $isVideo = !empty($material->link) && (strpos($material->link, 'youtube.com') !== false || strpos($material->link, 'youtu.be') !== false);
                                            $isExternalLink = !empty($material->link) && !$isVideo;
                                            
                                            // Enhanced styling with gradients and better visual indicators
                                            if ($isTextOnly) {
                                                $iconClass = 'text-blue-300';
                                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>';
                                                $typeLabel = 'Reading Material';
                                                $bgColor = 'bg-gradient-to-br from-blue-900/40 to-blue-800/20';
                                                $borderColor = 'border-blue-600/40';
                                                $hoverBgColor = 'group-hover:from-blue-900/60 group-hover:to-blue-800/40';
                                                $iconBgColor = 'bg-gradient-to-br from-blue-600 to-blue-800';
                                            } elseif ($isDownloadable) {
                                                $iconClass = 'text-green-300';
                                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>';
                                                $typeLabel = 'Study Material';
                                                $bgColor = 'bg-gradient-to-br from-green-900/40 to-emerald-800/20';
                                                $borderColor = 'border-green-600/40';
                                                $hoverBgColor = 'group-hover:from-green-900/60 group-hover:to-emerald-800/40';
                                                $iconBgColor = 'bg-gradient-to-br from-green-600 to-emerald-700';
                                            } elseif ($isVideo) {
                                                $iconClass = 'text-red-300';
                                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>';
                                                $typeLabel = 'Video Material';
                                                $bgColor = 'bg-gradient-to-br from-red-900/40 to-rose-800/20';
                                                $borderColor = 'border-red-600/40';
                                                $hoverBgColor = 'group-hover:from-red-900/60 group-hover:to-rose-800/40';
                                                $iconBgColor = 'bg-gradient-to-br from-red-600 to-rose-700';
                                            } else {
                                                $iconClass = 'text-purple-300';
                                                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>';
                                                $typeLabel = 'Web Resource';
                                                $bgColor = 'bg-gradient-to-br from-purple-900/40 to-indigo-800/20';
                                                $borderColor = 'border-purple-600/40';
                                                $hoverBgColor = 'group-hover:from-purple-900/60 group-hover:to-indigo-800/40';
                                                $iconBgColor = 'bg-gradient-to-br from-purple-600 to-indigo-700';
                                            }
                                        @endphp
                                        
                                        <!-- Enhanced material card with better visual distinction -->
                                        <div class="group {{ $bgColor }} hover:{{ $hoverBgColor }} backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border-2 {{ $borderColor }} transition-all duration-300 hover:shadow-xl hover:shadow-{{ substr($borderColor, 7, 5) }}/10">
                                            <div class="flex flex-col sm:flex-row p-5">
                                                <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
                                                    <div class="w-12 h-12 {{ $iconBgColor }} rounded-xl flex items-center justify-center shadow-lg shadow-{{ substr($iconBgColor, 24) }}/30">
                                                        <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            {!! $icon !!}
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap gap-2 items-center mb-2">
                                                        <h4 class="font-medium text-gray-100 group-hover:text-white transition-colors duration-200">{{ $material->title }}</h4>
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ str_replace('from-', 'bg-', substr($iconBgColor, 0, 30)) }}/40 {{ $iconClass }} border border-{{ substr($borderColor, 7) }}">
                                                            {{ $typeLabel }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-300 line-clamp-2 mb-3">
                                                        {{ Str::limit(strip_tags($material->content), 120) }}
                                                    </p>
                                                    <div class="text-xs text-gray-400">
                                                        Posted: {{ $material->create_at ? $material->create_at->format('M d, Y') : 'Unknown date' }}
                                                        @if($material->creator)
                                                            by {{ $material->creator->name }}
                                                        @endif
                                                    </div>
                                                    <div class="flex justify-end mt-3">
                                                        <a href="{{ route('student.classrooms.materials.show', ['classroom_id' => $classroom->id, 'id' => $material->id]) }}" 
                                                           class="inline-flex items-center px-4 py-2 {{ str_replace('from-', 'bg-', substr($iconBgColor, 0, 30)) }} hover:bg-opacity-90 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            View Material
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-900/30 backdrop-blur-sm rounded-xl">
                                    <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-300 mb-1">No materials available</h3>
                                    <p class="text-gray-500">Check back later for learning materials.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assignments Tab Content -->
                <div x-show="activeTab === 'assignments'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-white">Assignments</h2>
                            </div>

                            @if(count($assignments) > 0)
                                <div class="space-y-4">
                                    @foreach($assignments as $assignment)
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                            $isOverdue = $now->isAfter($dueDate);
                                            
                                            // Set status badge and border color with reduced opacity
                                            if($assignment->user_submission) {
                                                if($assignment->user_submission->graded) {
                                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-300 border border-green-600/30">Graded: ' . $assignment->user_submission->grade . '/100</span>';
                                                    $borderColor = 'border-green-500/30';
                                                    $hoverBorderColor = 'hover:border-green-400/40';
                                                } else {
                                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/50 text-blue-300 border border-blue-600/30">Submitted</span>';
                                                    $borderColor = 'border-blue-500/30';
                                                    $hoverBorderColor = 'hover:border-blue-400/40';
                                                }
                                            } elseif($isOverdue) {
                                                $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/50 text-red-300 border border-red-600/30">Overdue</span>';
                                                $borderColor = 'border-red-500/30';
                                                $hoverBorderColor = 'hover:border-red-400/40';
                                            } else {
                                                $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-300 border border-yellow-600/30">' . $dueDate->diffForHumans() . '</span>';
                                                $borderColor = 'border-yellow-500/30';
                                                $hoverBorderColor = 'hover:border-yellow-400/40';
                                            }
                                        @endphp

                                        <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border-2 {{ $borderColor }} {{ $hoverBorderColor }} transition duration-300">
                                            <div class="p-5">
                                                <div class="flex justify-between items-start">
                                                    <h4 class="font-medium text-gray-100 group-hover:text-purple-400 transition-colors duration-200 mb-2">{{ $assignment->title }}</h4>
                                                    <div class="ml-2">
                                                        {!! $statusBadge !!}
                                                    </div>
                                                </div>
                                                
                                                <!-- Description moved BEFORE date -->
                                                <div class="mt-2 text-sm text-gray-400 line-clamp-2 mb-4">
                                                    {{ Str::limit(strip_tags($assignment->description), 120) }}
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <div class="text-sm text-gray-400">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            Due: {{ $dueDate->format('M d, Y, h:i A') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-between items-center mt-4">
                                                    <div class="text-xs text-gray-500">
                                                        @if($assignment->user_submission)
                                                            @if($assignment->user_submission->graded)
                                                                <span class="text-green-400">Completed</span>
                                                            @else
                                                                <span class="text-blue-400">Submitted</span>
                                                            @endif
                                                        @else
                                                            @if($isOverdue)
                                                                <span class="text-red-400">Missed</span>
                                                            @else
                                                                <span class="text-yellow-400">Pending</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    
                                                    <a href="{{ route('student.classrooms.assignments.show', ['classroom_id' => $classroom->id, 'id' => $assignment->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-700 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-900/30 backdrop-blur-sm rounded-xl">
                                    <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-300 mb-1">No assignments yet</h3>
                                    <p class="text-gray-500">Check back later for assignments.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Confirmation Script -->
    <script>
        function confirmLeaveClassroom() {
            Swal.fire({
                title: 'Leave Classroom?',
                text: "Are you sure you want to leave this classroom? You'll lose access to all materials and assignments.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, leave classroom',
                cancelButtonText: 'Cancel',
                background: '#1f2937',
                backdrop: 'rgba(0,0,0,0.7)',
                iconColor: '#f87171',
                customClass: {
                    title: 'text-white',
                    content: 'text-gray-300',
                    confirmButton: 'py-2 px-4 rounded-lg',
                    cancelButton: 'py-2 px-4 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form to leave the classroom
                    window.location.href = "{{ route('student.classrooms.leave', $classroom->id) }}";
                }
            });
        }
    </script>
</x-app-layout>