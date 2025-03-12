<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $article->title }}
            </h2>
            <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Articles
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header Section -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-500/5 overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($article->typeOption)
                            <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/50 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-700/30">
                                {{ $article->typeOption->name }}
                            </div>
                        @endif
                        @if($article->statusOption)
                            <div class="inline-flex items-center px-3 py-1.5 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs font-medium text-gray-300 border border-gray-600/30">
                                {{ $article->statusOption->name }}
                            </div>
                        @endif
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $article->title }}</h1>
                    
                    <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Written by: {{ $article->writer }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Published: {{ \Carbon\Carbon::parse($article->post_time)->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Article Content Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    <div id="article-content" class="prose lg:prose-xl dark:prose-invert text-gray-100 
                        prose-headings:font-bold 
                        
                        prose-h1:text-3xl prose-h1:md:text-4xl prose-h1:bg-clip-text prose-h1:text-transparent 
                        prose-h1:bg-gradient-to-r prose-h1:from-blue-400 prose-h1:to-purple-400 
                        prose-h1:border-b prose-h1:border-blue-500/30 prose-h1:pb-2 prose-h1:mb-6
                        
                        prose-h2:text-2xl prose-h2:md:text-3xl prose-h2:text-blue-300 
                        prose-h2:mt-12 prose-h2:mb-6 prose-h2:pb-1 prose-h2:border-b prose-h2:border-blue-600/20
                        
                        prose-h3:text-xl prose-h3:md:text-2xl prose-h3:text-indigo-300 
                        prose-h3:mt-8 prose-h3:mb-4
                        
                        prose-h4:text-lg prose-h4:md:text-xl prose-h4:text-purple-300 
                        prose-h4:mt-6 prose-h4:mb-3
                        
                        prose-h5:text-base prose-h5:md:text-lg prose-h5:text-pink-300 
                        prose-h5:font-semibold prose-h5:mt-5 prose-h5:mb-3
                        
                        prose-h6:text-sm prose-h6:md:text-base prose-h6:text-cyan-300 
                        prose-h6:font-semibold prose-h6:mt-4 prose-h6:mb-2
                        
                        prose-p:text-gray-100 prose-p:leading-relaxed prose-p:my-4
                        
                        prose-strong:text-blue-200 prose-strong:font-semibold
                        prose-em:text-indigo-200
                        
                        prose-a:text-sky-400 prose-a:no-underline hover:prose-a:underline 
                        hover:prose-a:text-sky-300 prose-a:transition-colors prose-a:duration-200
                        
                        prose-code:text-amber-200 prose-code:bg-gray-900/80 prose-code:border 
                        prose-code:border-amber-900/30 prose-code:px-1.5 prose-code:py-0.5 
                        prose-code:rounded prose-code:text-sm prose-code:font-mono
                        
                        prose-pre:bg-gradient-to-b prose-pre:from-gray-800 prose-pre:to-gray-900 
                        prose-pre:border prose-pre:border-gray-700/60 prose-pre:rounded-lg
                        prose-pre:shadow-inner prose-pre:shadow-black/20 prose-pre:overflow-x-auto
                        prose-pre:my-8 prose-pre:p-6 prose-pre:relative
                        
                        prose-blockquote:border-l-4 prose-blockquote:border-indigo-500
                        prose-blockquote:bg-indigo-900/20 prose-blockquote:px-6 
                        prose-blockquote:py-4 prose-blockquote:rounded-r-md
                        prose-blockquote:text-indigo-100 prose-blockquote:not-italic
                        prose-blockquote:shadow-md prose-blockquote:my-6
                        
                        prose-li:text-gray-100 prose-li:my-1
                        prose-li:marker:text-blue-400
                        prose-ol:text-gray-100 prose-ol:marker:text-purple-400
                        prose-ul:text-gray-100 prose-ul:my-4
                        
                        prose-table:border prose-table:border-gray-700 prose-table:my-6
                        prose-th:bg-gray-800 prose-th:text-gray-100 prose-th:p-3 prose-th:font-semibold
                        prose-td:border prose-td:border-gray-700 prose-td:p-3 prose-td:text-gray-200
                        
                        prose-img:rounded-xl prose-img:shadow-lg prose-img:my-8 prose-img:mx-auto
                        prose-hr:border-gray-700 prose-hr:my-10
                        
                        [&_ol>li]:pl-2 [&_ul>li]:pl-2 [&_li]:my-1.5
                        [&_ol>li::marker]:text-indigo-400 [&_ul>li::marker]:text-blue-400
                        
                        [&_pre]:backdrop-blur-sm [&_pre]:shadow-lg
                        [&_pre_code]:text-blue-100 [&_pre_code]:font-mono [&_pre_code]:text-sm
                        
                        [&_.note]:bg-blue-900/20 [&_.note]:border-l-4 [&_.note]:border-blue-500 
                        [&_.note]:p-4 [&_.note]:rounded-r-md [&_.note]:text-blue-100 [&_.note]:my-6
                        
                        [&_.warning]:bg-amber-900/20 [&_.warning]:border-l-4 [&_.warning]:border-amber-500 
                        [&_.warning]:p-4 [&_.warning]:rounded-r-md [&_.warning]:text-amber-100 [&_.warning]:my-6
                        
                        [&_.tip]:bg-emerald-900/20 [&_.tip]:border-l-4 [&_.tip]:border-emerald-500 
                        [&_.tip]:p-4 [&_.tip]:rounded-r-md [&_.tip]:text-emerald-100 [&_.tip]:my-6
                        
                        [&_.step]:bg-gray-800/40 [&_.step]:p-5 [&_.step]:rounded-lg 
                        [&_.step]:border [&_.step]:border-blue-800/30 [&_.step]:my-6 [&_.step]:shadow-md
                        
                        [&_.step-title]:text-cyan-300 [&_.step-title]:font-bold [&_.step-title]:text-lg 
                        [&_.step-title]:flex [&_.step-title]:items-center [&_.step-title]:gap-2 [&_.step-title]:mb-3
                        
                        [&_.step-number]:bg-blue-900/50 [&_.step-number]:text-blue-200 [&_.step-number]:w-6 
                        [&_.step-number]:h-6 [&_.step-number]:rounded-full [&_.step-number]:flex 
                        [&_.step-number]:items-center [&_.step-number]:justify-center [&_.step-number]:text-sm
                        [&_.step-number]:border [&_.step-number]:border-blue-700/50
                        
                        max-w-none">
                        {!! $article->content !!}
                    </div>
                    
                    <div class="mt-16 pt-8 border-t border-gray-700/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-400">Last updated:</span>
                                <span class="text-gray-300">{{ $article->updated_at->format('M d, Y, h:i A') }}</span>
                            </div>
                            <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Articles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Articles -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        Related Articles
                    </h3>
                    <div class="space-y-4">
                        @foreach(App\Models\Article::where('id', '!=', $article->id)->latest()->take(5)->get() as $relatedArticle)
                            <a href="{{ route('articles.show', $relatedArticle->id) }}" class="block p-3 bg-gray-800/30 hover:bg-gray-700/50 rounded-lg border border-gray-700/30 hover:border-blue-500/30 transition-all duration-200">
                                <h4 class="font-medium text-white hover:text-blue-300 transition-colors duration-200">{{ $relatedArticle->title }}</h4>
                                <p class="text-sm text-gray-400 line-clamp-1 mt-1">{{ $relatedArticle->summary }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make external links open in new tab
            const links = document.querySelectorAll('#article-content a[href^="http"]');
            links.forEach(link => {
                if (!link.target) {
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                }
            });
            
            // Add copy buttons to code blocks
            const codeBlocks = document.querySelectorAll('#article-content pre');
            codeBlocks.forEach((block, index) => {
                block.id = 'code-block-' + index;
                
                const copyButton = document.createElement('button');
                copyButton.className = 'absolute top-3 right-3 p-2 rounded-md bg-blue-900/50 text-blue-200 hover:bg-blue-700/70 hover:text-white transition-colors duration-200 backdrop-blur-sm border border-blue-700/30';
                copyButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>`;
                
                // Make the code block container relative for positioning the button
                block.style.position = 'relative';
                
                // Insert the button
                block.appendChild(copyButton);
                
                // Add click event
                copyButton.addEventListener('click', function() {
                    const code = block.querySelector('code').innerText;
                    navigator.clipboard.writeText(code).then(() => {
                        copyButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>`;
                        setTimeout(() => {
                            copyButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>`;
                        }, 2000);
                    });
                });
            });
        });
    </script>
</x-app-layout>