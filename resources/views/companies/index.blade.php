<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Companies') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $companies->total() }} {{ Str::plural('company', $companies->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-8">
                <form action="{{ url('/companies') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" 
                               name="_search" 
                               value="{{ request('_search') }}" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                               placeholder="Search companies...">
                    </div>
                    
                    <div class="relative">
                        <select name="province" class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none">
                            <option value="">All Provinces</option>
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
                        <select name="city" class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none">
                            <option value="">All Cities</option>
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

                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                </form>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($companies->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Companies Found</h3>
                            <p class="text-gray-400">Companies will appear here once they are added to the system.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($companies as $company)
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    <a href="{{ route('companies.show', $company->id) }}" class="block h-48 relative overflow-hidden">
                                        @if($company->img)
                                            <img src="{{ $company->img }}" 
                                                 alt="{{ $company->name }}" 
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="h-full flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6">
                                                <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
                                        <h4 class="absolute bottom-4 left-4 right-4 text-white font-medium text-sm line-clamp-2">
                                            {{ $company->name }}
                                        </h4>
                                    </a>
                                    
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:text-blue-400 transition-colors duration-200">
                                            {{ $company->name }}
                                        </h3>
                                        
                                        <div class="text-sm text-gray-400 mb-4 line-clamp-2">
                                            {{ strip_tags($company->description) }}
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $company->city }}, {{ $company->province }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-6">
                                            <div class="flex space-x-2">
                                                @if($company->website)
                                                <a href="{{ $company->website }}" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($company->instagram)
                                                <a href="https://instagram.com/{{ ltrim($company->instagram, '@') }}" target="_blank" class="text-gray-400 hover:text-pink-400 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($company->facebook)
                                                <a href="{{ $company->facebook }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385h-3.047v-3.47h3.047v-2.642c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.514c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385c5.736-.9 10.125-5.864 10.125-11.854z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($company->x)
                                                <a href="https://x.com/{{ ltrim($company->x, '@') }}" target="_blank" class="text-gray-400 hover:text-gray-100 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('companies.show', $company->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $companies->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Fixed widths for the province and city dropdowns */
        select[name="province"], 
        select[name="city"] {
            width: 220px; /* You can adjust this value to fit your design */
            text-overflow: ellipsis;
        }
        
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
            
            // Get the province and city select elements
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
                    // If this was the previously selected city, select it again
                    if (city === currentCityValue) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
                
                // If the previously selected city isn't available in the new province
                // reset to "All Cities"
                if (!citiesToShow.includes(currentCityValue) && currentCityValue !== '') {
                    citySelect.value = '';
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
        });
    </script>
</x-app-layout>