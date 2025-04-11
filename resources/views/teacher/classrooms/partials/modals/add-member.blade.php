<div class="fixed inset-0 overflow-y-auto hidden" id="addMemberModal" aria-labelledby="addMemberModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('teacher.members.store', $classroom->id) }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="addMemberModalLabel">Add New Member</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('addMemberModal').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add By</label>
                        <div class="mt-1 space-y-2">
                            <div class="flex items-center">
                                <input id="addByEmail" name="add_by" type="radio" value="email" checked class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600">
                                <label for="addByEmail" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            </div>
                            <div class="flex items-center">
                                <input id="addByUserId" name="add_by" type="radio" value="user_id" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600">
                                <label for="addByUserId" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">User ID</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="emailInputGroup" class="mb-4">
                        <label for="memberEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" id="memberEmail" placeholder="example@email.com" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">User must be registered in the system</p>
                    </div>
                    
                    <div id="userIdInputGroup" class="mb-4 hidden">
                        <label for="memberId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User ID</label>
                        <input type="number" name="user_id" id="memberId" placeholder="Enter user ID" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="memberRoleSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                        <select id="memberRoleSelect" name="role" required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Member
                    </button>
                    <button type="button" onclick="document.getElementById('addMemberModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('emailInputGroup');
        const userIdInput = document.getElementById('userIdInputGroup');
        const addByEmailRadio = document.getElementById('addByEmail');
        const addByUserIdRadio = document.getElementById('addByUserId');
        
        // Open modal functionality
        const openModalButtons = document.querySelectorAll('[data-modal-target="addMemberModal"]');
        openModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('addMemberModal').classList.remove('hidden');
            });
        });
        
        addByEmailRadio.addEventListener('change', function() {
            if (this.checked) {
                emailInput.classList.remove('hidden');
                userIdInput.classList.add('hidden');
            }
        });
        
        addByUserIdRadio.addEventListener('change', function() {
            if (this.checked) {
                userIdInput.classList.remove('hidden');
                emailInput.classList.add('hidden');
            }
        });
    });
</script>