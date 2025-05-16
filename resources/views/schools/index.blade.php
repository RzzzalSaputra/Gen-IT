<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Educational Institutions') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $schools->total() }} {{ Str::plural('institution', $schools->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-12 sm:pt-16 md:pt-24">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Filter Tabs - Responsive -->
            <div class="mb-6 sm:mb-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50 overflow-x-auto">
                <ul class="flex flex-nowrap sm:flex-wrap justify-start sm:justify-center gap-2 text-sm font-medium min-w-max">
                    <li>
                        <a href="{{ url('/schools') }}" 
                        class="inline-flex items-center px-4 sm:px-6 py-3 rounded-lg {{ !request()->has('type') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="whitespace-nowrap">Semua Institusi</span>
                        </a>
                    </li>
                    @foreach($schoolTypes as $type)
                    <li>
                        <a href="{{ url('/schools?type=' . $type->id) }}" 
                           class="inline-flex items-center px-4 sm:px-6 py-3 rounded-lg {{ request()->input('type') == $type->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="whitespace-nowrap">{{ $type->value }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-6 sm:mb-8">
                <form action="{{ url('/schools') }}" method="GET" class="flex flex-col sm:flex-row items-start gap-3">
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" 
                               name="_search" 
                               value="{{ request('_search') }}" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                               placeholder="Cari institusi pendidikan...">
                    </div>
                    
                    <div class="grid grid-cols-2 sm:flex gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <select name="province" class="w-full sm:w-[180px] md:w-[220px] bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none text-sm">
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
                        
                        <div class="relative w-full sm:w-auto">
                            <select name="city" class="w-full sm:w-[180px] md:w-[220px] bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 pl-4 pr-10 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 appearance-none text-sm">
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

                        <button type="submit" class="col-span-2 sm:col-span-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-4 sm:p-6">
                    @if($schools->isEmpty())
                        <div class="text-center py-12 sm:py-16">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-200">Tidak Ada Institusi yang Ditemukan</h3>
                            <p class="text-gray-400">institut pendidikan akan muncul di sini setelah ditambahkan ke sistem.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($schools as $school)
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    <a href="{{ route('schools.show', $school->id) }}" class="block h-36 sm:h-48 relative overflow-hidden">
                                        @if($school->img)
                                            <img src="{{ Storage::url('schools/images/' . basename($school->img)) }}" 
                                                 alt="{{ $school->name }}" 
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="h-full flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6">
                                                <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                    <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
                                        <h4 class="absolute bottom-4 left-4 right-4 text-white font-medium text-sm line-clamp-2">
                                            {{ $school->name }}
                                        </h4>
                                    </a>
                                    
                                    <div class="p-4 sm:p-6">
                                        <h3 class="text-lg sm:text-xl font-bold text-gray-100 mb-2 sm:mb-3 group-hover:text-blue-400 transition-colors duration-200 line-clamp-2">
                                            {{ $school->name }}
                                        </h3>
                                        
                                        <div class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4 line-clamp-2">
                                            {{ strip_tags($school->description) }}
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-1 sm:gap-2 mb-3 sm:mb-4">
                                            @if($school->typeOption)
                                                <div class="inline-flex items-center px-2 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-xs text-blue-300 border border-blue-500/30">
                                                    {{ $school->typeOption->value }}
                                                </div>
                                            @endif

                                            <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $school->city }}, {{ $school->province }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-4 sm:mt-6">
                                            <div class="flex space-x-2">
                                                @if($school->website)
                                                <a href="{{ $school->website }}" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($school->instagram)
                                                <a href="https://instagram.com/{{ ltrim($school->instagram, '@') }}" target="_blank" class="text-gray-400 hover:text-pink-400 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($school->facebook)
                                                <a href="{{ $school->facebook }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385h-3.047v-3.47h3.047v-2.642c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.514c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385c5.736-.9 10.125-5.864 10.125-11.854z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                
                                                @if($school->x)
                                                <a href="https://x.com/{{ ltrim($school->x, '@') }}" target="_blank" class="text-gray-400 hover:text-gray-100 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('schools.show', $school->id) }}" class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 sm:mt-8 overflow-x-auto">
                            {{ $schools->appends(request()->except('page'))->links() }}
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
        
        /* Improve mobile pagination display */
        .pagination {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 0.5rem;
        }
        
        /* Hide scrollbar but keep functionality */
        .pagination::-webkit-scrollbar {
            display: none;
        }
        
        /* For Firefox */
        .pagination {
            scrollbar-width: none;
        }
        
        /* For horizontal scrollable areas */
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
            background-color: rgba(31, 41, 55, 0.5);
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: rgba(59, 130, 246, 0.5);
            border-radius: 2px;
        }
        
        @media (max-width: 640px) {
            /* Additional mobile-specific styles */
            .pagination > * {
                flex: 0 0 auto;
            }
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