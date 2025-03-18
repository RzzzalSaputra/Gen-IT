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
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                                        {{ ucfirst($type->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload File -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-300 mb-1">Upload Document (Optional)</label>
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
                        <div>
                            <label for="img" class="block text-sm font-medium text-gray-300 mb-1">Upload Image (Optional)</label>
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
                        <div>
                            <label for="link" class="block text-sm font-medium text-gray-300 mb-1">External Link (Optional)</label>
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
        // File input change handlers
        document.getElementById('file').onchange = function() {
            document.getElementById('file-name').textContent = this.files.length > 0 ? this.files[0].name : 'Choose a file (PDF, DOC, DOCX)';
            
            // Check file type and size
            if (this.files.length > 0) {
                const file = this.files[0];
                const validTypes = ['.pdf', '.doc', '.docx', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const isValidType = validTypes.some(type => {
                    if (type.startsWith('.')) {
                        return file.name.toLowerCase().endsWith(type);
                    }
                    return file.type === type;
                });
                
                if (!isValidType) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload a PDF, DOC, or DOCX file only.',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                    document.getElementById('file-name').textContent = 'Choose a file (PDF, DOC, DOCX)';
                    return;
                }
                
                // Check file size (max 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Document file size should not exceed 5MB.',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                    document.getElementById('file-name').textContent = 'Choose a file (PDF, DOC, DOCX)';
                }
            }
        };

        document.getElementById('img').onchange = function() {
            document.getElementById('img-name').textContent = this.files.length > 0 ? this.files[0].name : 'Choose an image (JPEG, PNG, JPG, GIF)';
            
            // Check image type and size
            if (this.files.length > 0) {
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', '.jpg', '.jpeg', '.png', '.gif'];
                const isValidType = validTypes.some(type => {
                    if (type.startsWith('.')) {
                        return file.name.toLowerCase().endsWith(type);
                    }
                    return file.type === type;
                });
                
                if (!isValidType) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Image Type',
                        text: 'Please upload a JPEG, PNG, or GIF image only.',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                    document.getElementById('img-name').textContent = 'Choose an image (JPEG, PNG, JPG, GIF)';
                    return;
                }
                
                // Check image size (max 2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Image Too Large',
                        text: 'Image file size should not exceed 2MB.',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                    document.getElementById('img-name').textContent = 'Choose an image (JPEG, PNG, JPG, GIF)';
                }
            }
        };

        // Form submission validation
        document.getElementById('submissionForm').addEventListener('submit', function(event) {
            let isValid = true;
            let errorMessage = '';
            
            // Validate title
            const title = document.getElementById('title').value.trim();
            if (!title) {
                isValid = false;
                errorMessage = 'Please enter a title for your submission.';
            }
            
            // Validate content
            const content = document.getElementById('content').value.trim();
            if (!content) {
                isValid = false;
                errorMessage = errorMessage ? errorMessage : 'Please enter content for your submission.';
            } else if (content.length < 10) {
                isValid = false;
                errorMessage = 'Content is too short. Please provide more details.';
            }
            
            // Validate submission type
            const type = document.getElementById('type').value;
            if (!type) {
                isValid = false;
                errorMessage = errorMessage ? errorMessage : 'Please select a submission type.';
            }
            
            // Validate URL format if provided
            const link = document.getElementById('link').value.trim();
            if (link && !isValidUrl(link)) {
                isValid = false;
                errorMessage = 'Please enter a valid URL starting with http:// or https://';
            }
            
            // Show error and prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: errorMessage,
                    confirmButtonColor: '#3085d6'
                });
            } else {
                // Show loading state
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your submission',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
        
        // URL validation helper function
        function isValidUrl(url) {
            try {
                const parsedUrl = new URL(url);
                return parsedUrl.protocol === 'http:' || parsedUrl.protocol === 'https:';
            } catch (error) {
                return false;
            }
        }
    </script>
</x-app-layout>