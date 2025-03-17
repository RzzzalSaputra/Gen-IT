<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $post->title }}
            </h2>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Post Header with Image (if available) -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-500/5 overflow-hidden mb-10">
                @if($post->img)
                <div class="w-full h-64 md:h-80 lg:h-96 bg-cover bg-center" style="background-image: url('{{ $post->img }}')">
                </div>
                @endif
                
                <div class="p-8 md:p-10">
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($post->option)
                            <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/50 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-700/30">
                                {{ $post->option->name }}
                            </div>
                        @endif
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $post->title }}</h1>
                    
                    <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                        @if($post->user)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Posted by: {{ $post->user->name }}
                        </div>
                        @endif
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Published: {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Post Content Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-12">
                <div class="p-8 md:p-10">
                <div class="prose lg:prose-xl dark:prose-invert text-gray-100 
                prose-headings:font-bold prose-headings:mt-8 prose-headings:mb-4
                prose-h1:text-gradient-to-r prose-h1:from-blue-300 prose-h1:to-indigo-300 prose-h1:text-3xl prose-h1:md:text-4xl
                prose-h2:text-cyan-300 prose-h2:text-2xl prose-h2:md:text-3xl prose-h2:border-b prose-h2:border-cyan-700/30 prose-h2:pb-2
                prose-h3:text-purple-300 prose-h3:text-xl prose-h3:md:text-2xl
                prose-h4:text-teal-300 prose-h4:text-lg prose-h4:md:text-xl
                prose-p:text-gray-300 prose-p:leading-relaxed prose-p:my-6
                prose-a:text-sky-400 prose-a:no-underline hover:prose-a:underline hover:prose-a:text-sky-300
                prose-img:rounded-xl prose-img:shadow-lg prose-img:mx-auto prose-img:my-8
                prose-strong:text-yellow-200
                prose-ul:my-6 prose-ul:pl-6 prose-li:my-2
                prose-ol:my-6 prose-ol:pl-6 
                prose-li:marker:text-purple-400
                prose-blockquote:my-8 prose-blockquote:pl-6 prose-blockquote:border-l-4 prose-blockquote:border-blue-500/50 prose-blockquote:italic prose-blockquote:text-gray-400
                prose-hr:my-10 prose-hr:border-gray-700/50
                prose-table:border-collapse prose-table:border-gray-700/50 prose-table:my-8
                prose-th:bg-gray-800/80 prose-th:text-blue-300 prose-th:p-3 prose-th:border prose-th:border-gray-700/50
                prose-td:p-3 prose-td:border prose-td:border-gray-700/50
                prose-code:text-pink-300 prose-code:bg-gray-800/70 prose-code:px-1 prose-code:py-0.5 prose-code:rounded
                max-w-none">
                {!! $post->content !!}
            </div>
                                
                    <!-- Embedded Video (if available) -->
                    @if($post->video_url)
                        <div class="mt-12 pt-8 border-t border-gray-700/50">
                            <h3 class="text-lg font-semibold text-white mb-4">Video Content</h3>
                            <div class="flex justify-center">
                                <div id="video-embed-container" class="relative overflow-hidden rounded-lg shadow-lg aspect-video mb-3 w-full max-w-[320px]" data-video-url="{{ $post->video_url }}">
                                    <!-- Video embed will be inserted here via JavaScript -->
                                    <div class="absolute inset-0 bg-gray-800/80 flex items-center justify-center" id="video-placeholder">
                                        <div class="text-center">
                                            <div class="animate-pulse bg-purple-900/40 h-5 w-5 rounded-full flex items-center justify-center mx-auto mb-1">
                                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                </svg>
                                            </div>
                                            <span class="text-xs text-gray-400">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center mt-2">
                                <a href="{{ $post->video_url }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-purple-600/80 hover:bg-purple-700 rounded text-white text-xs font-medium transition-all duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Open in New Tab
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Attachments Section (Files) -->
                    @if($post->file)
                        <div class="mt-12 pt-8 border-t border-gray-700/50">
                            <h3 class="text-xl font-semibold text-white mb-6">Attachments</h3>
                            
                            <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700/40 hover:border-blue-500/40 transition-all duration-300 hover:shadow-lg hover:shadow-blue-900/20 group">
                                <div class="flex items-start">
                                    <div class="bg-blue-900/40 p-3 rounded-lg mr-4">
                                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-white mb-2">Document</h4>
                                        <p class="text-sm text-gray-400 mb-4">Attached file for this post</p>
                                        <a href="{{ $post->file }}" download class="inline-flex items-center px-4 py-2 bg-blue-600/80 hover:bg-blue-700 rounded-lg text-white text-sm font-medium transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-12 pt-8 border-t border-gray-700/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-400">Last updated:</span>
                                <span class="text-gray-300">{{ $post->updated_at->format('M d, Y, h:i A') }}</span>
                            </div>
                            <button onclick="history.back()" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Posts
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Posts - With significant spacing from main content -->
            <div class="mt-20 mb-6 flex items-center">
                <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-700/50 to-transparent"></div>
                <h3 class="mx-4 text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    Related Posts
                </h3>
                <div class="flex-grow h-px bg-gradient-to-r from-gray-700/50 via-gray-700/50 to-transparent"></div>
            </div>
            
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(App\Models\Post::where('id', '!=', $post->id)->latest()->take(5)->get() as $relatedPost)
                            <a href="{{ route('posts.show', $relatedPost->id) }}" class="block p-4 bg-gray-800/30 hover:bg-gray-700/50 rounded-lg border border-gray-700/30 hover:border-blue-500/30 hover:shadow-md transition-all duration-200">
                                <h4 class="font-medium text-white hover:text-blue-300 transition-colors duration-200">{{ $relatedPost->title }}</h4>
                                <p class="text-sm text-gray-400 line-clamp-2 mt-2">{{ Str::limit(strip_tags($relatedPost->content), 120) }}</p>
                                <div class="flex items-center mt-3 text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($relatedPost->created_at)->format('M d, Y') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make only external links open in new tab
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                const href = link.getAttribute('href');
                // Check if the link is external (starts with http and not pointing to our domain)
                if (href && href.startsWith('http') && !href.includes(window.location.hostname)) {
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                }
            });
            
            // Handle video embedding
            const videoContainer = document.getElementById('video-embed-container');
            if (videoContainer) {
                const videoUrl = videoContainer.getAttribute('data-video-url');
                const embedCode = getVideoEmbedCode(videoUrl);
                
                if (embedCode) {
                    // Replace placeholder with actual embed
                    videoContainer.innerHTML = embedCode;
                } else {
                    // Show fallback for unsupported video URLs
                    const placeholder = document.getElementById('video-placeholder');
                    if (placeholder) {
                        placeholder.innerHTML = `
                            <div class="text-center">
                                <div class="bg-purple-900/40 h-10 w-10 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-400">Unable to embed. Click below to view.</span>
                            </div>
                        `;
                    }
                }
            }
        });

        // Replace the existing getVideoEmbedCode function with this updated version:
        function getVideoEmbedCode(url) {
            if (!url) return null;
            
            // Extract video ID from YouTube URL
            const youtubeRegex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/;
            const youtubeMatch = url.match(youtubeRegex);
            
            if (youtubeMatch && youtubeMatch[1]) {
                const videoId = youtubeMatch[1];
                return `
                    <iframe 
                        class="w-full h-full absolute top-0 left-0 rounded-lg"
                        src="https://www.youtube.com/embed/${videoId}?rel=0"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>
                `;
            }
            
            // Vimeo remains the same
            const vimeoRegex = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
            const vimeoMatch = url.match(vimeoRegex);
            
            if (vimeoMatch && vimeoMatch[5]) {
                return `<iframe class="w-full h-full absolute top-0 left-0 rounded-lg" src="https://player.vimeo.com/video/${vimeoMatch[5]}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            }
            
            return null;
        }
    </script>
</x-app-layout>