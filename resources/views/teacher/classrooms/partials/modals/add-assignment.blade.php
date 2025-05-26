<!-- Add Assignment Modal -->
<div class="fixed inset-0 overflow-y-auto hidden" id="addAssignmentModal" aria-labelledby="addAssignmentModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('teacher.assignments.store', $classroom->id) }}" method="POST" enctype="multipart/form-data" id="addAssignmentForm">
                @csrf
                <input type="hidden" name="redirect_hash" value="assignments">
                
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="addAssignmentModalLabel">Tambah Tugas Baru</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('addAssignmentModal').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="assignmentTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                        <input type="text" name="title" id="assignmentTitle" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="assignmentDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <!-- Replace textarea with Quill editor div -->
                        <div id="assignmentDescriptionEditor" class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600"></div>
                        <input type="hidden" name="description" id="assignmentDescription">
                    </div>
                    
                    <div class="mb-4">
                        <label for="assignmentDueDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tenggat</label>
                        <input type="datetime-local" name="due_date" id="assignmentDueDate" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="assignmentFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran(opsional)</label>
                        <input type="file" name="file" id="assignmentFile"
                            class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal Ukuran: 10 MB</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Buat Tugas
                    </button>
                    <button type="button" onclick="document.getElementById('addAssignmentModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load Quill if not already loaded
    if (typeof Quill === 'undefined') {
        // Add Quill CSS
        const quillCSS = document.createElement('link');
        quillCSS.rel = 'stylesheet';
        quillCSS.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
        document.head.appendChild(quillCSS);
        
        // Add Quill JS
        const quillScript = document.createElement('script');
        quillScript.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
        quillScript.onload = initializeQuill;
        document.head.appendChild(quillScript);
    } else {
        initializeQuill();
    }

    let assignmentQuill;
    
    function initializeQuill() {
        // Simplified toolbar configuration like Google Classroom
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],                 // basic formatting
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],    // lists
            ['link'],                                        // link
            [{ 'align': [] }],                               // text alignment
            ['clean']                                        // remove formatting
        ];
        
        // Initialize Quill editor
        const editorContainer = document.getElementById('assignmentDescriptionEditor');
        if (editorContainer) {
            assignmentQuill = new Quill('#assignmentDescriptionEditor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
                placeholder: 'Tambahkan deskripsi atau instruksi tugas...'
            });
            
            // Apply styling
            applyQuillStyling();
            
            // Make sure to get content when form is submitted
            const addAssignmentForm = document.getElementById('addAssignmentForm');
            if (addAssignmentForm) {
                addAssignmentForm.addEventListener('submit', function(e) {
                    // Save Quill content to hidden field before submission
                    document.getElementById('assignmentDescription').value = assignmentQuill.root.innerHTML;
                });
            }
        }
    }

    function applyQuillStyling() {
        const style = document.createElement('style');
        
        // Strong dark mode styling with high contrast
        style.textContent = `
            /* Editor container */
            .ql-container.ql-snow {
                border: 1px solid #4B5563;
                border-top: 0;
                border-radius: 0 0 4px 4px;
                font-family: inherit;
                background-color: #1F2937 !important;
            }
            
            /* Editor area with bright white text */
            .ql-editor {
                min-height: 150px;
                font-size: 15px;
                line-height: 1.5;
                padding: 12px 15px;
                color: #FFFFFF !important;
                background-color: #1F2937 !important;
            }
            
            /* Toolbar styling */
            .ql-toolbar.ql-snow {
                border: 1px solid #4B5563;
                border-radius: 4px 4px 0 0;
                padding: 8px;
                background-color: #374151 !important;
            }
            
            /* Toolbar button colors - bright white */
            .ql-snow .ql-stroke {
                stroke: #FFFFFF !important;
            }
            
            .ql-snow .ql-fill {
                fill: #FFFFFF !important;
            }
            
            .ql-snow .ql-picker {
                color: #FFFFFF !important;
            }
            
            /* Force white text for all content */
            .ql-editor p, .ql-editor ol, .ql-editor ul, .ql-editor pre, 
            .ql-editor blockquote, .ql-editor h1, .ql-editor h2, .ql-editor h3, 
            .ql-editor h4, .ql-editor h5, .ql-editor h6 {
                color: #FFFFFF !important;
            }
            
            .ql-editor a {
                color: #93C5FD !important;
            }
            
            /* Placeholder text */
            .ql-editor.ql-blank::before {
                font-style: italic;
                color: #9CA3AF !important;
            }
            
            /* Fixed dropdown menu styling */
            .ql-snow .ql-picker-options {
                background-color: #374151 !important;
                border-color: #4B5563 !important;
            }
            
            .ql-snow .ql-picker-item {
                color: #FFFFFF !important;
            }
        `;
        
        document.head.appendChild(style);
    }
    
    // Reset Quill when modal is closed
    const closeButtons = document.querySelectorAll('[onclick*="addAssignmentModal.classList.add(\'hidden\')"]');
    if (closeButtons.length) {
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (assignmentQuill) {
                    assignmentQuill.setText('');
                }
            });
        });
    }
});
</script>