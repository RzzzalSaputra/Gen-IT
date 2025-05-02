<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $company->name }}
            </h2>
            <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke List Perusahaan
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Company Header Section -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-500/5 overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    @if($company->img)
                        <div class="-mt-10 -mx-10 mb-10">
                            <div class="relative h-64 md:h-80 overflow-hidden">
                                <img src="{{ asset('storage/' . $company->img) }}" alt="{{ $company->name }}" 
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-80"></div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-500/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Company
                        </div>
                        <div class="inline-flex items-center px-3 py-1.5 bg-purple-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-purple-300 border border-purple-500/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $company->city }}, {{ $company->province }}
                        </div>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $company->name }}</h1>
                    
                    <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $company->read_counter ?? 0 }} views
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Terakhir Diperbaharui: {{ $company->updated_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Company Content Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-10">
                        <div class="p-8 md:p-10">
                            <!-- Company Description -->
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Tentang Perusahaan ini
                                </h2>
                                <div class="prose lg:prose-xl dark:prose-invert text-gray-100 max-w-none">
                                    {!! $company->description !!}
                                </div>
                            </div>
                            
                            <!-- Available Jobs - Similar to studies in school -->
                            @if(isset($company->jobs) && $company->jobs->count() > 0)
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Lowongan Pekerjaan
                                </h2>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($company->jobs as $job)
                                    <a href="{{ route('jobs.show', $job->id) }}" class="bg-gray-900/40 hover:bg-gray-900/60 rounded-xl p-5 border border-blue-600/10 transition-all duration-300 group">
                                        <h3 class="text-xl text-white font-semibold mb-2 group-hover:text-blue-300 transition-colors duration-200">{{ $job->title }}</h3>
                                        
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="inline-flex items-center px-2 py-1 bg-blue-900/40 backdrop-blur-sm rounded text-xs font-medium text-blue-300 border border-blue-500/30">
                                                {{ App\Models\Option::find($job->work_type)->value ?? 'Full Time' }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 bg-indigo-900/40 backdrop-blur-sm rounded text-xs font-medium text-indigo-300 border border-indigo-500/30">
                                                {{ App\Models\Option::find($job->type)->value ?? 'Job Type' }}
                                            </span>
                                            @if($job->salary_range)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-900/40 backdrop-blur-sm rounded text-xs font-medium text-green-300 border border-green-500/30">
                                                Rp {{ number_format($job->salary_range, 0, ',', '.') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-gray-300 text-sm line-clamp-2">{{ strip_tags($job->description ?? '') }}</p>
                                        
                                        <div class="mt-3 text-sm text-blue-400 flex items-center">
                                            <span>Lihat Detail</span>
                                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Company Location -->
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Lokasi
                                </h2>
                                <p class="text-gray-100 mb-6">{{ $company->address }}</p>
                                
                                @if($company->gmap)
                                <div class="h-96 w-full rounded-xl overflow-hidden border border-gray-700/50 shadow-lg">
                                    <iframe 
                                        src="{{ $company->gmap }}" 
                                        class="w-full h-full"
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-16 pt-8 border-t border-gray-700/50">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">Terakhir Diperbaharui:</span>
                                        <span class="text-gray-300">{{ $company->updated_at->format('M d, Y, h:i A') }}</span>
                                    </div>
                                    <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Kembali ke List Perusahaan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <!-- Contact Info Section -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden mb-8">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Perusahaan
                            </h3>
                            
                            <ul class="space-y-3">
                                @if($company->website)
                                <li>
                                    <a href="{{ $company->website }}" target="_blank" class="flex items-center p-3 bg-gray-900/50 hover:bg-gray-900/70 rounded-lg transition-colors duration-200 group">
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30 group-hover:border-blue-500/50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-200 group-hover:text-blue-300 transition-colors duration-200">Website</p>
                                            <p class="text-xs text-gray-500 break-all">{{ $company->website }}</p>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                
                                @if($company->instagram)
                                <li>
                                    <a href="https://instagram.com/{{ ltrim($company->instagram, '@') }}" target="_blank" class="flex items-center p-3 bg-gray-900/50 hover:bg-gray-900/70 rounded-lg transition-colors duration-200 group">
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-pink-900/30 flex items-center justify-center mr-3 border border-pink-700/30 group-hover:border-pink-500/50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-200 group-hover:text-pink-300 transition-colors duration-200">Instagram</p>
                                            <p class="text-xs text-gray-500 break-all">{{ $company->instagram }}</p>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                
                                @if($company->facebook)
                                <li>
                                    <a href="{{ $company->facebook }}" target="_blank" class="flex items-center p-3 bg-gray-900/50 hover:bg-gray-900/70 rounded-lg transition-colors duration-200 group">
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30 group-hover:border-blue-500/50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385h-3.047v-3.47h3.047v-2.642c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.514c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385c5.736-.9 10.125-5.864 10.125-11.854z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-200 group-hover:text-blue-300 transition-colors duration-200">Facebook</p>
                                            <p class="text-xs text-gray-500 break-all">{{ $company->facebook }}</p>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                
                                @if($company->x)
                                <li>
                                    <a href="https://x.com/{{ ltrim($company->x, '@') }}" target="_blank" class="flex items-center p-3 bg-gray-900/50 hover:bg-gray-900/70 rounded-lg transition-colors duration-200 group">
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-gray-800/50 flex items-center justify-center mr-3 border border-gray-700/50 group-hover:border-gray-500/50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-200 group-hover:text-gray-100 transition-colors duration-200">X (Twitter)</p>
                                            <p class="text-xs text-gray-500 break-all">{{ $company->x }}</p>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Similar Companies -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Perusahaan Serupa
                            </h3>
                            <div class="space-y-4">
                                @foreach(App\Models\Company::where('id', '!=', $company->id)->where('province', $company->province)->take(5)->get() as $relatedCompany)
                                    <a href="{{ route('companies.show', $relatedCompany->id) }}" class="block p-3 hover:bg-gray-700/30 rounded-lg transition-colors duration-200 group">
                                        <div class="flex items-start">
                                            @if($relatedCompany->img)
                                                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 mr-3">
                                                    <img src="{{ asset('storage/' . $relatedCompany->img) }}" alt="{{ $relatedCompany->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-800/30 to-purple-800/30 flex-shrink-0 mr-3 flex items-center justify-center border border-blue-700/30">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-200 group-hover:text-blue-300 transition-colors duration-200">{{ $relatedCompany->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $relatedCompany->city }}, {{ $relatedCompany->province }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>