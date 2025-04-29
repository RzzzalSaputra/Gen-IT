@extends('layouts.teacher')

@section('content')
<div class="container py-4">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 space-y-3 sm:space-y-0">
        <h1 class="text-2xl font-black text-gray-800 dark:text-white">Kelas Saya</h1>
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <button onclick="openJoinModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border-4 border-gray-900 rounded-none font-black text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                </svg>
                Gabung Kelas
            </button>
            <button onclick="openCreateModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border-4 border-gray-900 rounded-none font-black text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Buat Kelas Baru
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
        @forelse($classrooms as $classroom)
            <div class="bg-white dark:bg-gray-800 overflow-hidden border-4 border-gray-900 dark:border-gray-600 shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)] rounded-none hover:-translate-y-1 transition-transform">
                <div class="p-4 sm:p-6">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-800 dark:text-white mb-3">{{ $classroom->name }}</h3>
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4 px-2 py-1 bg-gray-100 dark:bg-gray-700 border-2 border-gray-900 dark:border-gray-600 inline-block">Kode: {{ $classroom->code }}</p>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 text-sm sm:text-base font-bold leading-relaxed">{{ Str::limit($classroom->description ?? 'Tidak ada deskripsi', 120) }}</p>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5 space-y-3 sm:space-y-0 text-sm font-bold text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <div class="p-2 rounded-none bg-blue-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            @if(method_exists($classroom, 'students'))
                                {{ $classroom->students()->count() }} siswa
                            @else
                                0 siswa
                            @endif
                        </div>
                        <div class="flex items-center">
                            <div class="p-2 rounded-none bg-yellow-500 bg-opacity-70 border-2 border-gray-900 dark:border-gray-600 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            @if(method_exists($classroom, 'assignments'))
                                {{ $classroom->assignments()->whereNull('delete_at')->count() }} tugas
                            @else
                                0 tugas
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t-4 border-gray-900 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                        <span class="text-sm font-bold text-gray-500 dark:text-gray-400">Dibuat: {{ $classroom->create_at ? $classroom->create_at->format('M d, Y') : 'Tidak Ada' }}</span>
                        <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border-3 border-gray-900 rounded-none font-black text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            Kelola
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-blue-50 dark:bg-blue-900 border-4 border-gray-900 dark:border-gray-600 text-blue-700 dark:text-blue-200 p-6 sm:p-8 rounded-none shadow-[6px_6px_0px_0px_rgba(0,0,0,0.7)]">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <svg class="h-12 w-12 text-blue-500 mx-auto sm:mx-0 sm:mr-6 mb-4 sm:mb-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-center sm:text-left">
                            <p class="text-xl font-black mb-3">Tidak ada kelas ditemukan</p>
                            <p class="mb-5 text-sm sm:text-base font-bold">Anda belum membuat kelas apapun. Mulai dengan membuat kelas pertama Anda atau bergabung dengan kelas yang sudah ada!</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button onclick="openJoinModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border-4 border-gray-900 rounded-none font-black text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                    Gabung Kelas
                                </button>
                                <button onclick="openCreateModal()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border-4 border-gray-900 rounded-none font-black text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,0.7)] hover:-translate-y-1 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Buat Kelas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Include both modals -->
@include('teacher.classrooms.join-modal')
@include('teacher.classrooms.create-modal')

<!-- JavaScript for modal functionality (unchanged) -->
<script>
    function openJoinModal() {
        document.getElementById('joinClassroomModal').classList.remove('hidden');
        document.getElementById('code').focus();
        // Reset form and errors
        document.getElementById('joinClassroomForm').reset();
        document.getElementById('modalError').classList.add('hidden');
        document.getElementById('codeError').classList.add('hidden');
    }
    
    function closeJoinModal() {
        document.getElementById('joinClassroomModal').classList.add('hidden');
    }
    
    function openCreateModal() {
        document.getElementById('createClassroomModal').classList.remove('hidden');
        document.getElementById('name').focus();
        // Reset form and errors
        document.getElementById('createClassroomForm').reset();
        document.getElementById('createModalError').classList.add('hidden');
        document.getElementById('nameError').classList.add('hidden');
        document.getElementById('descriptionError').classList.add('hidden');
    }
    
    function closeCreateModal() {
        document.getElementById('createClassroomModal').classList.add('hidden');
    }
    
    // Close modal when clicking Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeJoinModal();
            closeCreateModal();
        }
    });
    
    // Handle join form submission errors
    @if(session('error') && !session('form'))
        document.addEventListener('DOMContentLoaded', function() {
            openJoinModal();
            document.getElementById('modalError').classList.remove('hidden');
            document.getElementById('errorMessage').textContent = "{{ session('error') }}";
        });
    @endif
    
    @error('code')
        document.addEventListener('DOMContentLoaded', function() {
            openJoinModal();
            document.getElementById('codeError').classList.remove('hidden');
            document.getElementById('codeError').textContent = "{{ $message }}";
        });
    @enderror
    
    // Handle create form submission errors
    @if(session('error') && session('form') === 'create')
        document.addEventListener('DOMContentLoaded', function() {
            openCreateModal();
            document.getElementById('createModalError').classList.remove('hidden');
            document.getElementById('createErrorMessage').textContent = "{{ session('error') }}";
        });
    @endif
    
    @error('name')
        document.addEventListener('DOMContentLoaded', function() {
            openCreateModal();
            document.getElementById('nameError').classList.remove('hidden');
            document.getElementById('nameError').textContent = "{{ $message }}";
        });
    @enderror
    
    @error('description')
        document.addEventListener('DOMContentLoaded', function() {
            openCreateModal();
            document.getElementById('descriptionError').classList.remove('hidden');
            document.getElementById('descriptionError').textContent = "{{ $message }}";
        });
    @enderror
</script>
@endsection