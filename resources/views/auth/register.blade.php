<x-guest-layout>
    <!-- SweetAlert CDN - Add to your layout if not already included -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <div class="min-h-screen flex items-center justify-center px-2 sm:px-4 py-2 sm:py-4">
        <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 backdrop-blur-xl rounded-[1rem] sm:rounded-[1.5rem] border border-white/10 overflow-hidden shadow-2xl p-3 sm:p-4 w-full max-w-4xl mx-auto">
            <div class="flex justify-center items-center mb-2 sm:mb-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/Lambang_Kabupaten_Katingan.png" alt="Logo" class="h-8 sm:h-10 w-auto mr-2 animate-pulse-subtle">
                <span class="text-lg sm:text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-300">Gen-IT</span>
            </div>
            
            <h2 class="text-base sm:text-lg font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 mb-2 sm:mb-3 text-center">{{ __('Buat Akun Anda') }}</h2>
            
            <form id="registerForm" method="POST" action="{{ route('register') }}" class="max-h-[70vh] sm:max-h-none overflow-y-auto pb-1 px-1">
                @csrf

                <div class="grid grid-cols-1 gap-2 sm:gap-3">
                    <!-- Username -->
                    <div class="transform transition-all duration-300 hover:translate-x-1">
                        <x-input-label for="user_name" :value="__('Username')" class="text-gray-300 mb-1 text-xs" />
                        <x-text-input id="user_name" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="text" name="user_name" :value="old('user_name')" required autofocus autocomplete="username" placeholder="Masukkan username" />
                        <x-input-error :messages="$errors->get('user_name')" class="mt-1 text-xs" />
                    </div>

                    <!-- Name Fields (Responsive row/column) -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- First Name -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <x-input-label for="first_name" :value="__('Nama Depan')" class="text-gray-300 mb-1 text-xs" />
                            <x-text-input id="first_name" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="text" name="first_name" :value="old('first_name')" autocomplete="given-name" placeholder="Nama depan" />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-xs" />
                        </div>

                        <!-- Last Name -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <x-input-label for="last_name" :value="__('Nama Belakang')" class="text-gray-300 mb-1 text-xs" />
                            <x-text-input id="last_name" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="text" name="last_name" :value="old('last_name')" autocomplete="family-name" placeholder="Nama belakang" />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-xs" />
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="transform transition-all duration-300 hover:translate-x-1">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-300 mb-1 text-xs" />
                        <x-text-input id="email" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="email@contoh.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <!-- Phone Number with Country Code -->
                    <div class="transform transition-all duration-300 hover:translate-x-1">
                        <x-input-label for="phone" :value="__('Nomor Handphone')" class="text-gray-300 mb-1 text-xs" />
                        <div class="flex">
                            <!-- Country Code Selector -->
                            <select name="phone_country_code" id="phone_country_code" class="bg-gray-800/50 border-gray-700/30 rounded-l-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-2 text-sm shadow-inner w-20">
                                <option value="+62" {{ old('phone_country_code') == '+62' ? 'selected' : '' }}>+62</option>
                                <option value="+1" {{ old('phone_country_code') == '+1' ? 'selected' : '' }}>+1</option>
                                <option value="+44" {{ old('phone_country_code') == '+44' ? 'selected' : '' }}>+44</option>
                                <option value="+81" {{ old('phone_country_code') == '+81' ? 'selected' : '' }}>+81</option>
                                <option value="+86" {{ old('phone_country_code') == '+86' ? 'selected' : '' }}>+86</option>
                                <option value="+91" {{ old('phone_country_code') == '+91' ? 'selected' : '' }}>+91</option>
                            </select>
                            <!-- Phone Number Input -->
                            <x-text-input id="phone" class="block w-full bg-gray-800/50 border-gray-700/30 border-l-0 rounded-r-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="text" name="phone_number" :value="old('phone_number')" autocomplete="tel" placeholder="8123456789" />
                        </div>
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-1 text-xs" />
                    </div>

                    <!-- Birthdate -->
                    <div class="transform transition-all duration-300 hover:translate-x-1">
                        <x-input-label for="birthdate" :value="__('Tanggal Lahir')" class="text-gray-300 mb-1 text-xs" />
                        <x-text-input id="birthdate" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="date" name="birthdate" :value="old('birthdate')" required />
                        <x-input-error :messages="$errors->get('birthdate')" class="mt-1 text-xs" />
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Password -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <x-input-label for="password" :value="__('Kata Sandi')" class="text-gray-300 mb-1 text-xs" />
                            <x-text-input id="password" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="transform transition-all duration-300 hover:translate-x-1">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi')" class="text-gray-300 mb-1 text-xs" />
                            <x-text-input id="password_confirmation" class="block w-full bg-gray-800/50 border-gray-700/30 rounded-lg text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50 transition-all py-1.5 px-3 text-sm shadow-inner" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 space-x-2">
                    <a class="text-xs text-blue-400 hover:text-blue-300 font-medium transition-colors hover:underline" href="{{ route('login') }}">
                        {{ __('Sudah terdaftar?') }}
                    </a>

                    <x-primary-button id="registerBtn" class="px-4 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:ring-2 focus:ring-blue-300/50 text-white text-sm font-medium rounded-lg transition-all shadow-md shadow-blue-600/20 hover:shadow-blue-600/30 transform hover:scale-105 duration-300">
                        {{ __('Daftar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Updated styles for better mobile responsiveness -->
    <style>
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 3s infinite;
        }
        
        /* More aggressive mobile optimizations */
        @media (max-width: 640px) {
            input, select, button {
                font-size: 14px !important; /* Prevent zoom on focus in iOS */
                height: auto !important;
                min-height: 36px !important;
            }
            
            /* Label size adjustments for mobile */
            label {
                font-size: 12px !important;
                margin-bottom: 2px !important;
            }
            
            /* Compact vertical spacing for mobile */
            .gap-2 {
                gap: 0.375rem !important;
            }
            
            /* Improve scrolling on mobile */
            #registerForm {
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                max-height: 65vh !important;
            }
            
            #registerForm::-webkit-scrollbar {
                width: 3px;
            }
            
            #registerForm::-webkit-scrollbar-thumb {
                background-color: rgba(156, 163, 175, 0.5);
                border-radius: 1.5px;
            }
        }
    </style>

    <!-- Keep the JavaScript with minor adjustments for the new element IDs/classes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            
            // Add smooth reveal animation to form elements
            const formElements = document.querySelectorAll('input, select, button');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)'; // Reduced animation distance
                element.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 50 + (index * 30)); // Faster animation timing
            });
            
            // SweetAlert dark theme configuration
            const sweetAlertDarkTheme = {
                background: '#1F2937',
                color: '#F3F4F6',
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#4B5563',
                customClass: {
                    popup: 'dark-theme-modal',
                    confirmButton: 'dark-theme-confirm-btn',
                    cancelButton: 'dark-theme-cancel-btn',
                    title: 'dark-theme-title',
                    content: 'dark-theme-content'
                }
            };
            
            // Add CSS to the page for the dark theme with more mobile optimizations
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
                .swal2-icon.swal2-error, .swal2-icon.swal2-warning, .swal2-icon.swal2-success, .swal2-icon.swal2-info, .swal2-icon.swal2-question {
                    border-color: #6B7280 !important;
                    color: #F3F4F6 !important;
                }
                
                /* More compact SweetAlert for mobile */
                @media (max-width: 640px) {
                    .swal2-popup {
                        width: 85% !important;
                        font-size: 0.85em !important;
                        padding: 0.75em !important;
                    }
                    .swal2-title {
                        font-size: 1.2em !important;
                        padding: 0.5em 0 !important;
                    }
                    .swal2-content {
                        font-size: 0.9em !important;
                    }
                    .swal2-actions {
                        margin-top: 0.75em !important;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Keep the form submission logic the same
            registerBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Basic validation
                const username = document.getElementById('user_name').value;
                const email = document.getElementById('email').value;
                
                if (!username || !email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon isi semua kolom yang diperlukan!',
                        ...sweetAlertDarkTheme,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                    return;
                }
                
                // Show confirmation dialog
                Swal.fire({
                    title: 'Konfirmasi Pendaftaran',
                    text: 'Apakah data anda sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Daftar Kan!',
                    cancelButtonText: 'Batal',
                    ...sweetAlertDarkTheme,
                    showClass: {
                        popup: 'animate__animated animate__fadeIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Format phone number
                        const countryCode = document.getElementById('phone_country_code').value;
                        const phoneNumber = document.getElementById('phone').value;
                        
                        // Create hidden input for combined phone
                        const hiddenPhone = document.createElement('input');
                        hiddenPhone.type = 'hidden';
                        hiddenPhone.name = 'phone';
                        hiddenPhone.value = countryCode + phoneNumber;
                        registerForm.appendChild(hiddenPhone);
                        
                        // Submit the form
                        registerForm.submit();
                        
                        // Show processing message
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Membuat akun anda',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            ...sweetAlertDarkTheme,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-guest-layout>