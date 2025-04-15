<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-white">
                    My Classrooms
                </h2>
                <button type="button" onclick="openJoinModal()" class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-blue-500 rounded-xl text-white font-medium shadow-lg shadow-blue-500/20 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Join Classroom
                </button>
            </div>

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

            @if($joinedClassrooms->count() > 0)
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($joinedClassrooms as $classroom)
                            <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                <div class="p-1">
                                    <div class="h-32 bg-gradient-to-r from-blue-600/30 to-indigo-600/30 rounded-lg flex items-center justify-center p-6">
                                        <div class="text-center">
                                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:text-blue-400 transition-colors duration-200">
                                        {{ $classroom->name }}
                                    </h3>
                                    
                                    <div class="text-sm text-gray-400 mb-4 line-clamp-2">
                                        {{ Str::limit($classroom->description, 120) }}
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <div class="inline-flex items-center px-2 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-xs text-blue-300 border border-blue-500/30">
                                            Teacher: {{ $classroom->creator->name ?? 'Unknown' }}
                                        </div>

                                        <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                            @php
                                                $userRole = $classroom->members->where('user_id', Auth::id())->first()->role ?? 'student';
                                            @endphp
                                            You're a {{ $userRole }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end mt-6">
                                        <a href="{{ route('student.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Enter Classroom
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
                        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-xl font-semibold mb-2 text-gray-200">No Classrooms Yet</h3>
                        <p class="text-gray-400 mb-6">Join a classroom to start learning with your teachers and classmates.</p>
                        
                        <button type="button" onclick="openJoinModal()" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-blue-500 rounded-xl text-white font-medium shadow-lg shadow-blue-500/20 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Join a Classroom
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Join Classroom Modal (Improved Positioning) -->
    <div id="joinClassroomModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeJoinModal()"></div>
            
            <!-- Modal panel with improved positioning -->
            <div class="relative bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl transform transition-all w-full max-w-md mx-auto my-8 sm:my-12 p-4 sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeJoinModal()" class="text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="text-center mb-6 pt-2">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-blue-600/20 mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1" id="modal-title">Join a Classroom</h2>
                    <p class="text-sm sm:text-base text-gray-400">Enter the code provided by your teacher</p>
                </div>
                
                <div id="modalError" class="hidden bg-red-900/50 backdrop-blur-sm border-l-4 border-red-500 text-red-300 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                    <p id="errorMessage" class="text-sm"></p>
                </div>

                <form method="POST" action="{{ route('student.classrooms.process-join') }}" id="joinClassroomForm">
                    @csrf
                    
                    <div class="mb-4 sm:mb-6">
                        <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Classroom Code</label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                               placeholder="Enter the classroom code" 
                               required>
                        <p id="codeError" class="mt-1 text-xs sm:text-sm text-red-400 hidden"></p>
                    </div>

                    <div class="flex items-center justify-end mt-4 sm:mt-6">
                        <button type="button" onclick="closeJoinModal()" class="mr-3 text-sm text-gray-400 hover:text-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 bg-blue-600 border border-blue-500 rounded-xl text-white text-sm font-medium shadow-lg shadow-blue-500/20 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Join Classroom
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