<!-- Edit Classroom Modal -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="fixed inset-0 overflow-y-auto hidden" id="editClassroomModal" aria-labelledby="editClassroomModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="editClassroomModalLabel">Edit Classroom</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('editClassroomModal').classList.add('hidden')">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('teacher.classrooms.update', $classroom->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="classroomName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Classroom Name</label>
                        <input type="text" name="name" id="classroomName" value="{{ $classroom->name }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="classroomDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="classroomDescription" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $classroom->description }}</textarea>
                    </div>
                
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button" onclick="document.getElementById('editClassroomModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
                
                <form id="deleteClassroomForm" action="{{ route('teacher.classrooms.destroy', $classroom->id) }}" method="POST" class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" id="deleteClassroomButton" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            Delete Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.getElementById('deleteClassroomButton');
        const deleteForm = document.getElementById('deleteClassroomForm');
        
        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this classroom. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: '#1f2937',  // Dark background
                color: '#f3f4f6',       // Light text color
                iconColor: '#fbbf24',   // Amber warning icon
                customClass: {
                    popup: 'swal-dark-theme',
                    title: 'text-gray-100',
                    htmlContainer: 'text-gray-300',
                    confirmButton: 'bg-red-600 hover:bg-red-700',
                    cancelButton: 'bg-blue-600 hover:bg-blue-700'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    });
</script>

<style>
    .swal-dark-theme {
        border: 1px solid #374151 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }
    
    .swal2-popup.swal2-toast {
        background: #1f2937 !important;
        color: #f3f4f6 !important;
    }
    
    .swal2-title, .swal2-html-container {
        color: inherit !important;
    }
</style>