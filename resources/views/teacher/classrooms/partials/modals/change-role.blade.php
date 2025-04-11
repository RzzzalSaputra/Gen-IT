<!-- Change Role Modal -->
<div class="fixed inset-0 overflow-y-auto hidden" id="changeRoleModal" aria-labelledby="changeRoleModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="changeRoleForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="changeRoleModalLabel">Change Member Role</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('changeRoleModal').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="memberRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                        <select id="memberRole" name="role" required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:text-white">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                    <input type="hidden" id="memberIdForRole" name="member_id">
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="document.getElementById('changeRoleModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up form action when the change role button is clicked
    document.querySelectorAll('.change-role-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const memberId = this.getAttribute('data-member-id');
            const currentRole = this.getAttribute('data-current-role');
            
            // Set the member ID in the hidden field
            document.getElementById('memberIdForRole').value = memberId;
            
            // Set the initial selection in the dropdown
            const roleSelect = document.getElementById('memberRole');
            for (let i = 0; i < roleSelect.options.length; i++) {
                if (roleSelect.options[i].value === currentRole) {
                    roleSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Update the form action with the correct route
            const form = document.getElementById('changeRoleForm');
            form.action = "{{ route('teacher.members.update.role', [$classroom->id, '']) }}" + memberId;
            
            // Show the modal
            document.getElementById('changeRoleModal').classList.remove('hidden');
        });
    });
});
</script>