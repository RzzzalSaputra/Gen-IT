<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('New Message') }}
            </h2>
            <a href="{{ route('contacts.index') }}" class="text-sm text-blue-500 hover:text-blue-400 transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Messages
            </a>
        </div>
    </x-slot>

    <!-- SweetAlert Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-100 mb-6">
                        Send Message to Admin
                    </h3>
                    
                    @if(session('success'))
                        <div class="mb-6 bg-green-900/30 text-green-300 p-4 rounded-lg border border-green-500/30">
                            {{ session('success') }}
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your message has been sent successfully. We will respond within 24-48 hours.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3b82f6',
                                    background: '#1f2937',
                                    color: '#fff'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('contacts.index') }}";
                                    }
                                });
                            });
                        </script>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-900/30 text-red-300 p-4 rounded-lg border border-red-500/30">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form id="contactForm" action="{{ route('contacts.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Your Message</label>
                            <textarea
                            id="message"
                            name="message"
                            rows="8"
                            class="w-full px-4 py-3 bg-gray-800/50 backdrop-blur-sm border rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200 @error('message') border-red-500/50 @else border-gray-700/50 @enderror"
                            placeholder="Write your message to the admin here..."
                            required
                        >{{ old('message') }}</textarea>
                            
                            @error('message')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" id="submitBtn" class="inline-flex items-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-8 bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-gray-700/30">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-gray-300 font-medium text-sm">About Contacting Admin</h4>
                        <p class="text-gray-400 text-xs mt-1">
                            Your message will be sent to the admin team and you will receive a response within 24-48 hours.
                            You can view all your messages and responses in the "Contact Us" section.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Confirmation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');
            const contactForm = document.getElementById('contactForm');
            
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const messageField = document.getElementById('message');
                
                if (!messageField.value.trim()) {
                    Swal.fire({
                        title: 'Empty Message',
                        text: 'Please write a message before submitting.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6',
                        background: '#1f2937',
                        color: '#fff'
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Send Message?',
                    text: 'Are you sure you want to send this message to the admin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, send it!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6b7280',
                    background: '#1f2937',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Sending...',
                            text: 'Please wait while we send your message.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: '#1f2937',
                            color: '#fff'
                        });
                        
                        // Submit the form
                        contactForm.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>