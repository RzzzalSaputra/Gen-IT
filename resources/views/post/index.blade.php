<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Posts') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-8">
                <form action="{{ route('posts.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" 
                            name="search" 
                            value="{{ request('search') }}" 
                            class="w-full pl-12 pr-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                            placeholder="Cari Postingan...">
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 rounded-xl border border-blue-500 text-white font-medium hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </form>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-6">
                    @if($posts->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">Postingan Tidak ditemukan</h3>
                            <p class="text-gray-400">Postingan akan muncul di sini setelah ditambahkan ke sistem.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($posts as $post)
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    @if($post->img)
                                    <div class="h-48 bg-cover bg-center" style="background-image: url('{{ Storage::url($post->img) }}')">
                                    </div>
                                    @else
                                    <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6">
                                        <svg class="w-16 h-16 text-blue-400/70 group-hover:text-blue-300 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        <div class="flex items-center mb-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded-lg bg-blue-900/50 text-blue-300 border border-blue-700/30">
                                                {{ $post->option ? $post->option->name : 'Post' }}
                                            </span>
                                            <span class="ml-auto text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-300 transition-colors duration-200">
                                            {{ $post->title }}
                                        </h3>
                                        
                                        <p class="text-gray-300 text-sm line-clamp-3 mb-4">
                                            {!! Str::limit(strip_tags($post->content), 150) !!}
                                        </p>
                                        
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-400">
                                                Oleh: {{ $post->user ? $post->user->name : 'Unknown' }}
                                            </span>
                                            
                                            <a href="{{ route('posts.show', $post->id) }}" class="inline-flex items-center text-sm font-medium text-blue-400 hover:text-blue-300 group-hover:translate-x-1 transition-all duration-200">
                                                Baca Lebih Lanjut
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                            </a>
                                        </div>
                                        
                                        @if($post->file)
                                        <div class="mt-4 pt-3 border-t border-gray-700/30">
                                            <a class="flex items-center text-sm text-gray-300 hover:text-blue-300 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Dokumen Terlampir
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($post->video_url)
                                        <div class="mt-2 {{ $post->file ? '' : 'pt-3 border-t border-gray-700/30' }}">
                                            <a href="{{ $post->video_url }}" target="_blank" class="flex items-center text-sm text-gray-300 hover:text-blue-300 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Watch video
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $posts->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>