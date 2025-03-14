<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $job->title }}
            </h2>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Jobs
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Job Header Section -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-500/5 overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($job->jobType)
                            <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $job->jobType->value }}
                            </div>
                        @endif
                        
                        @if($job->experienceLevel)
                            <div class="inline-flex items-center px-3 py-1.5 bg-purple-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-purple-300 border border-purple-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ $job->experienceLevel->value }}
                            </div>
                        @endif
                        
                        @if($job->workType)
                            <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ $job->workType->value }}
                            </div>
                        @endif
                        
                        <div class="inline-flex items-center px-3 py-1.5 bg-green-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-green-300 border border-green-500/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ number_format($job->salary_range, 0) }} IDR
                        </div>
                        
                        @if($job->company)
                        <a href="{{ $job->company ? route('companies.show', $job->company_id) : '#' }}" class="inline-flex items-center px-3 py-1.5 bg-red-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-red-300 border border-red-500/30 hover:bg-red-800/40 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ $job->company->name }}
                    </a>

                        @endif
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $job->title }}</h1>
                    
                    <div class="flex items-start gap-3 md:gap-6">
                        @if($job->company && $job->company->logo)
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="{{ $job->company->logo }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ $job->read_counter ?? 0 }} views
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                @if($job->company && $job->company->city && $job->company->province)
                                    {{ $job->company->city }}, {{ $job->company->province }}
                                @else
                                    Remote/Various Locations
                                @endif
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Posted: {{ $job->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Job Content Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-10">
                        <div class="p-8 md:p-10">
                            <!-- Job Description -->
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Job Description
                                </h2>
                                <div class="prose lg:prose-xl dark:prose-invert text-gray-100 max-w-none">
                                    {!! $job->description !!}
                                </div>
                            </div>
                            
                            <!-- Job Requirements -->
                            @if($job->requirment)
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Requirements
                                </h2>
                                <div class="prose lg:prose-xl dark:prose-invert text-gray-100 max-w-none">
                                    {!! $job->requirment !!}
                                </div>
                            </div>
                            @endif
                            
                            <!-- Company Information -->
                            @if($job->company)
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-blue-300 border-b border-blue-600/20 pb-2 mb-6 font-bold">
                                    Company Information
                                </h2>
                                <div class="bg-gray-900/30 rounded-xl p-6 border border-gray-700/50">
                                    <div class="flex items-start">
                                        @if($job->company->logo)
                                            <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 mr-6">
                                                <img src="{{ $job->company->logo }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-xl font-semibold text-white mb-2">{{ $job->company->name }}</h3>
                                            @if($job->company->city && $job->company->province)
                                                <p class="text-gray-400 mb-4">{{ $job->company->city }}, {{ $job->company->province }}</p>
                                            @endif
                                            <a href="{{ route('companies.show', $job->company_id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-900/50 hover:bg-indigo-800/60 rounded-lg text-sm text-white transition-colors duration-200 border border-indigo-700/50">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                View Company Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-16 pt-8 border-t border-gray-700/50">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">Last updated:</span>
                                        <span class="text-gray-300">{{ $job->updated_at->format('M d, Y, h:i A') }}</span>
                                    </div>
                                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Back to Job Listings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <!-- Quick Information -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden mb-8">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Job Information
                            </h3>
                            
                            <ul class="space-y-3">
                                @if($job->company && $job->company->city && $job->company->province)
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-200">Location</p>
                                        <p class="text-xs text-gray-500">{{ $job->company->city }}, {{ $job->company->province }}</p>
                                    </div>
                                </li>
                                @endif
                                
                                @if($job->workType)
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg border border-blue-700/30">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-blue-300">Work Type</p>
                                        <p class="text-xs text-gray-500">{{ $job->workType->value }}</p>
                                    </div>
                                </li>
                                @endif
                                
                                @if($job->jobType)
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg border border-blue-700/30">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-blue-300">Job Type</p>
                                        <p class="text-xs text-gray-500">{{ $job->jobType->value }}</p>
                                    </div>
                                </li>
                                @endif
                                
                                @if($job->experienceLevel)
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg border border-purple-700/30">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-purple-900/30 flex items-center justify-center mr-3 border border-purple-700/30">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-purple-300">Experience Level</p>
                                        <p class="text-xs text-gray-500">{{ $job->experienceLevel->value }}</p>
                                    </div>
                                </li>
                                @endif
                                
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg border border-green-700/30">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-green-900/30 flex items-center justify-center mr-3 border border-green-700/30">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-green-300">Salary Range</p>
                                        <p class="text-xs text-gray-500">{{ number_format($job->salary_range, 0) }} IDR</p>
                                    </div>
                                </li>
                                
                                @if($job->register_link)
                                <li class="mt-5">
                                    <a href="{{ $job->register_link }}" target="_blank" class="flex items-center justify-center p-3 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        <span class="font-medium text-white">Apply Now</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Similar Jobs -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Similar Jobs
                            </h3>
                            <div class="space-y-4">
                                @foreach(\App\Models\Job::where('id', '!=', $job->id)
                                    ->where(function($query) use ($job) {
                                        $query->where('company_id', $job->company_id)
                                            ->orWhere('type', $job->type);
                                    })
                                    ->take(5)
                                    ->get() as $relatedJob)
                                    <a href="{{ route('jobs.show', $relatedJob->id) }}" class="block p-3 hover:bg-gray-700/30 rounded-lg transition-colors duration-200 group">
                                        <div class="flex items-start">
                                            @if($relatedJob->company && $relatedJob->company->logo)
                                                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 mr-3">
                                                    <img src="{{ $relatedJob->company->logo }}" alt="{{ $relatedJob->company->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-800/30 to-indigo-800/30 flex-shrink-0 mr-3 flex items-center justify-center border border-blue-700/30">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-200 group-hover:text-blue-300 transition-colors duration-200">{{ $relatedJob->title }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $relatedJob->company ? $relatedJob->company->name : 'Unknown Company' }}</p>
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