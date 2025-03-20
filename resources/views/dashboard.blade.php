<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Section -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 overflow-hidden shadow-xl">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59l-1.95-2.1a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z" clip-rule="evenodd" />
                        </svg>
                        Selamat Datang, {{ $user->user_name ?? $user->name }}!
                    </h3>
                    <p class="mt-3 text-gray-300">
                        Ini adalah pusat aktivitas Anda di platform GEN-IT. Mulai penjelajahan Anda di dunia teknologi.
                    </p>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Submissions Card -->
                <div class="bg-gradient-to-r from-blue-900/30 to-indigo-900/30 backdrop-blur-sm rounded-2xl border border-blue-700/30 overflow-hidden shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-100 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                    </svg>
                                    Submissions
                                </h3>
                                <p class="text-gray-300 mb-4">
                                    Manage your content submissions and check their status
                                </p>
                            </div>
                            @php
                                // For the submission status
                                $submissionStatusClass = $latestSubmissionStatus == 'accepted' ? 'bg-green-900/30 text-green-300 border-green-500/30' : 
                                                       ($latestSubmissionStatus == 'rejected' ? 'bg-red-900/30 text-red-300 border-red-500/30' : 
                                                       'bg-yellow-900/30 text-yellow-300 border-yellow-500/30');
                                
                                $indicatorClass = $latestSubmissionStatus == 'accepted' ? 'bg-green-400' : 
                                                ($latestSubmissionStatus == 'rejected' ? 'bg-red-400' : 'bg-yellow-400');
                            @endphp

                            <!-- Then use these variables in your HTML -->
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $submissionStatusClass }} backdrop-blur-sm border">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $indicatorClass }}"></span>
                                {{ ucfirst($latestSubmissionStatus ?? 'Pending') }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">Total Submissions</span>
                                <span class="text-lg font-medium text-white">{{ $submissionsCount ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ min(($submissionsCount ?? 0) * 10, 100) }}%"></div>
                            </div>
                        </div>
                        
                        <a href="{{ route('submissions.index') }}" 
                           class="mt-2 inline-flex w-full justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-blue-500/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View Submissions
                        </a>
                    </div>
                </div>
                
                <!-- Contact Card -->
                <div class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 backdrop-blur-sm rounded-2xl border border-purple-700/30 overflow-hidden shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-100 mb-2 flex items-center">
                                    Messages
                                </h3>
                                <p class="text-gray-300 mb-4">
                                    Manage your communications
                                </p>
                            </div>
                            @php
                                // For the messages status
                                $messageStatusClass = ($pendingMessagesCount ?? 0) > 0 ? 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30' : 'bg-green-900/30 text-green-300 border-green-500/30';
                                $messageIndicatorClass = ($pendingMessagesCount ?? 0) > 0 ? 'bg-yellow-400' : 'bg-green-400';
                            @endphp

                            <!-- And later -->
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $messageStatusClass }} backdrop-blur-sm border">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $messageIndicatorClass }}"></span>
                                {{ ($pendingMessagesCount ?? 0) > 0 ? 'Pending' : 'All Responded' }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">Messages Sent</span>
                                <span class="text-lg font-medium text-white">{{ $contactsCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">Messages Responded</span>
                                <span class="text-lg font-medium text-white">{{ $respondedMessagesCount ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-2.5">
                                <div class="{{ ($pendingMessagesCount ?? 0) > 0 ? 'bg-yellow-500' : 'bg-green-500' }} h-2.5 rounded-full" 
                                     style="width: {{ ($contactsCount > 0) ? (($respondedMessagesCount) / $contactsCount) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <a href="{{ route('contacts.index') }}" 
                           class="mt-2 inline-flex w-full justify-center items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-purple-500/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Manage Messages
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Activity - Expanded -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 overflow-hidden shadow-xl">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-gray-100 flex items-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Your Activity
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile -->
                        <div class="bg-gray-800/50 rounded-xl p-5 border border-gray-700/30">
                            <h4 class="font-medium text-gray-100 flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Profile Status
                            </h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-300">Completion Status</span>
                                    <span class="text-sm font-medium px-2 py-1 rounded 
                                        {{ $profileComplete ? 'bg-green-900/30 text-green-300 border-green-500/30' : 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30' }} border">
                                        {{ $profileComplete ? 'Complete' : 'Incomplete' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-300">Last Updated</span>
                                    <span class="text-sm font-medium text-gray-200">{{ $user->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-300">Member Since</span>
                                    <span class="text-sm font-medium text-gray-200">{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block w-full text-center mt-3 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="bg-gray-800/50 rounded-xl p-5 border border-gray-700/30">
                            <h4 class="font-medium text-gray-100 flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Recent Activities
                            </h4>
                            <div class="space-y-3">
                                @if(isset($recentActivities) && count($recentActivities) > 0)
                                    @foreach($recentActivities as $activity)
                                        <div class="text-sm border-l-2 border-blue-500 pl-3 py-1">
                                            <div class="font-medium text-gray-200">{{ $activity->description }}</div>
                                            <div class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-sm text-gray-400 italic py-4 text-center">No recent activities found</div>
                                @endif
                                <a href="#" class="block w-full text-center mt-3 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    View All Activities
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 overflow-hidden shadow-xl">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-gray-100 mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        Statistics Overview
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-800/50 p-4 rounded-xl border border-blue-500/30 transition-all duration-300 hover:shadow-blue-500/10 hover:border-blue-500/50">
                            <div class="text-sm text-gray-400">Content Views</div>
                            <div class="text-2xl font-semibold text-gray-100">{{ $viewsCount ?? 0 }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if(isset($viewsChange) && $viewsChange > 0)
                                    <span class="text-green-400">↑ {{ $viewsChange }}%</span> from last month
                                @elseif(isset($viewsChange) && $viewsChange < 0)
                                    <span class="text-red-400">↓ {{ abs($viewsChange) }}%</span> from last month
                                @else
                                    Same as last month
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-800/50 p-4 rounded-xl border border-green-500/30 transition-all duration-300 hover:shadow-green-500/10 hover:border-green-500/50">
                            <div class="text-sm text-gray-400">Submissions</div>
                            <div class="text-2xl font-semibold text-gray-100">{{ $submissionsCount ?? 0 }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if(isset($submissionsChange) && $submissionsChange > 0)
                                    <span class="text-green-400">↑ {{ $submissionsChange }}%</span> from last month
                                @elseif(isset($submissionsChange) && $submissionsChange < 0)
                                    <span class="text-red-400">↓ {{ abs($submissionsChange) }}%</span> from last month
                                @else
                                    Same as last month
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-800/50 p-4 rounded-xl border border-yellow-500/30 transition-all duration-300 hover:shadow-yellow-500/10 hover:border-yellow-500/50">
                            <div class="text-sm text-gray-400">Materials Accessed</div>
                            <div class="text-2xl font-semibold text-gray-100">{{ $materialsCount ?? 0 }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if(isset($materialsChange) && $materialsChange > 0)
                                    <span class="text-green-400">↑ {{ $materialsChange }}%</span> from last month
                                @elseif(isset($materialsChange) && $materialsChange < 0)
                                    <span class="text-red-400">↓ {{ abs($materialsChange) }}%</span> from last month
                                @else
                                    Same as last month
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-800/50 p-4 rounded-xl border border-purple-500/30 transition-all duration-300 hover:shadow-purple-500/10 hover:border-purple-500/50">
                            <div class="text-sm text-gray-400">Last Activity</div>
                            <div class="text-lg font-semibold text-gray-100">{{ $user->updated_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $user->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
