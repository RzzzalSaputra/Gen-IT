<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight break-words">
                {{ $article->title }}
            </h2>
            <a href="{{ route('articles.index') }}" class="self-start inline-flex items-center px-3 py-1.5 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-md text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Artikel
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-4 pb-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header Section -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-lg border border-blue-500/20 shadow-lg overflow-hidden mb-6">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="flex flex-wrap gap-2 mb-3 sm:mb-4">
                        @if($article->typeOption)
                            <div class="inline-flex items-center px-2 py-1 bg-blue-900/50 backdrop-blur-sm rounded text-xs font-medium text-blue-300 border border-blue-700/30">
                                {{ $article->typeOption->name }}
                            </div>
                        @endif
                        @if($article->statusOption)
                            <div class="inline-flex items-center px-2 py-1 bg-gray-700/50 backdrop-blur-sm rounded text-xs font-medium text-gray-300 border border-gray-600/30">
                                {{ $article->statusOption->name }}
                            </div>
                        @endif
                    </div>
                    
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-3 sm:mb-4 leading-tight">{{ $article->title }}</h1>
                    
                    <div class="flex flex-col sm:flex-row text-xs text-gray-400 gap-y-2 sm:gap-x-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Ditulis Oleh : {{ $article->writer }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Diterbitkan: {{ \Carbon\Carbon::parse($article->post_time)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Article Content Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700/50 shadow-lg overflow-hidden mb-6">
                <div class="p-4 sm:p-6 md:p-8">
                    <div id="article-content" class="prose max-w-none text-gray-100
                        prose-headings:font-bold 
                        
                        prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:bg-clip-text prose-h1:text-transparent 
                        prose-h1:bg-gradient-to-r prose-h1:from-blue-400 prose-h1:to-purple-400 
                        prose-h1:border-b prose-h1:border-blue-500/30 prose-h1:pb-2 prose-h1:mb-4
                        
                        prose-h2:text-lg sm:prose-h2:text-xl prose-h2:text-blue-300 
                        prose-h2:mt-6 prose-h2:mb-3 prose-h2:pb-1 prose-h2:border-b prose-h2:border-blue-600/20
                        
                        prose-h3:text-base sm:prose-h3:text-lg prose-h3:text-indigo-300 
                        prose-h3:mt-5 prose-h3:mb-2
                        
                        prose-h4:text-sm sm:prose-h4:text-base prose-h4:text-purple-300 
                        prose-h4:mt-4 prose-h4:mb-2
                        
                        prose-p:text-gray-100 prose-p:leading-relaxed prose-p:my-2 prose-p:text-sm sm:prose-p:text-base
                        
                        prose-strong:text-blue-200 prose-strong:font-semibold
                        prose-em:text-indigo-200
                        
                        prose-a:text-sky-400 prose-a:no-underline hover:prose-a:underline 
                        hover:prose-a:text-sky-300 prose-a:transition-colors prose-a:duration-200
                        
                        prose-code:text-amber-200 prose-code:bg-gray-900/80 prose-code:border 
                        prose-code:border-amber-900/30 prose-code:px-1 prose-code:py-0.5 
                        prose-code:rounded prose-code:text-xs sm:prose-code:text-sm prose-code:font-mono
                        
                        prose-pre:bg-gray-900 prose-pre:border prose-pre:border-gray-700/60 
                        prose-pre:rounded-md prose-pre:my-3 prose-pre:p-3 prose-pre:relative prose-pre:text-xs
                        prose-pre:overflow-x-auto prose-pre:whitespace-pre-wrap prose-pre:break-words
                        
                        prose-blockquote:border-l-4 prose-blockquote:border-indigo-500
                        prose-blockquote:bg-indigo-900/20 prose-blockquote:px-3 
                        prose-blockquote:py-2 prose-blockquote:rounded-r-md
                        prose-blockquote:text-indigo-100 prose-blockquote:not-italic
                        prose-blockquote:shadow-md prose-blockquote:my-3 prose-blockquote:text-sm
                        
                        prose-ul:text-sm prose-ul:my-2 prose-ul:pl-4
                        prose-ol:text-sm prose-ol:my-2 prose-ol:pl-4
                        
                        prose-li:text-gray-100 prose-li:my-0.5 prose-li:text-sm
                        prose-li:marker:text-blue-400
                        
                        prose-table:border prose-table:border-gray-700 prose-table:my-3 prose-table:text-xs
                        prose-th:bg-gray-800 prose-th:text-gray-100 prose-th:p-1.5 prose-th:font-semibold
                        prose-td:border prose-td:border-gray-700 prose-td:p-1.5 prose-td:text-gray-200
                        
                        prose-img:rounded-lg prose-img:shadow-lg prose-img:my-4 prose-img:mx-auto prose-img:max-w-full prose-img:h-auto
                        prose-hr:border-gray-700 prose-hr:my-6
                        
                        max-w-none overflow-hidden">
                        {!! $article->content !!}
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-700/50">
                        <div class="flex flex-col gap-3">
                            <div>
                                <span class="text-xs font-medium text-gray-400">Terakhir Diperbaharui:</span>
                                <span class="text-xs text-gray-300">{{ $article->updated_at->format('M d, Y, h:i A') }}</span>
                            </div>
                            <a href="{{ route('articles.index') }}" class="self-start inline-flex items-center px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-md text-xs text-white transition-colors duration-200">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Articles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Artikel Terkait -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg border border-gray-700/50 shadow-lg overflow-hidden">
                <div class="p-4">
                    <h3 class="text-base font-semibold text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        Artikel Terkait
                    </h3>
                    <div class="space-y-2.5">
                        @foreach(App\Models\Article::where('id', '!=', $article->id)->latest()->take(5)->get() as $relatedArticle)
                            <a href="{{ route('articles.show', $relatedArticle->id) }}" class="block p-2 bg-gray-800/30 hover:bg-gray-700/50 rounded-md border border-gray-700/30 hover:border-blue-500/30 transition-all duration-200">
                                <h4 class="font-medium text-sm text-white line-clamp-1">{{ $relatedArticle->title }}</h4>
                                <p class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ $relatedArticle->summary }}</p>
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
                
                // Make code blocks scrollable horizontally on mobile
                block.style.overflowX = 'auto';
                block.style.webkitOverflowScrolling = 'touch';
                
                const copyButton = document.createElement('button');
                copyButton.className = 'absolute top-1 right-1 p-1 rounded bg-blue-900/50 text-blue-200 hover:bg-blue-700/70 hover:text-white transition-colors duration-200 text-xs border border-blue-700/30';
                copyButton.style.fontSize = '10px';
                copyButton.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        copyButton.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>`;
                        setTimeout(() => {
                            copyButton.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>`;
                        }, 2000);
                    });
                });
            });
            
            // Handle images responsively
            const images = document.querySelectorAll('#article-content img');
            images.forEach(img => {
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
            });
            
            // Make tables responsive
            const allTables = document.querySelectorAll('#article-content table');
            allTables.forEach(table => {
                const wrapper = document.createElement('div');
                wrapper.style.overflowX = 'auto';
                wrapper.style.width = '100%';
                wrapper.style.marginBottom = '1rem';
                wrapper.style.WebkitOverflowScrolling = 'touch';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });

            // Adjust code blocks for mobile
            const allCode = document.querySelectorAll('#article-content code');
            allCode.forEach(code => {
                if (code.parentNode.tagName !== 'PRE') {
                    code.style.wordBreak = 'break-word';
                    code.style.whiteSpace = 'normal';
                }
            });
        });
    </script>
</x-app-layout>