<!-- Add Material Modal -->
<div class="fixed inset-0 z-50 overflow-y-auto hidden" id="addMaterialModal" aria-labelledby="addMaterialModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addMaterialModal')"></div>
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                <button type="button" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none" onclick="closeModal('addMaterialModal')">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="addMaterialForm" action="{{ route('teacher.materials.store', $classroom->id) }}" method="POST" enctype="multipart/form-data" class="max-h-[80vh] overflow-y-auto scrollbar-hidden">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4 sm:mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="addMaterialModalLabel">Tambah Materi Baru</h3>
                    </div>
                    
                    <div class="sm:flex sm:flex-wrap sm:gap-x-4">
                        <div class="mb-4 sm:mb-2 sm:w-full">
                            <label for="materialTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
                            <input type="text" name="title" id="materialTitle" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        {{-- Hidden field for material type, automatically set to classroom --}}
                        @php
                            // Try to find the classroom material type dynamically first
                            $classroomOption = App\Models\Option::where('type', 'classroom_material_type')
                                ->where('value', 'classroom')
                                ->first();
                            
                            // If not found, use the known ID from the database (ID 44)
                            $optionId = $classroomOption ? $classroomOption->id : 44;
                        @endphp
                        <input type="hidden" name="type" value="{{ $optionId }}">
                        
                        <!-- Replace the textarea with a div for Quill with fixed height -->
                        <div class="mb-6 sm:mb-5 sm:w-full">
                            <label for="materialContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konten</label>
                            <div id="materialContentEditor" class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600"></div>
                            <input type="hidden" name="content" id="materialContent">
                        </div>
                        
                        <div class="mb-4 sm:w-1/2">
                            <label for="materialFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lampiran (Opsional)</label>
                            <input type="file" name="file" id="materialFile"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-0 text-xs text-gray-500 dark:text-gray-400">Ukuran file maksimal: 20 MB</p>
                            
                            <!-- File upload progress -->
                            <div id="fileProgressContainer" class="mt-2 hidden">
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span id="fileSelectedName"></span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div id="fileProgressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 sm:w-1/2">
                            <label for="materialImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar (Opsional)</label>
                            <input type="file" name="img" id="materialImage" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-0 text-xs text-gray-500 dark:text-gray-400">Format yang didukung: JPEG, PNG, GIF. Ukuran maksimal: 20 MB</p>
                            
                            <!-- Image upload progress -->
                            <div id="imageProgressContainer" class="mt-2 hidden">
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span id="imageSelectedName"></span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div id="imageProgressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="mb-4 sm:w-full">
                            <label for="materialLink" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tautan Eksternal (Opsional)</label>
                            <input type="url" name="link" id="materialLink" placeholder="https://example.com" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-2 sm:gap-0 sticky bottom-0">
                    <button type="submit" id="addMaterialSubmitBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tambah Materi
                    </button>
                    <button type="button" onclick="closeModal('addMaterialModal')" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Replace your existing scrollbar styling with this subtle dark theme
    const scrollbarStyle = document.createElement('style');
    scrollbarStyle.textContent = `
        /* Subtle dark scrollbar for all scrollable elements */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.6);  /* dark:bg-gray-800 with opacity */
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.8);  /* dark:bg-gray-600 with opacity */
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.9);  /* dark:bg-gray-500 with opacity */
        }
        
        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(75, 85, 99, 0.8) rgba(31, 41, 55, 0.6);
        }
        
        /* Keep main form scrollbar consistent */
        #addMaterialForm {
            scrollbar-width: thin;
            scrollbar-color: rgba(75, 85, 99, 0.8) rgba(31, 41, 55, 0.6);
        }
    `;
    document.head.appendChild(scrollbarStyle);

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
        quillScript.onload = initializeRichTextEditor;
        document.head.appendChild(quillScript);
    } else {
        initializeRichTextEditor();
    }

    let materialQuill;
    
    function initializeRichTextEditor() {
        // Initialize Quill editor with simplified toolbar options (Google Classroom style)
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],                 // basic formatting
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],    // lists
            ['link'],                                        // link
            [{ 'align': [] }],                               // text alignment
            ['clean']                                        // remove formatting
        ];
        
        // Only initialize if the container exists
        const editorContainer = document.getElementById('materialContentEditor');
        if (editorContainer) {
            materialQuill = new Quill('#materialContentEditor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
                placeholder: 'Tambahkan deskripsi atau instruksi...'
            });
            
            // Apply styling (including dark mode if needed)
            applyQuillStyling();
        }
    }

    function applyQuillStyling() {
        const style = document.createElement('style');

        // High contrast styling with dark background and white text
        style.textContent = `
            /* Editor container - reduce overall height */
            .ql-container.ql-snow {
                border: 1px solid #4B5563;
                border-top: 0;
                border-radius: 0 0 4px 4px;
                font-family: inherit;
                background-color: #1F2937 !important;
                min-height: auto !important;
                max-height: 170px !important; /* Slightly smaller height */
            }
            
            /* Editor area with bright white text and REDUCED FIXED HEIGHT */
            .ql-editor {
                height: 160px !important; /* Reduced from 150px */
                max-height: 140px !important;
                min-height: 140px !important;
                font-size: 15px;
                line-height: 1.5;
                padding: 12px 15px;
                color: #FFFFFF !important;
                background-color: #1F2937 !important;
                overflow-y: auto !important;
            }
            
            /* Custom scrollbar for Quill editor - subtle dark theme */
            .ql-editor::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }
            
            .ql-editor::-webkit-scrollbar-track {
                background: rgba(31, 41, 55, 0.6);
                border-radius: 10px;
            }
            
            .ql-editor::-webkit-scrollbar-thumb {
                background: rgba(75, 85, 99, 0.8);
                border-radius: 10px;
            }
            
            .ql-editor::-webkit-scrollbar-thumb:hover {
                background: rgba(107, 114, 128, 0.9);
            }
            
            /* Toolbar styling - make it more compact */
            .ql-toolbar.ql-snow {
                border: 1px solid #4B5563;
                border-radius: 4px 4px 0 0;
                padding: 6px !important; /* Slightly reduced padding */
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
            .ql-editor h4, .ql-editor h5, .ql-editor {
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
            
            /* Dropdown menu styling */
            .ql-snow .ql-picker-options {
                background-color: #374151 !important;
                border-color: #4B5563 !important;
            }
            
            .ql-snow .ql-picker-item {
                color: #FFFFFF !important;
            }
            
            /* Increase spacing after the editor */
            #materialContentEditor {
                margin-bottom: 20px !important;
            }
        `;

        document.head.appendChild(style);
    }
    
    // Handle modal close and open events
    const closeModalBtn = document.querySelector('[onclick="closeModal(\'addMaterialModal\')"]');
    const openModalTriggers = document.querySelectorAll('[onclick*="addMaterialModal.classList.remove(\'hidden\')"]');
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            // Reset Quill content when modal is closed
            if (materialQuill) {
                materialQuill.setText('');
            }
        });
    }
    
    if (openModalTriggers.length > 0) {
        openModalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                // Ensure Quill is re-initialized when modal is opened
                setTimeout(function() {
                    if (!materialQuill) {
                        materialQuill = new Quill('#materialContentEditor', {
                            modules: {
                                toolbar: toolbarOptions
                            },
                            theme: 'snow',
                            placeholder: 'Tambahkan deskripsi atau instruksi...'
                        });
                        
                        if (document.documentElement.classList.contains('dark')) {
                            applyQuillStyling();
                        }
                    }
                }, 100);
            });
        });
    }
    
    const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    const materialFileInput = document.getElementById('materialFile');
    const materialImageInput = document.getElementById('materialImage');
    const addMaterialForm = document.getElementById('addMaterialForm');
    const fileProgressContainer = document.getElementById('fileProgressContainer');
    const fileProgressBar = document.getElementById('fileProgressBar');
    const fileSelectedName = document.getElementById('fileSelectedName');
    const imageProgressContainer = document.getElementById('imageProgressContainer');
    const imageProgressBar = document.getElementById('imageProgressBar');
    const imageSelectedName = document.getElementById('imageSelectedName');
    
    // SweetAlert dark theme configuration
    const sweetAlertDarkTheme = {
        background: '#1F2937', // dark:bg-gray-800
        color: '#F3F4F6', // dark:text-gray-100
        confirmButtonColor: '#3B82F6', // blue-500
        cancelButtonColor: '#4B5563', // gray-600
        customClass: {
            popup: 'dark-theme-modal',
            confirmButton: 'dark-theme-confirm-btn',
            cancelButton: 'dark-theme-cancel-btn',
            title: 'dark-theme-title',
            content: 'dark-theme-content'
        }
    };
    
    // Add CSS to the page for the dark theme
    const style = document.createElement('style');
    style.textContent = `
        .dark-theme-modal {
            background-color: #1F2937 !important;
            color: #F3F4F6 !important;
            border: 1px solid #374151 !important;
        }
        .dark-theme-title, .dark-theme-content {
            color: #F3F4F6 !important;
        }
        .dark-theme-confirm-btn, .dark-theme-cancel-btn {
            color: #F3F4F6 !important;
        }
        .swal2-timer-progress-bar {
            background: #3B82F6 !important;
        }
        .swal2-icon.swal2-error, .swal2-icon.swal2-warning, .swal2-icon.swal2-success {
            border-color: #6B7280 !important;
            color: #F3F4F6 !important;
        }
    `;
    document.head.appendChild(style);
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + " bytes";
        else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
        else return (bytes / 1048576).toFixed(1) + " MB";
    }
    
    // File selection event
    materialFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) {
            fileProgressContainer.classList.add('hidden');
            return;
        }
        
        // Check file size
        if (file.size > MAX_FILE_SIZE) {
            Swal.fire({
                title: 'File Terlalu Besar',
                text: `Ukuran file (${formatFileSize(file.size)}) melebihi batas maksimal 20 MB.`,
                icon: 'error',
                confirmButtonText: 'OK',
                ...sweetAlertDarkTheme
            });
            e.target.value = ''; // Clear the file input
            fileProgressContainer.classList.add('hidden');
            return;
        }
        
        // Show progress container with file info
        fileSelectedName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
        fileProgressContainer.classList.remove('hidden');
        fileProgressBar.style.width = '0%';
    });
    
    // Image selection event
    materialImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) {
            imageProgressContainer.classList.add('hidden');
            return;
        }
        
        // Check file size
        if (file.size > MAX_FILE_SIZE) {
            Swal.fire({
                title: 'Gambar Terlalu Besar',
                text: `Ukuran gambar (${formatFileSize(file.size)}) melebihi batas maksimal 20 MB.`,
                icon: 'error',
                confirmButtonText: 'OK',
                ...sweetAlertDarkTheme
            });
            e.target.value = ''; // Clear the file input
            imageProgressContainer.classList.add('hidden');
            return;
        }
        
        // Check file type
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(file.type)) {
            Swal.fire({
                title: 'Format Tidak Didukung',
                text: 'Format gambar tidak didukung. Gunakan JPEG, PNG, atau GIF.',
                icon: 'error',
                confirmButtonText: 'OK',
                ...sweetAlertDarkTheme
            });
            e.target.value = ''; // Clear the file input
            imageProgressContainer.classList.add('hidden');
            return;
        }
        
        // Show progress container with file info
        imageSelectedName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
        imageProgressContainer.classList.remove('hidden');
        imageProgressBar.style.width = '0%';
    });
    
    // Form submission
    addMaterialForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Add this line to get content from Quill editor before submission
        if (materialQuill) {
            document.getElementById('materialContent').value = materialQuill.root.innerHTML;
        }
        
        // Check if there are files selected and they are within size limits
        const fileValid = !materialFileInput.files[0] || materialFileInput.files[0].size <= MAX_FILE_SIZE;
        const imageValid = !materialImageInput.files[0] || materialImageInput.files[0].size <= MAX_FILE_SIZE;
        
        if (!fileValid || !imageValid) {
            Swal.fire({
                title: 'Ukuran File Melebihi Batas',
                text: 'File atau gambar melebihi ukuran maksimal (20 MB).',
                icon: 'error',
                confirmButtonText: 'OK',
                ...sweetAlertDarkTheme
            });
            return;
        }
        
        // Create FormData object
        const formData = new FormData(this);
        
        // Show loading state with SweetAlert
        Swal.fire({
            title: 'Mengunggah...',
            html: 'Mohon tunggu saat file sedang diunggah',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            ...sweetAlertDarkTheme
        });
        
        // Create and configure the AJAX request
        const xhr = new XMLHttpRequest();
        
        // Upload progress event
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                
                // Update both progress bars (they will show if their containers are visible)
                fileProgressBar.style.width = percentComplete + '%';
                imageProgressBar.style.width = percentComplete + '%';
                
                // Update the SweetAlert loading message
                Swal.update({
                    title: 'Mengunggah...',
                    html: `Proses: ${percentComplete}%`
                });
                
                // Disable submit button during upload
                document.getElementById('addMaterialSubmitBtn').disabled = true;
            }
        });
        
        // Request completed
        xhr.addEventListener('load', function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Success notification
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Materi berhasil ditambahkan.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    ...sweetAlertDarkTheme
                }).then(() => {
                    // Redirect to the returned location or reload page
                    window.location.href = xhr.responseURL || window.location.href;
                });
            } else {
                // Error notification
                let errorMessage = 'Terjadi kesalahan saat mengunggah file.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {}
                
                Swal.fire({
                    title: 'Gagal!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    ...sweetAlertDarkTheme
                });
                
                // Re-enable submit button
                document.getElementById('addMaterialSubmitBtn').disabled = false;
            }
        });
        
        // Request failed
        xhr.addEventListener('error', function() {
            Swal.fire({
                title: 'Kesalahan Jaringan',
                text: 'Network error. Periksa koneksi internet Anda.',
                icon: 'error',
                confirmButtonText: 'OK',
                ...sweetAlertDarkTheme
            });
            document.getElementById('addMaterialSubmitBtn').disabled = false;
        });
        
        // Open and send the request
        xhr.open('POST', addMaterialForm.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
});
</script>