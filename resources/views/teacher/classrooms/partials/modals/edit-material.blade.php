<!-- Edit Material Modal -->
<div class="fixed inset-0 overflow-y-auto hidden" id="editMaterialModal{{ $material->id }}" aria-labelledby="editMaterialModalLabel{{ $material->id }}" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="editMaterialForm{{ $material->id }}" action="{{ route('teacher.materials.update', [$classroom->id, $material->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="editMaterialModalLabel{{ $material->id }}">Edit Materi</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('editMaterialModal{{ $material->id }}').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Hidden fields for removal tracking -->
                    <input type="hidden" name="remove_file" id="remove_file{{ $material->id }}" value="0">
                    <input type="hidden" name="remove_img" id="remove_img{{ $material->id }}" value="0">
                    <input type="hidden" name="remove_link" id="remove_link{{ $material->id }}" value="0">
                    
                    <!-- Preserve the type value as hidden input -->
                    <input type="hidden" name="type" value="{{ $material->type }}">
                    
                    <div class="mb-4">
                        <label for="editMaterialTitle{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                        <input type="text" name="title" id="editMaterialTitle{{ $material->id }}" value="{{ $material->title }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editMaterialContent{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konten</label>
                        <!-- Replace the textarea with a div for Quill -->
                        <div id="editMaterialContentEditor{{ $material->id }}" class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600"></div>
                        <input type="hidden" name="content" id="editMaterialContent{{ $material->id }}" value="{{ $material->content }}">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="editMaterialFile{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran</label>
                            @if($material->file)
                                <div class="mb-2 flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-md p-2">
                                    <a href="{{ asset('storage/' . $material->file) }}" target="_blank" class="flex items-center group">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900 rounded-md mr-3">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate">
                                                @php
                                                    $filename = basename($material->file);
                                                    $displayName = strlen($filename) > 20 ? substr($filename, 0, 17) . '...' : $filename;
                                                @endphp
                                                {{ $displayName }}
                                                <span class="sr-only">{{ $filename }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Klik untuk lihat / Download
                                            </p>
                                        </div>
                                    </a>
                                    <button type="button" 
                                            onclick="document.getElementById('remove_file{{ $material->id }}').value='1'; this.parentElement.classList.add('hidden');"
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="file" id="editMaterialFile{{ $material->id }}"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unggah file baru untuk menggantikan file saat ini. Ukuran maksimal: 20 MB</p>
                            
                            <!-- File upload progress -->
                            <div id="editFileProgressContainer{{ $material->id }}" class="mt-2 hidden">
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span id="editFileSelectedName{{ $material->id }}"></span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div id="editFileProgressBar{{ $material->id }}" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="editMaterialImage{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar</label>
                            @if($material->img)
                                <div class="mb-2 flex items-center justify-between">
                                    <img src="{{ asset('storage/' . $material->img) }}" alt="Current Image" class="h-20 w-auto object-cover rounded">
                                    <button type="button" 
                                            onclick="document.getElementById('remove_img{{ $material->id }}').value='1'; this.parentElement.classList.add('hidden');"
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="img" id="editMaterialImage{{ $material->id }}" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unggah Gambar baru untuk menggantikan file saat ini. Format: JPEG, PNG, GIF. Ukuran maksimal: 20 MB</p>
                            
                            <!-- Image upload progress -->
                            <div id="editImageProgressContainer{{ $material->id }}" class="mt-2 hidden">
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span id="editImageSelectedName{{ $material->id }}"></span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div id="editImageProgressBar{{ $material->id }}" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editMaterialLink{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link Youtube</label>
                        <div class="flex items-center">
                            <input type="url" name="link" id="editMaterialLink{{ $material->id }}" value="{{ $material->link }}" placeholder="https://www.youtube.com/watch?v=example"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($material->link)
                                <button type="button" 
                                        onclick="document.getElementById('remove_link{{ $material->id }}').value='1'; document.getElementById('editMaterialLink{{ $material->id }}').value='';"
                                        class="ml-2 text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Fixed button section -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="editMaterialSubmitBtn{{ $material->id }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="document.getElementById('editMaterialModal{{ $material->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// First, include the SweetAlert2 library
document.addEventListener('DOMContentLoaded', function() {
    // Load SweetAlert2 from CDN if not already loaded
    if (typeof Swal === 'undefined') {
        const sweetAlertScript = document.createElement('script');
        sweetAlertScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        sweetAlertScript.onload = initializeMaterialEdit;
        document.head.appendChild(sweetAlertScript);
    } else {
        initializeMaterialEdit();
    }
    
    function initializeMaterialEdit() {
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
            quillScript.onload = initQuill;
            document.head.appendChild(quillScript);
        } else {
            initQuill();
        }

        function initQuill() {
            const materialId = {{ $material->id }};
            const contentValue = document.getElementById(`editMaterialContent${materialId}`).value;
            
            // Simplified toolbar configuration like Google Classroom
            const toolbarOptions = [
                ['bold', 'italic', 'underline'],                 // basic formatting
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],    // lists
                ['link'],                                        // link
                [{ 'align': [] }],                               // text alignment
                ['clean']                                        // remove formatting
            ];
            
            // Initialize Quill editor
            const editorContainer = document.getElementById(`editMaterialContentEditor${materialId}`);
            if (editorContainer) {
                const quill = new Quill(`#editMaterialContentEditor${materialId}`, {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow',
                    placeholder: 'Tambahkan deskripsi atau instruksi...'
                });
                
                // Set initial content
                quill.root.innerHTML = contentValue;
                
                // Apply styling
                applyQuillStyling();
                
                // Make sure to get content when form is submitted
                const editMaterialForm = document.getElementById(`editMaterialForm${materialId}`);
                if (editMaterialForm) {
                    editMaterialForm.addEventListener('submit', function(e) {
                        // Save Quill content to hidden field before submission
                        const content = quill.root.innerHTML;
                        document.getElementById(`editMaterialContent${materialId}`).value = content;
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
        
        const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
        const materialId = {{ $material->id }};
        
        const editMaterialForm = document.getElementById(`editMaterialForm${materialId}`);
        const editMaterialFileInput = document.getElementById(`editMaterialFile${materialId}`);
        const editMaterialImageInput = document.getElementById(`editMaterialImage${materialId}`);
        const fileProgressContainer = document.getElementById(`editFileProgressContainer${materialId}`);
        const fileProgressBar = document.getElementById(`editFileProgressBar${materialId}`);
        const fileSelectedName = document.getElementById(`editFileSelectedName${materialId}`);
        const imageProgressContainer = document.getElementById(`editImageProgressContainer${materialId}`);
        const imageProgressBar = document.getElementById(`editImageProgressBar${materialId}`);
        const imageSelectedName = document.getElementById(`editImageSelectedName${materialId}`);
        
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
        
        if (!editMaterialForm || !editMaterialFileInput || !editMaterialImageInput) {
            console.error(`Missing required elements for material ID ${materialId}`);
            return;
        }
        
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + " bytes";
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + " KB";
            else return (bytes / 1048576).toFixed(1) + " MB";
        }
        
        // File selection event
        editMaterialFileInput.addEventListener('change', function(e) {
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
        editMaterialImageInput.addEventListener('change', function(e) {
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
        editMaterialForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Replace this line:
            // const quillContent = document.getElementById(`editMaterialContent${materialId}`).value;
            // document.querySelector(`#editMaterialContent${materialId}`).value = quillContent;
            
            // With this:
            const quill = Quill.find(document.getElementById(`editMaterialContentEditor${materialId}`));
            if (quill) {
                document.getElementById(`editMaterialContent${materialId}`).value = quill.root.innerHTML;
            }
            
            // Check if there are files selected and they are within size limits
            const fileValid = !editMaterialFileInput.files[0] || editMaterialFileInput.files[0].size <= MAX_FILE_SIZE;
            const imageValid = !editMaterialImageInput.files[0] || editMaterialImageInput.files[0].size <= MAX_FILE_SIZE;
            
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
                title: 'Memperbarui...',
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
                    
                    // Update file progress bar if visible
                    if (!fileProgressContainer.classList.contains('hidden')) {
                        fileProgressBar.style.width = percentComplete + '%';
                    }
                    
                    // Update image progress bar if visible
                    if (!imageProgressContainer.classList.contains('hidden')) {
                        imageProgressBar.style.width = percentComplete + '%';
                    }
                    
                    // Update the SweetAlert loading message
                    Swal.update({
                        title: 'Memperbarui...',
                        html: `Proses: ${percentComplete}%`
                    });
                    
                    // Disable submit button during upload
                    document.getElementById(`editMaterialSubmitBtn${materialId}`).disabled = true;
                }
            });
            
            // Request completed
            xhr.addEventListener('load', function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // Success notification
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Materi berhasil diperbarui.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        ...sweetAlertDarkTheme
                    }).then(() => {
                        // Redirect to the returned location or reload page
                        window.location.href = xhr.responseURL || window.location.href;
                    });
                } else {
                    // Error notification
                    let errorMessage = 'Terjadi kesalahan saat memperbarui materi.';
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
                    document.getElementById(`editMaterialSubmitBtn${materialId}`).disabled = false;
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
                document.getElementById(`editMaterialSubmitBtn${materialId}`).disabled = false;
            });
            
            // Open and send the request
            xhr.open('POST', editMaterialForm.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    }
});
</script>