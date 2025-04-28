<!-- Join Classroom Modal for Teachers -->
<div id="joinClassroomModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeJoinModal()"></div>
        
        <!-- Modal panel -->
        <div class="relative bg-white dark:bg-gray-800 border-4 border-gray-900 dark:border-gray-600 rounded-none overflow-hidden shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] transform transition-all w-full max-w-md mx-auto my-2 sm:my-8">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeJoinModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none p-2 touch-manipulation">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-4 sm:p-6">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-none bg-green-100 dark:bg-green-900 border-3 border-gray-900 dark:border-gray-600 mb-3 sm:mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-800 dark:text-white mb-1" id="modal-title">Join as Teacher</h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Enter the classroom code to join as a teacher</p>
                </div>
                
                <div id="modalError" class="hidden bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 mb-4 sm:mb-6 rounded-none" role="alert">
                    <p id="errorMessage" class="font-bold text-sm sm:text-base"></p>
                </div>

                <form method="POST" action="{{ route('teacher.classrooms.process-join') }}" id="joinClassroomForm">
                    @csrf
                    <!-- Hidden input for role - this ensures the person joining will be a teacher -->
                    <input type="hidden" name="role" value="teacher">
                    
                    <div class="mb-6">
                        <label for="code" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Classroom Code</label>
                        <input type="text" 
                               name="code" 
                               id="code" 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 border-3 border-gray-900 dark:border-gray-600 rounded-none text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]" 
                               placeholder="Enter classroom code" 
                               required>
                        <p id="codeError" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end mt-6 space-y-3 sm:space-y-0 sm:space-x-4">
                        <button type="button" onclick="closeJoinModal()" class="w-full sm:w-auto py-2 sm:py-0 text-center sm:text-left font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-green-600 border-3 border-gray-900 rounded-none font-black text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform order-1 sm:order-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Join Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>