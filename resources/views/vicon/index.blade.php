<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Video Conferences') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $vicons->total() }} {{ Str::plural('video conference', $vicons->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold text-white mb-8 text-center">Video Conferences</h1>
            
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($vicons->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Video Conferences Found</h3>
                            <p class="text-gray-400">Video conferences will appear here once they are added to the system.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($vicons as $vicon)
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    <div class="h-48 overflow-hidden bg-gray-700">
                                        <img src="{{ asset('storage/' . $vicon->img) }}" alt="{{ $vicon->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    
                                    <div class="p-6">
                                        <div class="flex items-center mb-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded-lg bg-blue-900/50 text-blue-300 border border-blue-700/30">
                                                Video Conference
                                            </span>
                                            <span class="ml-auto text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($vicon->time)->format('M d, Y - H:i') }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-200">
                                            {{ $vicon->title }}
                                        </h3>
                                        
                                        <p class="text-gray-300 text-sm line-clamp-3 mb-4">
                                            {{ $vicon->desc }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between">
                                            <a href="{{ $vicon->link }}" target="_blank" class="inline-flex items-center text-sm font-medium text-green-400 hover:text-green-300 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Join
                                            </a>
                                            
                                            <a href="{{ route('vicons.show', $vicon->id) }}" class="inline-flex items-center text-sm font-medium text-blue-400 hover:text-blue-300 group-hover:translate-x-1 transition-all duration-200">
                                                Detail
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $vicons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>