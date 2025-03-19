<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Submission') }}
            </h2>
            <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Submissions
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/60 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6 sm:p-8">
                    <h3 class="text-2xl font-bold text-gray-100 mb-6">
                        Submit New Content
                    </h3>

                    @if(session('error'))
                        <div class="mb-6 bg-red-900/30 text-red-300 p-4 rounded-lg border border-red-500/30">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="submissionForm" method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="w-full rounded-lg bg-gray-900/50 border border-gray-700 text-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:outline-none transition-colors duration-200"
                                placeholder="Enter submission title">
                            @error('title')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Content</label>
                            <textarea name="content" id="content" rows="6" required
                                class="w-full rounded-lg bg-gray-900/50 border border-gray-700 text-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:outline-none transition-colors duration-200"
                                placeholder="Enter submission content">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submission Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300 mb-1">Submission Type</label>
                            <select name="type" id="type" required
                                class="w-full rounded-lg bg-gray-900/50 border border-gray-700 text-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:outline-none transition-colors duration-200">
                                <option value="">Select a type</option>
                                <option value="1" data-type-value="document">Document</option>
                                <option value="2" data-type-value="image">Image</option>
                                <option value="3" data-type-value="video">Video</option>
                                <option value="5" data-type-value="link">Link</option>
                            </select>
                            @error('type')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden field to track real submission_type -->
                        <input type="hidden" name="submission_type" id="submission_type" value="">

                        <!-- Upload File -->
                        <div id="fileUploadField" class="form-field" style="display: none;">
                            <label for="file" class="block text-sm font-medium text-gray-300 mb-1">Upload Document</label>
                            <div class="mt-1 flex items-center">
                                <label class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-900/50 border border-dashed border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                                    <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span id="file-name" class="text-gray-400">Choose a file (PDF, DOC, DOCX)</span>
                                    <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                </label>
                            </div>
                            @error('file')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Image -->
                        <div id="imageUploadField" class="form-field" style="display: none;">
                            <label for="img" class="block text-sm font-medium text-gray-300 mb-1">Upload Image</label>
                            <div class="mt-1 flex items-center">
                                <label class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-900/50 border border-dashed border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                                    <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span id="img-name" class="text-gray-400">Choose an image (JPEG, PNG, JPG, GIF)</span>
                                    <input id="img" name="img" type="file" class="sr-only" accept="image/jpeg,image/png,image/gif">
                                </label>
                            </div>
                            @error('img')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div id="linkField" class="form-field" style="display: none;">
                            <label for="link" class="block text-sm font-medium text-gray-300 mb-1">External Link</label>
                            <input type="url" name="link" id="link" value="{{ old('link') }}"
                                class="w-full rounded-lg bg-gray-900/50 border border-gray-700 text-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring focus:ring-blue-500/30 focus:outline-none transition-colors duration-200"
                                placeholder="https://example.com">
                            @error('link')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" id="submitBtn" class="w-full md:w-auto px-6 py-3 bg-blue-600 rounded-xl text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors duration-200">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sweet Alert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define field mapping - which fields show for which submission type
        const fieldMapping = {
            'document': ['fileUploadField'],
            'image': ['imageUploadField'],
            'video': ['linkField'],
            'link': ['linkField']
        };
        
        const typeSelect = document.getElementById('type');
        const allFormFields = document.querySelectorAll('.form-field');
        const submitBtn = document.getElementById('submitBtn');
        
        if (!submitBtn.textContent.trim()) {
            submitBtn.textContent = 'Submit';
        }

        // Function to update visible form fields based on selected type
        function updateVisibleFields() {
            // First hide all fields
            allFormFields.forEach(field => {
                field.style.display = 'none';
            });
            
            // Show fields based on selected type
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const typeValue = selectedOption.getAttribute('data-type-value');
                console.log('Selected type:', typeValue);
                
                if (typeValue && fieldMapping[typeValue]) {
                    fieldMapping[typeValue].forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) {
                            field.style.display = 'block';
                            console.log('Showing field:', fieldId);
                        } else {
                            console.error('Field not found:', fieldId);
                        }
                    });
                }
            }
        }
        
        // Add event listener to type select
        typeSelect.addEventListener('change', updateVisibleFields);
        
        // Handle file upload UI
        const fileInput = document.getElementById('file');
        const fileNameLabel = document.getElementById('file-name');
        if (fileInput && fileNameLabel) {
            fileInput.addEventListener('change', function() {
                fileNameLabel.textContent = this.files.length > 0 
                    ? this.files[0].name 
                    : 'Choose a file (PDF, DOC, DOCX)';
                    
                // File validation
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if (!validTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Please upload a PDF, DOC, or DOCX file only.',
                            background: '#1f2937',
                            color: '#f3f4f6',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        fileNameLabel.textContent = 'Choose a file (PDF, DOC, DOCX)';
                    } else if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'Document file size should not exceed 5MB.',
                            background: '#1f2937',
                            color: '#f3f4f6',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        fileNameLabel.textContent = 'Choose a file (PDF, DOC, DOCX)';
                    }
                }
            });
        }
        
        // Handle image upload UI
        const imgInput = document.getElementById('img');
        const imgNameLabel = document.getElementById('img-name');
        const imgPreviewContainer = document.createElement('div');
        imgPreviewContainer.className = 'mt-3';
        
        if (imgInput && imgNameLabel) {
            // Insert the preview container after the file input
            if (imgInput.parentNode && imgInput.parentNode.parentNode) {
                imgInput.parentNode.parentNode.appendChild(imgPreviewContainer);
            }
            
            imgInput.addEventListener('change', function() {
                imgNameLabel.textContent = this.files.length > 0 
                    ? this.files[0].name 
                    : 'Choose an image (JPEG, PNG, JPG, GIF)';
                    
                // Image validation and preview
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if (!validTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Image Type',
                            text: 'Please upload a JPEG, PNG, or GIF image only.',
                            background: '#1f2937',
                            color: '#f3f4f6',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        imgNameLabel.textContent = 'Choose an image (JPEG, PNG, JPG, GIF)';
                        imgPreviewContainer.innerHTML = '';
                    } else if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Image Too Large',
                            text: 'Image file size should not exceed 2MB.',
                            background: '#1f2937',
                            color: '#f3f4f6',
                            confirmButtonColor: '#3b82f6'
                        });
                        this.value = '';
                        imgNameLabel.textContent = 'Choose an image (JPEG, PNG, JPG, GIF)';
                        imgPreviewContainer.innerHTML = '';
                    } else {
                        // Show image preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imgPreviewContainer.innerHTML = `
                                <div class="relative mt-4 rounded-lg overflow-hidden border border-gray-700 shadow-lg">
                                    <img src="${e.target.result}" alt="Image Preview" class="w-full max-h-64 object-contain bg-gray-900/50" />
                                    <button type="button" class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-600 text-white rounded-full p-1 transition-colors duration-200" onclick="removeImagePreview()">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                } else {
                    imgPreviewContainer.innerHTML = '';
                }
            });
        }
        
        // Function to remove image preview
        window.removeImagePreview = function() {
            imgInput.value = '';
            imgNameLabel.textContent = 'Choose an image (JPEG, PNG, JPG, GIF)';
            imgPreviewContainer.innerHTML = '';
        };
        
        // Form validation
        const form = document.getElementById('submissionForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default submission
                
                let valid = true;
                let errorMessage = '';
                
                // Check title
                const title = document.getElementById('title').value.trim();
                if (!title) {
                    valid = false;
                    errorMessage = 'Title is required';
                }
                
                // Check content
                const content = document.getElementById('content').value.trim();
                if (!content) {
                    valid = false;
                    errorMessage = errorMessage || 'Content is required';
                }
                
                // Check type
                if (!typeSelect.value) {
                    valid = false;
                    errorMessage = errorMessage || 'Please select a submission type';
                }
                
                // Check visible field requirements based on type
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const typeValue = selectedOption.getAttribute('data-type-value');
                    
                    // Link validation for video and link types
                    if ((typeValue === 'video' || typeValue === 'link') && 
                        document.getElementById('linkField').style.display !== 'none') {
                        const link = document.getElementById('link').value.trim();
                        if (!link) {
                            valid = false;
                            errorMessage = errorMessage || 'Please enter a valid link';
                        } else {
                            try {
                                new URL(link);
                            } catch (err) {
                                valid = false;
                                errorMessage = errorMessage || 'Please enter a valid URL including http:// or https://';
                            }
                        }
                    }
                    
                    // File validation
                    if (typeValue === 'document' && document.getElementById('file').files.length === 0) {
                        valid = false;
                        errorMessage = errorMessage || 'Please upload a document';
                    }
                    
                    // Image validation
                    if (typeValue === 'image' && document.getElementById('img').files.length === 0) {
                        valid = false;
                        errorMessage = errorMessage || 'Please upload an image';
                    }
                }
                
                if (!valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessage,
                        background: '#1f2937',
                        color: '#f3f4f6',
                        confirmButtonColor: '#3b82f6'
                    });
                } else {
                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Submit Confirmation',
                        text: 'Are you sure you want to submit this content?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, submit it!',
                        cancelButtonText: 'Cancel',
                        background: '#1f2937',
                        color: '#f3f4f6',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#4b5563',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading while form submits
                            Swal.fire({
                                title: 'Submitting...',
                                html: 'Please wait while we process your submission.',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                background: '#1f2937',
                                color: '#f3f4f6'
                            });
                            
                            // Set submission type before submitting
                            setSubmissionType();
                            form.submit();
                        }
                    });
                }
            });
        }
        
        // Initialize form fields based on any existing selection
        updateVisibleFields();
        
        // Function to determine and set submission type
        function setSubmissionType() {
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const submissionTypeField = document.getElementById('submission_type');
            
            if (selectedOption && selectedOption.value) {
                const typeValue = selectedOption.getAttribute('data-type-value');
                
                // Set submission_type based on selection according to the allowed values
                if (typeValue === 'document' || typeValue === 'image') {
                    submissionTypeField.value = 'file'; // For document or image uploads
                } else if (typeValue === 'video') {
                    submissionTypeField.value = 'video'; // For video links
                } else {
                    submissionTypeField.value = 'text'; // For external links and fallback
                }
            } else {
                // Default to text if no selection
                submissionTypeField.value = 'text';
            }
            console.log('Set submission_type to:', submissionTypeField.value);
        }
        
        // Update submission type when type changes
        typeSelect.addEventListener('change', function() {
            updateVisibleFields();
            setSubmissionType();
        });
        
        // Initialize submission type if a type is already selected
        if (typeSelect.value) {
            setSubmissionType();
        }
        
        console.log('Form setup complete');
    });
</script>
</x-app-layout>