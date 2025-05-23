<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Job Listings') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $jobs->total() }} {{ Str::plural('job', $jobs->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Filter Tabs with preserved filters -->
            <div class="mb-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                <ul class="flex flex-wrap justify-center gap-2 text-sm font-medium">
                    <li>
                        <a href="{{ route('jobs.index', request()->except('type', 'page')) }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ !request()->has('type') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Daftar Semua Pekerjaan
                        </a>
                    </li>
                    @foreach($jobTypes as $type)
                    <li>
                        <a href="{{ route('jobs.index', array_merge(request()->except(['type', 'page']), ['type' => $type->id])) }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->input('type') == $type->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $type->value }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Search form with preserved filters -->
            <div class="mb-8">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Preserve existing type filter when using the search form -->
                    @if(request()->has('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    
                    <div class="relative flex-1 mb-3 sm:mb-0">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" 
                            name="_search" 
                            value="{{ request('_search') }}" 
                            class="w-full pl-12 pr-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                            placeholder="Cari daftar pekerjaan ...">
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3 sm:mb-0">
                        <div class="relative">
                            <select name="province" class="w-full bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>
                                        {{ $province }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <select name="city" class="w-full bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none">
                                <option value="">Semua Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    
                        <div class="relative">
                            <select name="experience" class="w-full bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none">
                                <option value="">Semua Pengalaman</option>
                                @foreach($experienceLevels as $level)
                                    <option value="{{ $level->id }}" {{ request('experience') == $level->id ? 'selected' : '' }}>
                                        {{ $level->value }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                </form>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($jobs->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">Tidak ada Pekerjaan yang ditemukan</h3>
                            <p class="text-gray-400">Daftar pekerjaan akan muncul di sini setelah ditambahkan ke sistem.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($jobs as $job)
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    <div class="p-6">
                                        <div class="mb-4 flex items-center">
                                            @if($job->company && $job->company->logo)
                                                <img src="{{ $job->company->logo }}" alt="{{ $job->company->name }}" class="w-12 h-12 object-cover rounded-lg mr-4">
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-600/20 to-purple-600/20 rounded-lg flex items-center justify-center mr-4">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-100 group-hover:text-blue-400 line-clamp-2 transition-colors duration-200">
                                                    {{ $job->title }}
                                                </h3>
                                                <p class="text-sm text-gray-400">
                                                    {{ $job->company ? $job->company->name : 'Unknown Company' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-400 mb-4 line-clamp-3">
                                            {{ strip_tags($job->description) }}
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($job->jobType)
                                                <div class="inline-flex items-center px-2 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-xs text-blue-300 border border-blue-500/30">
                                                    {{ $job->jobType->value }}
                                                </div>
                                            @endif

                                            @if($job->experienceLevel)
                                                <div class="inline-flex items-center px-2 py-1 bg-purple-900/30 backdrop-blur-sm rounded-lg text-xs text-purple-300 border border-purple-500/30">
                                                    {{ $job->experienceLevel->value }}
                                                </div>
                                            @endif

                                            <div class="inline-flex items-center px-2 py-1 bg-green-900/30 backdrop-blur-sm rounded-lg text-xs text-green-300 border border-green-500/30">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ number_format($job->salary_range, 0) }} IDR
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-6">
                                            <div class="flex items-center text-sm text-gray-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $job->created_at->locale('id')->diffForHumans() }}
                                            </div>
                                            
                                            <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $jobs->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Handle text overflow in dropdowns */
        select option {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create a mapping of provinces to cities
            const cityProvinceMap = @json($cityProvinceMap);
            
            // Get all cities for the "All Provinces" option
            const allCities = @json($cities);
            
            // Get the form elements
            const filterForm = document.querySelector('form[action*="jobs"]');
            const provinceSelect = document.querySelector('select[name="province"]');
            const citySelect = document.querySelector('select[name="city"]');
            
            // Store the currently selected city value
            let currentCityValue = citySelect.value;
            
            // Function to update cities based on selected province
            function updateCities() {
                // Clear current options except the first one ("All Cities")
                while (citySelect.options.length > 1) {
                    citySelect.remove(1);
                }
                
                // Get selected province
                const selectedProvince = provinceSelect.value;
                
                // Determine which cities to show
                let citiesToShow = [];
                if (selectedProvince === '') {
                    // If "All Provinces" is selected, show all cities
                    citiesToShow = allCities;
                } else if (cityProvinceMap[selectedProvince]) {
                    // If a specific province is selected, show only its cities
                    citiesToShow = cityProvinceMap[selectedProvince];
                }
                
                // Add the cities to the dropdown
                citiesToShow.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    
                    // If this was the previously selected city, select it again if it exists in new list
                    if (city === currentCityValue) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
                
                // If the previously selected city isn't available in the new province
                // reset to "All Cities"
                const cityExists = Array.from(citySelect.options).some(option => option.value === currentCityValue);
                if (!cityExists && currentCityValue !== '') {
                    citySelect.value = '';
                    currentCityValue = '';
                }
            }
            
            // Listen for changes on the province dropdown
            provinceSelect.addEventListener('change', function() {
                updateCities();
            });
            
            // Store the selected city value when it changes
            citySelect.addEventListener('change', function() {
                currentCityValue = citySelect.value;
            });
            
            // Initialize cities based on the initial province selection
            updateCities();
            
            // Preserve form inputs when browser's back button is used
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    // Update cities dropdown after page is shown from back-forward cache
                    updateCities();
                }
            });
            
            // Add event handling to prevent form submission with invalid city selection
            filterForm.addEventListener('submit', function(event) {
                const selectedProvince = provinceSelect.value;
                const selectedCity = citySelect.value;
                
                // If a city is selected but doesn't belong to the selected province
                if (selectedCity && selectedProvince && 
                    cityProvinceMap[selectedProvince] && 
                    !cityProvinceMap[selectedProvince].includes(selectedCity)) {
                    
                    // Reset city selection
                    citySelect.value = '';
                }
            });
        });
    </script>
</x-app-layout>