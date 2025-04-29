<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Learning Materials') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $materials->total() }} {{ Str::plural('resource', $materials->total()) }} available
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Filter Tabs -->
            <div class="mb-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-2 border border-gray-700/50">
                <ul class="flex flex-wrap justify-center gap-2 text-sm font-medium">
                    <li>
                        <a href="{{ url('/materials=text') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->is('materials=text') || (!request()->is('materials=*') && !request()->has('content_type')) || request()->input('content_type') == 'text' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Text Materials
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/materials=downloadable') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->is('materials=downloadable') || request()->input('content_type') == 'downloadable' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Downloads
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/materials=video') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg {{ request()->is('materials=video') || request()->input('content_type') == 'video' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white transition-all duration-200' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Video Content
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mb-8">
                <form action="{{ url('/materials=' . (request()->route('content_type') ?? 'text')) }}" method="GET" class="flex items-center gap-3">
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
                               placeholder="Cari Materi Pembelajaran">
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
                    @if($materials->isEmpty())
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Materials Found</h3>
                            <p class="text-gray-400">Materials will appear here once they are added to the system.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($materials as $material)
                                @php
                                    $layoutType = $material->layoutOption ? $material->layoutOption->value : 'Text Only';
                                    
                                    $isVideo = false;
                                    $videoId = null;
                                    if (isset($material->link) && $material->link) {
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $material->link, $match)) {
                                            $isVideo = true;
                                            $videoId = $match[1];
                                        }
                                    }
                                    
                                    $fileExtension = null;
                                    if ($material->file) {
                                        $fileExtension = pathinfo($material->file, PATHINFO_EXTENSION);
                                        $fileExtension = strtoupper($fileExtension);
                                    }
                                @endphp
                                
                                <div class="group bg-gray-800/30 hover:bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/30 hover:border-blue-500/50 transition-all duration-300 hover:shadow-blue-500/5">
                                    @if($layoutType == 'Text Only')
                                        <a href="{{ route('materials.show', $material->id) }}" class="block h-48 flex items-center justify-center bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6">
                                            <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $material->title }}</h4>
                                            </div>
                                        </a>
                                    @elseif($layoutType == 'Text with Image' && $material->img)
                                        <a href="{{ route('materials.show', $material->id) }}" class="block h-48 relative overflow-hidden">
                                            <img src="{{ asset('storage/' . $material->img) }}" 
                                                 alt="{{ $material->title }}" 
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
                                            <h4 class="absolute bottom-4 left-4 right-4 text-white font-medium text-sm line-clamp-2">
                                                {{ $material->title }}
                                            </h4>
                                        </a>
                                    @elseif($layoutType == 'Video Content')
                                        <div class="h-48 bg-black relative overflow-hidden">
                                            @php
                                                $videoEmbedId = null;
                                                if ($material->link && preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $material->link, $match)) {
                                                    $videoEmbedId = $match[1];
                                                }
                                            @endphp
                                            
                                            @if($videoEmbedId)
                                                <iframe 
                                                    src="https://www.youtube.com/embed/{{ $videoEmbedId }}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen
                                                    class="w-full h-48"
                                                ></iframe>
                                            @else
                                                <div class="flex items-center justify-center h-full bg-gradient-to-br from-purple-600/20 to-blue-600/20 p-6">
                                                    <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $material->title }}</h4>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="absolute top-3 right-3">
                                                <span class="px-2 py-1 text-xs font-medium text-purple-300 bg-purple-900/30 backdrop-blur-sm rounded-lg border border-purple-500/20">
                                                    VIDEO
                                                </span>
                                            </div>
                                        </div>
                                    @elseif($layoutType == 'File Only')
                                        <div class="h-48 flex items-center justify-center bg-gradient-to-br from-purple-600/20 to-pink-600/20 p-6 relative overflow-hidden">
                                            <div class="absolute top-3 right-3">
                                                <span class="px-2 py-1 text-xs font-medium text-purple-300 bg-purple-900/30 backdrop-blur-sm rounded-lg border border-purple-500/20">
                                                    {{ $fileExtension ?? 'FILE' }}
                                                </span>
                                            </div>
                                            <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </div>
                                                <h4 class="text-gray-200 font-medium text-sm px-2 line-clamp-2">{{ $material->title }}</h4>
                                            </div>
                                        </div>
                                    @elseif($isVideo && $videoId)
                                        <div class="aspect-w-16 aspect-h-9 h-48 bg-black relative group">
                                            <iframe 
                                                src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen
                                                class="w-full h-48"
                                            ></iframe>
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        </div>
                                    @else
                                        <div class="h-48 flex items-center justify-center bg-gradient-to-br from-gray-700/50 to-gray-800/50 p-6">
                                            <div class="text-center transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center shadow-lg">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <h4 class="text-gray-300 font-medium text-sm px-2 line-clamp-2">{{ $material->title }}</h4>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-100 mb-3 group-hover:text-blue-400 transition-colors duration-200">
                                            {{ $material->title }}
                                        </h3>
                                        
                                        @if($layoutType != 'File Only' && !$isVideo)
                                            <div class="text-sm text-gray-400 mb-4 line-clamp-2">
                                                {{ strip_tags($material->content) }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($layoutType == 'File Only' && $material->file)
                                                <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                    <svg class="w-4 h-4 mar-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    File
                                                </div>
                                                <div class="inline-flex items-center px-2 py-1 bg-emerald-900/30 backdrop-blur-sm rounded-lg text-xs text-emerald-300 border border-emerald-500/30">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                    </svg>
                                                    {{ $material->download_counter ?? 0 }} downloads
                                                </div>
                                            @endif
                                            
                                            @if($isVideo)
                                                <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs text-gray-300 border border-gray-600/30">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Video
                                                </div>
                                            @endif
                                            
                                            @if($material->typeOption)
                                                <div class="inline-flex items-center px-2 py-1 bg-blue-900/30 backdrop-blur-sm rounded-lg text-xs text-blue-300 border border-blue-500/30">
                                                    {{ $material->typeOption->value }}
                                                </div>
                                            @endif

                                            @if($layoutType == 'Video Content' && $material->link)
                                                <div class="inline-flex items-center px-2 py-1 bg-purple-900/40 backdrop-blur-sm rounded-lg text-xs text-purple-300 border border-purple-500/30">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Video Content
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-6">
                                            <span class="text-sm text-gray-500">{{ $material->created_at->diffForHumans() }}</span>
                                            
                                            <div class="flex items-center gap-3">
                                                @if($layoutType == 'Text Only' || $layoutType == 'Text with Image')
                                                    <!-- For text materials, link to detail page instead of preview -->
                                                    <a href="{{ route('materials.show', $material->id) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Materi
                                                    </a>
                                                @endif
                                                
                                                @if(($layoutType == 'Text with File' || $layoutType == 'File Only') && $material->file)
                                                    <a href="{{ route('materials.download', $material->id) }}" class="text-blue-400 hover:text-blue-300 inline-flex items-center text-sm font-medium transition-colors duration-200">
                                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                @endif
                                                
                                                @if($isVideo && !$videoId && $material->link)
                                                    <a href="{{ $material->link }}" 
                                                       target="_blank" 
                                                       class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if($layoutType == 'Video Content' && $material->link)
                                                    <a href="{{ $material->link }}" 
                                                       target="_blank" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 group">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Watch Video
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $materials->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const contentModal = document.getElementById('contentModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const closeContentModal = document.getElementById('closeContentModal');
            
            const previewButtons = document.querySelectorAll('.preview-button');
            
            previewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const content = this.getAttribute('data-content');
                    const file = this.getAttribute('data-file');
                    const fileType = this.getAttribute('data-filetype');
                    
                    modalTitle.textContent = title;
                    
                    let modalContent = `<div class="prose dark:prose-invert max-w-none">${content}</div>`;
                    
                    if (file) {
                        const fileExtension = file.split('.').pop().toLowerCase();
                        
                        if (fileExtension === 'pdf') {
                            modalContent += `
                                <div class="mt-8 border-t border-gray-700 pt-6">
                                    <h4 class="text-lg font-semibold mb-4 text-gray-200">Attached Document</h4>
                                    <div class="bg-gray-900 rounded-xl overflow-hidden">
                                        <iframe src="${file}" class="w-full h-[600px] border-0"></iframe>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <a href="${file}" 
                                           download 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 group">
                                            <svg class="w-4 h-4 mr-2 transform group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download Document
                                        </a>
                                    </div>
                                </div>
                            `;
                        } else if (fileExtension === 'doc' || fileExtension === 'docx') {
                            modalContent += `
                                <div class="mt-8 border-t border-gray-700 pt-6">
                                    <h4 class="text-lg font-semibold mb-4 text-gray-200">Microsoft Word Document</h4>
                                    <div class="bg-gray-900 rounded-xl p-8 flex flex-col items-center justify-center">
                                        <svg class="w-24 h-24 text-blue-500 mb-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 6.5V17.5C20 19.9853 17.9853 22 15.5 22H8.5C6.01472 22 4 19.9853 4 17.5V6.5C4 4.01472 6.01472 2 8.5 2H15.5C17.9853 2 20 4.01472 20 6.5Z" stroke="currentColor" stroke-width="1.5"/>
                                            <path d="M9.5 10L7 12L9.5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14.5 10L17 12L14.5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 9L12 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <p class="text-gray-300 mb-6">Word document preview not available directly in browser.</p>
                                        <a href="${file}" 
                                           download 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 group">
                                            <svg class="w-4 h-4 mr-2 transform group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download Word Document
                                        </a>
                                    </div>
                                </div>
                            `;
                        } else {
                            modalContent += `
                                <div class="mt-8 border-t border-gray-700 pt-6">
                                    <h4 class="text-lg font-semibold mb-4 text-gray-200">Attached File (${fileType})</h4>
                                    <div class="bg-gray-900 rounded-xl p-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-gray-300 font-medium">${file.split('/').pop()}</span>
                                        </div>
                                        <a href="${file}" 
                                           download 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 group">
                                            <svg class="w-4 h-4 mr-2 transform group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            `;
                        }
                    }
                    
                    modalBody.innerHTML = modalContent;
                    
                    contentModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
            
            closeContentModal.addEventListener('click', function() {
                contentModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
            
            contentModal.addEventListener('click', function(e) {
                if (e.target === contentModal) {
                    contentModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !contentModal.classList.contains('hidden')) {
                    contentModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });

            const videoPreviewButtons = document.querySelectorAll('.video-preview-button');
            
            videoPreviewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const videoId = this.getAttribute('data-video-id');
                    const videoUrl = this.getAttribute('data-video-url');
                    
                    modalTitle.textContent = title;
                    
                    let videoContent = '';
                    if (videoId) {
                        videoContent = `
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe 
                                    src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-full h-[500px]">
                                </iframe>
                            </div>
                        `;
                    } else {
                        videoContent = `
                            <div class="bg-gray-900 p-8 rounded-xl text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-xl font-medium mb-2 text-gray-300">Video URL</h3>
                                <p class="text-gray-400 mb-6">The video cannot be embedded directly.</p>
                                <a href="${videoUrl}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Open Video Link
                                </a>
                            </div>
                        `;
                    }
                    
                    modalBody.innerHTML = videoContent;
                    
                    contentModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });
        });
    </script>
</x-app-layout>