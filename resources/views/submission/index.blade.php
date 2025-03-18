<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Submissions') }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $submissions->total() ?? 0 }} {{ Str::plural('submission', $submissions->total() ?? 0) }} made
                </div>
                <a href="{{ route('submissions.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Submission
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if(session('success'))
                <div class="mb-6 bg-green-900/30 text-green-300 p-4 rounded-lg border border-green-500/30">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Create New Submission Card -->
            <div class="bg-gradient-to-r from-blue-900/50 to-purple-900/50 backdrop-blur-sm rounded-2xl border border-blue-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-100 mb-2">
                                Share your work or resources
                            </h3>
                            <p class="text-gray-300 mb-4">
                                Submit documents, links, or resources to be approved and shared with the community.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-blue-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('submissions.create') }}" 
                        class="mt-2 inline-flex w-full sm:w-auto justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-blue-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Submission
                    </a>
                </div>
            </div>
            
            <!-- Submission History Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-200">Your Submission History</h3>
                </div>
                <div class="p-6">
                    @if(count($submissions) > 0)
                        <div class="space-y-6">
                            @foreach($submissions as $submission)
                                <div class="bg-gray-800/30 rounded-xl overflow-hidden shadow-lg border border-gray-700/30 transition-all duration-300 hover:border-blue-500/50 hover:shadow-blue-500/5">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-gray-100 mb-2">
                                                    {{ $submission->title }}
                                                </h3>
                                                <div class="text-sm text-gray-400 mb-3">
                                                    Submitted {{ $submission->created_at->diffForHumans() }}
                                                    @if($submission->type)
                                                        • Type: <span class="text-blue-300">{{ $submission->typeOption->value }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                @php
                                                    $statusClass = '';
                                                    $statusText = 'Unknown';
                                                    
                                                    if ($submission->status) {
                                                        $option = $submission->statusOption;
                                                        if ($option) {
                                                            $statusText = ucfirst($option->value);
                                                            
                                                            if ($option->value == 'pending') {
                                                                $statusClass = 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30';
                                                            } elseif ($option->value == 'approved') {
                                                                $statusClass = 'bg-green-900/30 text-green-300 border-green-500/30';
                                                            } elseif ($option->value == 'rejected') {
                                                                $statusClass = 'bg-red-900/30 text-red-300 border-red-500/30';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $statusClass }} backdrop-blur-sm border">
                                                    <span class="w-2 h-2 rounded-full mr-2 
                                                        {{ $statusText == 'Pending' ? 'bg-yellow-400' : 
                                                        ($statusText == 'Approved' ? 'bg-green-400' : 'bg-red-400') }}"></span>
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4 text-gray-300">
                                            <div class="line-clamp-3">{{ $submission->content }}</div>
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-3 mt-4">
                                            @if($submission->file)
                                                <a href="{{ $submission->file }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-200 text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    View Document
                                                </a>
                                            @endif
                                            
                                            @if($submission->link)
                                                <a href="{{ $submission->link }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-200 text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    View Link
                                                </a>
                                            @endif
                                            
                                            @if($submission->approve_at && $submission->approve_by)
                                                <div class="text-sm text-green-400">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Approved {{ $submission->approve_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="mt-8">
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">No Submissions Yet</h3>
                            <p class="text-gray-400 mb-6">You haven't made any submissions yet.</p>
                            <a href="{{ route('submissions.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-xl hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Your First Submission
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>