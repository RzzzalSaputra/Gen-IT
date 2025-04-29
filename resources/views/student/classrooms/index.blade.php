<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-white">
                    Kelas Saya
                </h2>
                <div class="flex space-x-3">
                    @if(Auth::user()->role === 'teacher')
                    <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 border border-emerald-500 rounded-xl text-white font-medium shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-emerald-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Dashboard Guru
                    </a>
                    @endif
                    <button type="button" onclick="openJoinModal()" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-indigo-500 rounded-xl text-white font-medium shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-indigo-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Gabung Kelas
                    </button>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-emerald-900/50 backdrop-blur-sm border-l-4 border-emerald-500 text-emerald-300 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-900/50 backdrop-blur-sm border-l-4 border-rose-500 text-rose-300 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($joinedClassrooms->count() > 0)
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($joinedClassrooms as $index => $classroom)
                            @php
                                // Create a rotation of color schemes for different classrooms
                                $colorSchemes = [
                                    // Blue-Purple scheme
                                    [
                                        'gradient' => 'from-blue-600/30 to-purple-600/30',
                                        'icon' => 'from-blue-500 to-purple-600',
                                        'iconShadow' => 'shadow-purple-500/20',
                                        'hoverText' => 'text-blue-400',
                                        'button' => 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700',
                                        'teacherBadge' => 'bg-blue-900/30 text-blue-300 border-blue-500/30',
                                        'roleBadge' => 'bg-purple-900/30 text-purple-300 border-purple-500/30',
                                        'hoverBorder' => 'hover:border-blue-500/50',
                                    ],
                                    // Teal-Emerald scheme
                                    [
                                        'gradient' => 'from-teal-600/30 to-emerald-600/30',
                                        'icon' => 'from-teal-500 to-emerald-600',
                                        'iconShadow' => 'shadow-emerald-500/20',
                                        'hoverText' => 'text-teal-400',
                                        'button' => 'bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700',
                                        'teacherBadge' => 'bg-teal-900/30 text-teal-300 border-teal-500/30',
                                        'roleBadge' => 'bg-emerald-900/30 text-emerald-300 border-emerald-500/30',
                                        'hoverBorder' => 'hover:border-teal-500/50',
                                    ],
                                    // Indigo-Violet scheme
                                    [
                                        'gradient' => 'from-indigo-600/30 to-violet-600/30',
                                        'icon' => 'from-indigo-500 to-violet-600',
                                        'iconShadow' => 'shadow-violet-500/20',
                                        'hoverText' => 'text-indigo-400',
                                        'button' => 'bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700',
                                        'teacherBadge' => 'bg-indigo-900/30 text-indigo-300 border-indigo-500/30',
                                        'roleBadge' => 'bg-violet-900/30 text-violet-300 border-violet-500/30',
                                        'hoverBorder' => 'hover:border-indigo-500/50',
                                    ],
                                    // Rose-Pink scheme
                                    [
                                        'gradient' => 'from-rose-600/30 to-pink-600/30',
                                        'icon' => 'from-rose-500 to-pink-600',
                                        'iconShadow' => 'shadow-pink-500/20',
                                        'hoverText' => 'text-rose-400',
                                        'button' => 'bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700',
                                        'teacherBadge' => 'bg-rose-900/30 text-rose-300 border-rose-500/30',
                                        'roleBadge' => 'bg-pink-900/30 text-pink-300 border-pink-500/30',
                                        'hoverBorder' => 'hover:border-rose-500/50',
                                    ],
                                    // Amber-Orange scheme
                                    [
                                        'gradient' => 'from-amber-600/30 to-orange-600/30',
                                        'icon' => 'from-amber-500 to-orange-600',
                                        'iconShadow' => 'shadow-orange-500/20',
                                        'hoverText' => 'text-amber-400',
                                        'button' => 'bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700',
                                        'teacherBadge' => 'bg-amber-900/30 text-amber-300 border-amber-500/30',
                                        'roleBadge' => 'bg-orange-900/30 text-orange-300 border-orange-500/30',
                                        'hoverBorder' => 'hover:border-amber-500/50',
                                    ],
                                ];
                                
                                // Select a color scheme based on the index
                                $scheme = $colorSchemes[$index % count($colorSchemes)];
                            @endphp
                            
                            <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 {{ $scheme['hoverBorder'] }} transition-all duration-300 hover:shadow-blue-500/5">
                                <div class="p-1">
                                    <div class="h-32 bg-gradient-to-r {{ $scheme['gradient'] }} rounded-lg flex items-center justify-center p-6">
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br {{ $scheme['icon'] }} flex items-center justify-center shadow-lg {{ $scheme['iconShadow'] }}">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:{{ $scheme['hoverText'] }} transition-colors duration-200">
                                        {{ $classroom->name }}
                                    </h3>
                                    
                                    <div class="text-sm text-gray-400 mb-4 line-clamp-2">
                                        {{ Str::limit($classroom->description, 120) }}
                                    </div>
                                    
                                    <!-- Add assignment progress stats -->
                                    <div class="bg-gray-900/40 rounded-xl p-4 mb-4">
                                        <div class="grid grid-cols-2 gap-3">
                                            <!-- Assignment progress card -->
                                            <div class="bg-gray-800/50 rounded-lg p-3">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-400">Tugas</span>
                                                    @php
                                                        // Filter to get only active (non-deleted) assignments
                                                        $activeAssignments = $classroom->assignments->filter(function($assignment) {
                                                            return $assignment->delete_at === null;
                                                        });
                                                        
                                                        $totalAssignments = $activeAssignments->count();
                                                        $now = \Carbon\Carbon::now();
                                                        
                                                        // Count assignments that have any submission from the current user
                                                        // or are past their due date (considered "completed" either way)
                                                        $completedAssignments = $activeAssignments
                                                            ->filter(function($assignment) use ($now) {
                                                                return $assignment->submissions->where('user_id', Auth::id())->isNotEmpty() ||
                                                                      ($assignment->due_date && $now->gt($assignment->due_date));
                                                            })->count();
                                                        
                                                        // Calculate assignment percentage
                                                        $assignmentPercentage = $totalAssignments > 0 
                                                            ? ($completedAssignments / $totalAssignments) * 100 
                                                            : 0;
                                                    @endphp
                                                    <span class="text-sm font-medium {{ $scheme['hoverText'] }}">{{ $completedAssignments }}/{{ $totalAssignments }}</span>
                                                </div>
                                                <div class="w-full bg-gray-700/50 rounded-full h-1.5 mt-1">
                                                    <div class="bg-gradient-to-r {{ $scheme['icon'] }} h-1.5 rounded-full" style="width: {{ $assignmentPercentage }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Materials progress card -->
                                            <div class="bg-gray-800/50 rounded-lg p-3">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-400">Materi</span>
                                                    @php
                                                        $totalMaterials = $classroom->materials->count() ?? 0;
                                                        
                                                        // For materials viewed tracking, you would need to implement a system
                                                        // to track which materials a student has viewed. For now, we'll just show the count.
                                                    @endphp
                                                    <span class="text-sm font-medium {{ $scheme['hoverText'] }}">{{ $totalMaterials }}</span>
                                                </div>
                                                <div class="w-full bg-gray-700/50 rounded-full h-1.5 mt-1">
                                                    <div class="bg-gradient-to-r {{ $scheme['icon'] }} h-1.5 rounded-full" style="width: 100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status indicator for assignments -->
                                    @if($totalAssignments > 0)
                                        @php
                                            // Calculate missed (overdue) assignments
                                            $now = \Carbon\Carbon::now();
                                            $missedAssignments = 0;
                                            $upcomingAssignments = 0;
                                            
                                            foreach($activeAssignments as $assignment) {
                                                // Check if user hasn't submitted and the due date has passed
                                                if ($assignment->due_date && $now->gt($assignment->due_date) && 
                                                    !$assignment->submissions->where('user_id', Auth::id())->count()) {
                                                    $missedAssignments++;
                                                } 
                                                // Still has time to submit
                                                elseif (!$assignment->submissions->where('user_id', Auth::id())->count()) {
                                                    $upcomingAssignments++;
                                                }
                                            }
                                            
                                            // Determine status classes based on missed assignments
                                            if ($missedAssignments > 0) {
                                                $statusClass = 'bg-rose-900/30 text-rose-300 border-rose-500/30';
                                                $indicatorClass = 'bg-rose-400';
                                                $message = $missedAssignments . ' ' . ($missedAssignments == 1 ? 'tugas terlewat' : 'tugas terlewatkan');
                                            } elseif ($upcomingAssignments > 0) {
                                                $statusClass = 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30';
                                                $indicatorClass = 'bg-yellow-400';
                                                $message = $upcomingAssignments . ' ' . ($upcomingAssignments == 1 ? 'tugas tersisa' : 'tugas tersisa');
                                            } else {
                                                $statusClass = 'bg-green-900/30 text-green-300 border-green-500/30';
                                                $indicatorClass = 'bg-green-400';
                                                $message = 'Semua tugas selesai';
                                            }
                                        @endphp
                                        <div class="inline-flex items-center px-2 py-1 mb-3 {{ $statusClass }} backdrop-blur-sm rounded-lg text-xs">
                                            <span class="w-2 h-2 flex-shrink-0 rounded-full mr-2 {{ $indicatorClass }}"></span>
                                            {{ $message }}
                                        </div>
                                    @endif
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <div class="inline-flex items-center px-2 py-1 {{ $scheme['teacherBadge'] }} backdrop-blur-sm rounded-lg text-xs">
                                            Guru: {{ $classroom->creator->name ?? 'Tidak diketahui' }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end mt-6">
                                        <a href="{{ route('student.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-4 py-2 {{ $scheme['button'] }} text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Masuk Kelas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                    <div class="p-10 text-center">
                        <svg class="mx-auto h-16 w-16 text-indigo-500/70 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2 text-indigo-200">Belum Ada Kelas</h3>
                        <p class="text-gray-400 mb-6">Gabung kelas untuk mulai belajar bersama guru dan teman sekelas Anda.</p>
                        
                        <button type="button" onclick="openJoinModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-indigo-500 rounded-xl text-white font-medium shadow-lg shadow-indigo-500/20 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-indigo-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Gabung Kelas
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Join Classroom Modal -->
    <div id="joinClassroomModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeJoinModal()"></div>
            
            <!-- Modal panel with improved positioning -->
            <div class="relative bg-gradient-to-b from-gray-800/80 to-gray-900/80 backdrop-blur-sm rounded-2xl border border-indigo-700/30 overflow-hidden shadow-xl transform transition-all w-full max-w-md mx-auto my-8 sm:my-12 p-4 sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeJoinModal()" class="text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="text-center mb-6 pt-2">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-indigo-600/20 to-purple-600/20 mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1" id="modal-title">Gabung Kelas</h2>
                    <p class="text-sm sm:text-base text-gray-400">Masukkan kode yang diberikan oleh guru Anda</p>
                </div>
                
                <div id="modalError" class="hidden bg-rose-900/50 backdrop-blur-sm border-l-4 border-rose-500 text-rose-300 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                    <p id="errorMessage" class="text-sm"></p>
                </div>

                <form method="POST" action="{{ route('student.classrooms.process-join') }}" id="joinClassroomForm">
                    @csrf
                    
                    <div class="mb-4 sm:mb-6">
                        <label for="code" class="block text-sm font-medium text-indigo-300 mb-2">Kode Kelas</label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-gray-700/50 border border-indigo-600/30 rounded-xl text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-all duration-200" 
                               placeholder="Masukkan kode kelas" 
                               required>
                        <p id="codeError" class="mt-1 text-xs sm:text-sm text-rose-400 hidden"></p>
                    </div>

                    <div class="flex items-center justify-end mt-4 sm:mt-6">
                        <button type="button" onclick="closeJoinModal()" class="mr-3 text-sm text-gray-400 hover:text-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-indigo-500 rounded-xl text-white text-sm font-medium shadow-lg shadow-indigo-500/20 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-purple-500 transition-all duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Gabung Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add JavaScript for modal functionality -->
    <script>
        function openJoinModal() {
            document.getElementById('joinClassroomModal').classList.remove('hidden');
            document.getElementById('code').focus();
            // Reset form and errors
            document.getElementById('joinClassroomForm').reset();
            document.getElementById('modalError').classList.add('hidden');
            document.getElementById('codeError').classList.add('hidden');
        }
        
        function closeJoinModal() {
            document.getElementById('joinClassroomModal').classList.add('hidden');
        }
        
        // Close modal when clicking Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeJoinModal();
            }
        });
        
        // Handle form submission errors
        @if(session('error'))
            // If there was an error from a previous submission, show the modal with the error
            document.addEventListener('DOMContentLoaded', function() {
                openJoinModal();
                document.getElementById('modalError').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = "{{ session('error') }}";
            });
        @endif
        
        @error('code')
            document.addEventListener('DOMContentLoaded', function() {
                openJoinModal();
                document.getElementById('codeError').classList.remove('hidden');
                document.getElementById('codeError').textContent = "{{ $message }}";
            });
        @enderror
    </script>
</x-app-layout>