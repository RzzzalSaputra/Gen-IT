@extends('layouts.teacher')

@section('title', $material->title)

@section('content')
<div class="container py-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <!-- Header with actions -->
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <div class="flex items-center">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $material->title }}</h1>
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Added on {{ date('M d, Y', strtotime($material->create_at)) }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <button data-modal-target="editMaterialModal{{ $material->id }}" data-modal-toggle="editMaterialModal{{ $material->id }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </button>
                    <button data-modal-target="deleteMaterialModal{{ $material->id }}" data-modal-toggle="deleteMaterialModal{{ $material->id }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                    <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Classroom
                    </a>
                </div>
            </div>

            <!-- Material Content -->
            <div class="p-6">
                <!-- Material Content with improved text color -->
                <div class="prose prose-lg max-w-none dark:prose-invert text-gray-800 dark:text-white mb-6">
                    {!! $material->content !!}
                </div>

                <!-- Material Link -->
                @if($material->link)
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">External Link:</h3>
                    <a href="{{ $material->link }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                        <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        {{ $material->link }}
                    </a>
                </div>
                @endif

                <!-- Material Image - Removed "Cover Image:" text -->
                @if($material->img)
                <div class="mt-6">
                    <img src="{{ asset('storage/' . $material->img) }}" alt="{{ $material->title }}" class="rounded-lg max-h-96 mx-auto">
                </div>
                @endif

                <!-- Material File -->
                @if($material->file)
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attached File:</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 dark:text-gray-300">{{ basename($material->file) }}</span>
                        <a href="{{ route('materials.download', $material->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add these modals and script for the edit/delete functionality -->
@include('teacher.classrooms.partials.modals.edit-material', ['material' => $material])
@include('teacher.classrooms.partials.modals.delete-material', ['material' => $material])

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modalButtons = document.querySelectorAll('[data-modal-target]');
    modalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');
            openModal(modalId);
        });
    });
});

// Functions to open and close modals
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script>
@endsection