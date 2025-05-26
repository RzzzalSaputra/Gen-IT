<!-- Edit Assignment Modal -->
<div class="fixed inset-0 overflow-y-auto hidden" id="editAssignmentModal{{ $assignment->id }}" aria-labelledby="editAssignmentModalLabel{{ $assignment->id }}" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('teacher.assignments.update', [$classroom->id, $assignment->id]) }}" method="POST" enctype="multipart/form-data" id="editAssignmentForm{{ $assignment->id }}">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="editAssignmentModalLabel{{ $assignment->id }}">Edit Tugas</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('editAssignmentModal{{ $assignment->id }}').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editAssignmentTitle{{ $assignment->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                        <input type="text" name="title" id="editAssignmentTitle{{ $assignment->id }}" value="{{ $assignment->title }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editAssignmentDescription{{ $assignment->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <!-- Replace textarea with Quill editor div -->
                        <div id="editAssignmentDescriptionEditor{{ $assignment->id }}" class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600"></div>
                        <input type="hidden" name="description" id="editAssignmentDescription{{ $assignment->id }}" value="{{ $assignment->description }}">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editAssignmentDueDate{{ $assignment->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tenggat</label>
                        <input type="datetime-local" name="due_date" id="editAssignmentDueDate{{ $assignment->id }}" value="{{ date('Y-m-d\TH:i', strtotime($assignment->due_date)) }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editAssignmentFile{{ $assignment->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran</label>
                        @if($assignment->file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $assignment->file) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Current File
                                </a>
                            </div>
                        @endif
                        <input type="file" name="file" id="editAssignmentFile{{ $assignment->id }}"
                            class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unggah file baru untuk menggantikan file saat ini</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('editAssignmentModal{{ $assignment->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeEditAssignmentEditor{{ $assignment->id }}();
    
    function initializeEditAssignmentEditor{{ $assignment->id }}() {
        // Load Quill if not already loaded
        if (typeof Quill === 'undefined') {
            // Check if we're already loading Quill
            if (document.querySelector('script[src*="quill.min.js"]')) {
                // Quill is loading, wait for it
                setTimeout(() => initializeEditAssignmentEditor{{ $assignment->id }}(), 100);
                return;
            }
            
            // Add Quill CSS if not already added
            if (!document.querySelector('link[href*="quill.snow.css"]')) {
                const quillCSS = document.createElement('link');
                quillCSS.rel = 'stylesheet';
                quillCSS.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
                document.head.appendChild(quillCSS);
            }
            
            // Add Quill JS
            const quillScript = document.createElement('script');
            quillScript.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
            quillScript.onload = () => setupQuill{{ $assignment->id }}();
            document.head.appendChild(quillScript);
        } else {
            setupQuill{{ $assignment->id }}();
        }
    }
    
    function setupQuill{{ $assignment->id }}() {
        const assignmentId = {{ $assignment->id }};
        const contentValue = document.getElementById(`editAssignmentDescription${assignmentId}`).value;
        
        // Simplified toolbar configuration
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],                 // basic formatting
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],    // lists
            ['link'],                                        // link
            [{ 'align': [] }],                               // text alignment
            ['clean']                                        // remove formatting
        ];
        
        // Initialize Quill editor
        const editorContainer = document.getElementById(`editAssignmentDescriptionEditor${assignmentId}`);
        if (editorContainer) {
            const quill = new Quill(`#editAssignmentDescriptionEditor${assignmentId}`, {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
                placeholder: 'Add description or instructions...'
            });
            
            // Set initial content
            quill.root.innerHTML = contentValue;
            
            // Apply styling
            applyQuillStyling{{ $assignment->id }}();
            
            // Make sure to get content when form is submitted
            const editAssignmentForm = document.getElementById(`editAssignmentForm${assignmentId}`);
            if (editAssignmentForm) {
                editAssignmentForm.addEventListener('submit', function(e) {
                    // Save Quill content to hidden field before submission
                    const content = quill.root.innerHTML;
                    document.getElementById(`editAssignmentDescription${assignmentId}`).value = content;
                });
            }
        }
    }
    
    function applyQuillStyling{{ $assignment->id }}() {
        // Check if we already added this styling
        if (document.getElementById('quill-dark-style-{{ $assignment->id }}')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'quill-dark-style-{{ $assignment->id }}';
        
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
});
</script>