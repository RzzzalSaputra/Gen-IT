<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $vicon->title }}
            </h2>
            <a href="{{ route('vicons.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Video Conferences
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Image Section - Now with max-height -->
            <div class="max-h-64 bg-gray-800/50 rounded-3xl overflow-hidden border border-gray-700/50 shadow-2xl mb-8">
                <img src="{{ $vicon->img }}" alt="{{ $vicon->title }}" class="w-full h-64 object-cover object-center">
            </div>
            
            <!-- Main Content -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-8">
                <div class="p-8 md:p-10">
                    <!-- Title and Meta -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 text-xs font-medium bg-blue-900/50 text-blue-300 rounded-lg border border-blue-700/30">
                                Video Conference
                            </span>
                            <span class="text-gray-400 text-sm">
                                {{ \Carbon\Carbon::parse($vicon->time)->format('M d, Y - H:i') }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                            {{ $vicon->title }}
                        </h1>
                        
                        <div class="mt-4 flex items-center text-sm text-gray-400">
                            <svg class="w-5 h-5 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Diselenggarakan oleh: {{ $vicon->creator->name ?? 'Administrator' }}
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-700/40 pt-8">
                        <h2 class="text-xl font-bold text-blue-300 mb-4">Tentang Zoom ini</h2>
                        
                        <div class="text-gray-200 space-y-4 leading-relaxed mb-8">
                            {{ $vicon->desc }}
                        </div>
                        
                        <!-- Conference Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                            <div class="bg-gray-800/70 rounded-xl p-5 border border-gray-700/50">
                                <h3 class="flex items-center text-lg font-semibold text-white mb-3">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tanggal & Waktu
                                </h3>
                                <div class="text-gray-300">
                                    <p class="mb-1">{{ \Carbon\Carbon::parse($vicon->time)->format('l, F d, Y') }}</p>
                                    <p class="mb-1">{{ \Carbon\Carbon::parse($vicon->time)->format('h:i A') }}</p>
                                    <p class="text-gray-400 text-sm italic">{{ \Carbon\Carbon::parse($vicon->time)->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-800/70 rounded-xl p-5 border border-gray-700/50">
                                <h3 class="flex items-center text-lg font-semibold text-white mb-3">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informasi Konferensi
                                </h3>
                                <div class="text-gray-300">
                                    <p class="mb-2">Gunakan tombol di bawah ini untuk bergabung dengan Zoom atau mengunduh materi terkait.</p>
                                    <p class="text-gray-400 text-sm">Dibuat oleh: {{ $vicon->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-700/40">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ $vicon->link }}" target="_blank" class="inline-flex justify-center items-center px-6 py-3 bg-green-500 hover:bg-green-400 text-white font-medium rounded-xl transition-all duration-200 flex-1 shadow-lg shadow-green-900/30 border border-green-400/30 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Join Conference
                            </a>
                            
                            @if($vicon->download)
                                <a href="{{ $vicon->download }}" target="_blank" class="inline-flex justify-center items-center px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-xl transition-all duration-200 flex-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Video
                                </a>
                            @endif
                            
                            <a href="{{ route('vicons.index') }}" class="inline-flex justify-center items-center px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded-xl transition-all duration-200 border border-gray-600/40">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali Ke List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>