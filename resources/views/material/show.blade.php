<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $material->title }}
            </h2>
            <a href="{{ route('materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-700/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Materials
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-8 sm:pt-16 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Material Header Section -->
            <div class="bg-gradient-to-r from-blue-900/30 via-indigo-900/30 to-purple-900/30 backdrop-blur-sm rounded-3xl border border-blue-500/20 shadow-xl shadow-blue-500/5 overflow-hidden mb-10">
                <div class="p-8 md:p-10">
                    @if($material->img)
                        <div class="-mt-10 -mx-10 mb-10">
                            <div class="relative h-64 md:h-80 overflow-hidden">
                                <img src="{{ asset('storage/' . $material->img) }}" alt="{{ $material->title }}" 
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-80"></div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($material->layoutOption)
                            <div class="inline-flex items-center px-3 py-1.5 bg-blue-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-blue-300 border border-blue-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                                {{ $material->layoutOption->value }}
                            </div>
                        @endif
                        @if($material->typeOption)
                            <div class="inline-flex items-center px-3 py-1.5 bg-purple-900/40 backdrop-blur-sm rounded-lg text-xs font-medium text-purple-300 border border-purple-500/30">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $material->typeOption->value }}
                            </div>
                        @endif
                        <div class="inline-flex items-center px-3 py-1.5 bg-gray-700/50 backdrop-blur-sm rounded-lg text-xs font-medium text-gray-300 border border-gray-600/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $material->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">{{ $material->title }}</h1>
                    
                    <div class="flex flex-wrap items-center text-sm text-gray-400 gap-x-8 gap-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ $material->read_counter ?? 0 }} reads
                        </div>
                        @if($material->file)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                {{ $material->download_counter ?? 0 }} downloads
                            </div>
                        @endif
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $material->creator ? $material->creator->name : 'Admin' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Material Content Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Main Content -->
                <div class="md:col-span-3">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl border border-gray-700/50 shadow-xl overflow-hidden mb-10">
                        <div class="p-8 md:p-10">
                            <!-- Table of Contents (for long articles) -->
                            @if(strlen($material->content) > 1000)
                                <div class="mb-10 p-6 bg-gray-900/50 rounded-2xl border border-gray-700/50 shadow-inner">
                                    <h3 class="text-xl font-bold text-gray-100 mb-4">Daftar isi</h3>
                                    <div id="table-of-contents" class="text-blue-400">
                                        <!-- JavaScript will populate this -->
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Article Content -->
                            <div id="material-content" class="prose lg:prose-xl dark:prose-invert text-gray-100 
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
                                {!! $material->content !!}
                            </div>
                            
                            <div class="mt-16 pt-8 border-t border-gray-700/50">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">Terakhir Diperbaharui:</span>
                                        <span class="text-gray-300">{{ $material->updated_at->format('M d, Y, h:i A') }}</span>
                                    </div>
                                    <a href="{{ route('materials.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm text-white transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        kembali ke materi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <!-- File Download Section -->
                    @if($material->file)
                        <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden mb-8">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Attachment
                                </h3>
                                @php
                                    $fileExtension = pathinfo($material->file, PATHINFO_EXTENSION);
                                @endphp
                                <div class="bg-gray-900 rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            @if(in_array(strtolower($fileExtension), ['pdf']))
                                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-red-900/30 flex items-center justify-center mr-3 border border-red-700/30">
                                                    <span class="text-xs font-bold text-red-400">PDF</span>
                                                </div>
                                            @elseif(in_array(strtolower($fileExtension), ['doc', 'docx']))
                                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-900/30 flex items-center justify-center mr-3 border border-blue-700/30">
                                                <span class="text-xs font-bold text-blue-400">DOC</span>
                                                </div>
                                            @elseif(in_array(strtolower($fileExtension), ['xls', 'xlsx']))
                                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-green-900/30 flex items-center justify-center mr-3 border border-green-700/30">
                                                    <span class="text-xs font-bold text-green-400">XLS</span>
                                                </div>
                                            @elseif(in_array(strtolower($fileExtension), ['ppt', 'pptx']))
                                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-orange-900/30 flex items-center justify-center mr-3 border border-orange-700/30">
                                                    <span class="text-xs font-bold text-orange-400">PPT</span>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-gray-800 flex items-center justify-center mr-3 border border-gray-700">
                                                    <span class="text-xs font-bold text-gray-400">{{ strtoupper($fileExtension) }}</span>
                                                </div>
                                            @endif
                                            <div class="truncate">
                                                <p class="text-sm font-medium text-gray-200 truncate">{{ basename($material->file) }}</p>
                                                <p class="text-xs text-gray-500">Click to download</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('materials.download', $material->id) }}" 
                                    class="ml-4 flex-shrink-0 p-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Related Materials -->
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-gray-700/50 shadow-xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                </svg>
                                Sumber Belajar Lebih Banyak
                            </h3>
                            <div class="space-y-4">
                                @foreach(App\Models\Material::where('id', '!=', $material->id)->where('type', $material->type)->take(5)->get() as $relatedMaterial)
                                    <a href="{{ route('materials.show', $relatedMaterial->id) }}" class="flex items-start p-3 hover:bg-gray-700/30 rounded-lg transition-colors duration-200">
                                        @if($relatedMaterial->img)
                                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 mr-3">
                                                <img src="{{ asset('storage/' . $relatedMaterial->img) }}" alt="{{ $relatedMaterial->title }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-800/30 to-blue-800/30 flex-shrink-0 mr-3 flex items-center justify-center border border-purple-700/30">
                                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-200 line-clamp-2">{{ $relatedMaterial->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $relatedMaterial->created_at->format('M d, Y') }}</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate Table of Contents for long articles
            const content = document.getElementById('material-content');
            const toc = document.getElementById('table-of-contents');
            
            if (content && toc) {
                const headings = content.querySelectorAll('h1, h2, h3, h4');
                
                if (headings.length > 0) {
                    const tocList = document.createElement('ul');
                    tocList.className = 'space-y-2';
                    
                    headings.forEach((heading, index) => {
                        // Add ID to the heading if it doesn't have one
                        if (!heading.id) {
                            heading.id = 'heading-' + index;
                        }
                        
                        const listItem = document.createElement('li');
                        const link = document.createElement('a');
                        link.href = '#' + heading.id;
                        link.textContent = heading.textContent;
                        
                        // Style based on heading level
                        if (heading.tagName === 'H1') {
                            link.className = 'block hover:text-blue-300 transition-colors duration-200';
                        } else if (heading.tagName === 'H2') {
                            link.className = 'block pl-4 hover:text-blue-300 transition-colors duration-200';
                        } else if (heading.tagName === 'H3') {
                            link.className = 'block pl-8 hover:text-blue-300 transition-colors duration-200';
                        } else {
                            link.className = 'block pl-12 hover:text-blue-300 transition-colors duration-200';
                        }
                        
                        listItem.appendChild(link);
                        tocList.appendChild(listItem);
                        
                        // Add click event for smooth scrolling
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            document.querySelector(this.getAttribute('href')).scrollIntoView({
                                behavior: 'smooth'
                            });
                        });
                    });
                    
                    toc.appendChild(tocList);
                } else {
                    toc.parentElement.style.display = 'none';
                }
            }

            // Make external links open in new tab
            const links = document.querySelectorAll('#material-content a[href^="http"]');
            links.forEach(link => {
                if (!link.target) {
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                }
            });
            
            // Add copy buttons to code blocks
            const codeBlocks = document.querySelectorAll('#material-content pre');
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
                        const originalInnerHTML = this.innerHTML;
                        this.innerHTML = `<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>`;
                        
                        setTimeout(() => {
                            this.innerHTML = originalInnerHTML;
                        }, 2000);
                    });
                });
            });
        });
    </script>
</x-app-layout>