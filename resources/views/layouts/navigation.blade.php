<nav x-data="{ open: false }" class="bg-black/50 backdrop-blur-lg fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('https://upload.wikimedia.org/wikipedia/commons/4/4b/Lambang_Kabupaten_Katingan.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                        <span class="text-white font-bold text-xl">
                            <span class="text-blue-500">Gen</span>-IT
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-white hover:text-blue-300' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                        {{ __('Home') }}
                    </a>
                    
                    <!-- Learning Resources Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="{{ request()->routeIs('materials.index') || request()->routeIs('articles.index') || request()->routeIs('schools.index') ? 'text-blue-400' : 'text-white hover:text-blue-300' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            {{ __('Learning') }}
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <a href="{{ url('/materials=text') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Materials') }}
                            </a>
                            <a href="{{ route('articles.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Articles') }}
                            </a>
                            <a href="{{ route('schools.index', ['active' => 1]) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Schools') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Career Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="{{ request()->routeIs('companies.index') || request()->routeIs('jobs.index') ? 'text-blue-400' : 'text-white hover:text-blue-300' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            {{ __('Career') }}
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <a href="{{ route('companies.index', ['active' => 1]) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Companies') }}
                            </a>
                            <a href="{{ route('jobs.index', ['active' => 1]) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Jobs') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Community Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="{{ request()->routeIs('gallery.index') || request()->is('posts*') || request()->routeIs('contacts.index') ? 'text-blue-400' : 'text-white hover:text-blue-300' }} px-3 py-2 text-sm font-medium transition-colors duration-200 flex items-center">
                            {{ __('Community') }}
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <a href="{{ url('/gallery?type=7') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Gallery') }}
                            </a>
                            </a>
                            <a href="{{ url('/posts') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Posts') }}
                            </a>
                            <a href="{{ url('/index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Contact Us') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <!-- Submission Link (Only visible when logged in) -->
                <a href="{{ url('/submission') }}" class="{{ request()->routeIs('submissions.index') ? 'text-blue-400' : 'text-white hover:text-blue-300' }} px-3 py-2 text-sm font-medium transition-colors duration-200 mr-4">
                    {{ __('Submissions') }}
                </a>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:text-blue-300 focus:outline-none transition duration-150 ease-in-out">
                        <div>{{ Auth::user()->name }}</div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                        style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                            {{ __('Profile') }}
                        </a>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-white hover:text-blue-300 transition-colors">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">Register</a>
                    @endif
                </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-gray-800/90 backdrop-blur-lg">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 text-base font-medium">
                {{ __('Home') }}
            </a>
            
            <!-- Learning Section for Mobile -->
            <div x-data="{ openLearning: false }">
                <button @click="openLearning = !openLearning" class="text-gray-300 hover:bg-gray-700 hover:text-white w-full text-left flex justify-between items-center px-3 py-2 text-base font-medium">
                    {{ __('Learning') }}
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openLearning" class="pl-4 bg-gray-700/50">
                    <a href="{{ url('/materials=text') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Materials') }}
                    </a>
                    <a href="{{ route('articles.index') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Articles') }}
                    </a>
                    <a href="{{ route('schools.index', ['active' => 1]) }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Schools') }}
                    </a>
                </div>
            </div>
            
            <!-- Career Section for Mobile -->
            <div x-data="{ openCareer: false }">
                <button @click="openCareer = !openCareer" class="text-gray-300 hover:bg-gray-700 hover:text-white w-full text-left flex justify-between items-center px-3 py-2 text-base font-medium">
                    {{ __('Career') }}
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openCareer" class="pl-4 bg-gray-700/50">
                    <a href="{{ route('companies.index', ['active' => 1]) }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Companies') }}
                    </a>
                    <a href="{{ route('jobs.index', ['active' => 1]) }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Jobs') }}
                    </a>
                </div>
            </div>
            
            <!-- Community Section for Mobile -->
            <div x-data="{ openCommunity: false }">
                <button @click="openCommunity = !openCommunity" class="text-gray-300 hover:bg-gray-700 hover:text-white w-full text-left flex justify-between items-center px-3 py-2 text-base font-medium">
                    {{ __('Community') }}
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="openCommunity" class="pl-4 bg-gray-700/50">
                    <a href="{{ url('/gallery?type=7') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Gallery') }}
                    </a>
                    <a href="{{ route('gallery.index', ['type' => 8]) }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Videos') }}
                    </a>
                    <a href="{{ url('/posts') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Posts') }}
                    </a>
                    <a href="{{ url('/index') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Contact Us') }}
                    </a>
                </div>
            </div>
            
            <!-- Submissions link (only for authenticated users) -->
            @auth
            <a href="{{ url('/submission') }}" class="{{ request()->routeIs('submissions.index') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block px-3 py-2 text-base font-medium">
                {{ __('Submissions') }}
            </a>
            @endauth
        </div>
        
        @auth
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <!-- User avatar placeholder -->
                    <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-3 border-t border-gray-700 px-5 space-y-3">
            <a href="{{ route('login') }}" class="block text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2">
                {{ __('Log In') }}
            </a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block text-base font-medium text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md">
                    {{ __('Register') }}
                </a>
            @endif
        </div>
        @endauth
    </div>
</nav>
