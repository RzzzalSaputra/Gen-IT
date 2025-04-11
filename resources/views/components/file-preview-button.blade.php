@php
    $record = $getRecord();
    $filePath = $record?->file ? asset('storage/' . ltrim($record->file, '/')) : null;
@endphp

@if ($filePath)
    <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded">
        🔍 Preview File
    </a>
@endif
