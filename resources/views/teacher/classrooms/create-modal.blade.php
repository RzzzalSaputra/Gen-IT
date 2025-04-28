<!-- Create Classroom Modal for Teachers -->
<div id="createClassroomModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCreateModal()"></div>
        
        <!-- Modal panel -->
        <div class="relative bg-white dark:bg-gray-800 border-4 border-gray-900 dark:border-gray-600 rounded-none overflow-hidden shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] transform transition-all w-full max-w-lg mx-auto my-2 sm:my-8">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeCreateModal()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none p-2 touch-manipulation">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-4 sm:p-6">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-none bg-blue-100 dark:bg-blue-900 border-3 border-gray-900 dark:border-gray-600 mb-3 sm:mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-800 dark:text-white mb-1" id="modal-title">Create New Classroom</h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Fill in the details to create your classroom</p>
                </div>
                
                <div id="createModalError" class="hidden bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-3 sm:p-4 mb-4 sm:mb-6 rounded-none" role="alert">
                    <p id="createErrorMessage" class="font-bold text-sm sm:text-base"></p>
                </div>

                <form method="POST" action="{{ route('teacher.classrooms.store') }}" id="createClassroomForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Classroom Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 border-3 border-gray-900 dark:border-gray-600 rounded-none text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]" 
                               placeholder="Enter classroom name" 
                               value="{{ old('name') }}"
                               required>
                        <p id="nameError" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose a descriptive name (e.g., "Biology 101 - Spring 2025")</p>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 border-3 border-gray-900 dark:border-gray-600 rounded-none text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.7)]" 
                            placeholder="Enter classroom description"
                            rows="3">{{ old('description') }}</textarea>
                        <p id="descriptionError" class="mt-1 text-sm text-red-600 dark:text-red-400 hidden"></p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Provide a short description about this classroom</p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end mt-6 space-y-3 sm:space-y-0 sm:space-x-4">
                        <button type="button" onclick="closeCreateModal()" class="w-full sm:w-auto py-2 sm:py-0 text-center sm:text-left font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 border-3 border-gray-900 rounded-none font-black text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform order-1 sm:order-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>