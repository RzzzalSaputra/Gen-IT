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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Assignments Card -->
                <div class="bg-gradient-to-r from-emerald-900/30 to-teal-900/30 backdrop-blur-sm rounded-2xl border border-emerald-700/30 overflow-hidden shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="max-w-[65%]">
                                <h3 class="text-xl font-bold text-gray-100 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    Assignments
                                </h3>
                                <p class="text-gray-300 mb-4">
                                    Track your assignments and access new learning materials
                                </p>
                            </div>
                            @php
                                // For assignment reminders
                                $hasUpcomingDeadlines = ($upcomingDeadlinesCount ?? 0) > 0;
                                $deadlineStatusClass = $hasUpcomingDeadlines ? 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30' : 'bg-green-900/30 text-green-300 border-green-500/30';
                                $deadlineIndicatorClass = $hasUpcomingDeadlines ? 'bg-yellow-400' : 'bg-green-400';
                            @endphp

                            <span class="inline-flex items-center whitespace-nowrap px-3 py-1 rounded-lg text-sm font-medium {{ $deadlineStatusClass }} backdrop-blur-sm border">
                                <span class="w-2 h-2 flex-shrink-0 rounded-full mr-2 {{ $deadlineIndicatorClass }}"></span>
                                {{ $hasUpcomingDeadlines ? ($upcomingDeadlinesCount . ' Left') : 'Complete' }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">New Materials</span>
                                <span class="text-lg font-medium text-white">{{ $newMaterialsCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-300">Last Graded</span>
                                <span class="text-lg font-medium text-white">{{ $lastGrade ?? 'N/A' }}</span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-2.5 mt-2">
                                <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $assignmentCompletionRate ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <a href="{{ route('student.classrooms.index') }}" 
                           class="mt-2 inline-flex w-full justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            View Classrooms
                        </a>
                    </div>
                </div>

                <!-- Submissions Card -->
                <div class="bg-gradient-to-r from-blue-900/30 to-indigo-900/30 backdrop-blur-sm rounded-2xl border border-blue-700/30 overflow-hidden shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="max-w-[65%]">
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

                            <span class="inline-flex items-center whitespace-nowrap px-3 py-1 rounded-lg text-sm font-medium {{ $submissionStatusClass }} backdrop-blur-sm border">
                                <span class="w-2 h-2 flex-shrink-0 rounded-full mr-2 {{ $indicatorClass }}"></span>
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
                            <div class="max-w-[65%]">
                                <h3 class="text-xl font-bold text-gray-100 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
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

                            <span class="inline-flex items-center whitespace-nowrap px-3 py-1 rounded-lg text-sm font-medium {{ $messageStatusClass }} backdrop-blur-sm border">
                                <span class="w-2 h-2 flex-shrink-0 rounded-full mr-2 {{ $messageIndicatorClass }}"></span>
                                {{ ($pendingMessagesCount ?? 0) > 0 ? 'Pending' : 'Responded' }}
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
                                     style="width: {{ ($contactsCount > 0) ? (($respondedMessagesCount ?? 0) / $contactsCount) * 100 : 0 }}%"></div>
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
        </div>
    </div>
</x-app-layout>