<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 pt-16 sm:pt-24">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600/20 mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-1">Join a Classroom</h2>
                        <p class="text-gray-400">Enter the code provided by your teacher</p>
                    </div>
                    
                    @if(session('error'))
                        <div class="bg-red-900/50 backdrop-blur-sm border-l-4 border-red-500 text-red-300 p-4 mb-6 rounded-lg" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.classrooms.process-join') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Classroom Code</label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200" 
                                   placeholder="Enter the classroom code" 
                                   value="{{ old('code') }}" 
                                   required>
                            @error('code')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('student.classrooms.index') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                                Back to classrooms
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-blue-500 rounded-xl text-white font-medium shadow-lg shadow-blue-500/20 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Join Classroom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>