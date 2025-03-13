<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $study->name }}
            </h2>
            <a href="{{ route('schools.show', $study->school_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to School
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Study Header Section -->
            <div class="bg-gradient-to-r from-indigo-900/30 via-purple-900/30 to-blue-900/30 backdrop-blur-sm rounded-3xl border border-purple-500/20 shadow-xl shadow-purple-500/5 overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    @if($study->img && $study->img != '/storage/studies/images/default.jpg')
                        <div class="-mt-10 -mx-10 mb-10">
                            <div class="relative h-64 md:h-80 overflow-hidden">
                                <img src="{{ $study->img }}" alt="{{ $study->name }}" 
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-80"></div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($study->levelOption)
                            <div class="inline-flex items-center px-3 py-1.5 bg-purple-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-purple-300 border border-purple-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                {{ $study->levelOption->value }}
                            </div>
                        @endif
                        
                        <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-500/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $study->duration }}
                        </div>
                        
                        <a href="{{ route('schools.show', $study->school_id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-green-300 border border-green-500/30 hover:bg-green-800/40 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $study->school ? $study->school->name : 'Unknown School' }}
                        </a>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $study->name }}</h1>
                    
                    <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $study->read_counter ?? 0 }} views
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Last updated: {{ $study->updated_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Study Content Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-10">
                        <div class="p-8 md:p-10">
                            <!-- Study Description -->
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-purple-300 border-b border-purple-600/20 pb-2 mb-6 font-bold">
                                    About This Program
                                </h2>
                                <div class="prose lg:prose-xl dark:prose-invert text-gray-100 max-w-none">
                                    {!! $study->description !!}
                                </div>
                            </div>
                            
                            <!-- School Information -->
                            @if($study->school)
                            <div class="mb-12">
                                <h2 class="text-2xl md:text-3xl text-purple-300 border-b border-purple-600/20 pb-2 mb-6 font-bold">
                                    Institution Information
                                </h2>
                                <div class="bg-gray-900/30 rounded-xl p-6 border border-gray-700/50">
                                    <div class="flex items-start">
                                        @if($study->school->img)
                                            <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 mr-6">
                                                <img src="{{ $study->school->img }}" alt="{{ $study->school->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="text-xl font-semibold text-white mb-2">{{ $study->school->name }}</h3>
                                            <p class="text-gray-400 mb-4">{{ $study->school->city }}, {{ $study->school->province }}</p>
                                            <a href="{{ route('schools.show', $study->school_id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-900/50 hover:bg-indigo-800/60 rounded-lg text-sm text-white transition-colors duration-200 border border-indigo-700/50">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                View School Details
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
                                        <span class="text-gray-300">{{ $study->updated_at->format('M d, Y, h:i A') }}</span>
                                    </div>
                                    <a href="{{ route('schools.show', $study->school_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Back to School
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
                                <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Program Information
                            </h3>
                            
                            <ul class="space-y-3">
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-200">Duration</p>
                                        <p class="text-xs text-gray-500">{{ $study->duration }}</p>
                                    </div>
                                </li>
                                
                                @if($study->levelOption)
                                <li class="flex items-center p-3 bg-gray-900/50 rounded-lg">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-purple-900/30 flex items-center justify-center mr-3 border border-purple-700/30">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-200">Education Level</p>
                                        <p class="text-xs text-gray-500">{{ $study->levelOption->value }}</p>
                                    </div>
                                </li>
                                @endif
                                
                                @if($study->link)
                                <li>
                                    <a href="{{ $study->link }}" target="_blank" class="flex items-center p-3 bg-gray-900/50 hover:bg-gray-900/70 rounded-lg transition-colors duration-200 group">
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-green-900/30 flex items-center justify-center mr-3 border border-green-700/30 group-hover:border-green-500/50 transition-all duration-200">
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-200 group-hover:text-green-300 transition-colors duration-200">Official Program Link</p>
                                            <p class="text-xs text-gray-500 break-all">Visit program website</p>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Similar Programs -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Similar Programs
                            </h3>
                            <div class="space-y-4">
                                @foreach(\App\Models\Study::where('id', '!=', $study->id)
                                    ->where('school_id', $study->school_id)
                                    ->orWhere(function($query) use ($study) {
                                        $query->where('level', $study->level)
                                            ->where('id', '!=', $study->id);
                                    })
                                    ->take(5)
                                    ->get() as $relatedStudy)
                                    <a href="{{ route('studies.show', $relatedStudy->id) }}" class="block p-3 hover:bg-gray-700/30 rounded-lg transition-colors duration-200 group">
                                        <div class="flex items-start">
                                            @if($relatedStudy->img && $relatedStudy->img != '/storage/studies/images/default.jpg')
                                                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 mr-3">
                                                    <img src="{{ $relatedStudy->img }}" alt="{{ $relatedStudy->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-800/30 to-purple-800/30 flex-shrink-0 mr-3 flex items-center justify-center border border-blue-700/30">
                                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-200 group-hover:text-blue-300 transition-colors duration-200">{{ $relatedStudy->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $relatedStudy->duration }}</p>
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