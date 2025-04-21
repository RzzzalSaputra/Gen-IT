<x-guest-layout>
    <div class="bg-gradient-to-br from-gray-900/80 to-gray-800/80 backdrop-blur-xl rounded-[2rem] border border-white/10 overflow-hidden shadow-2xl p-6 max-w-md mx-auto">
        <div class="flex justify-center items-center mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/Lambang_Kabupaten_Katingan.png" alt="Logo" class="h-16 w-auto mr-3 animate-pulse-subtle">
            <span class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-300 tracking-wide">Gen-IT</span>
        </div>
        
        <h2 class="text-xl font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 mb-6 text-center">{{ __('Welcome Back') }}</h2>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-800/50 border-gray-700/30 rounded-xl text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-gray-300" />

                <x-text-input id="password" class="block mt-1 w-full bg-gray-800/50 border-gray-700/30 rounded-xl text-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500/50"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded bg-gray-800 border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                <div>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-400 hover:text-blue-300 font-medium transition-colors hover:underline transform hover:translate-x-1 transition-transform duration-300" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                    
                    <div class="mt-2">
                        <a class="text-sm text-gray-400 hover:text-gray-300" href="{{ route('register') }}">
                            {{ __("Don't have an account?") }} <span class="text-blue-400 hover:text-blue-300 hover:underline">{{ __('Register here') }}</span>
                        </a>
                    </div>
                </div>

                <x-primary-button class="ml-4 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:ring-4 focus:ring-blue-300/50 text-white font-medium rounded-xl transition-all shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transform hover:scale-105 duration-300">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Add animation keyframes -->
    <style>
        @keyframes pulse-subtle {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 3s infinite;
        }
    </style>
</x-guest-layout>
