@extends('layouts.teacher')

@section('title', 'Pengumpulan untuk ' . $assignment->title)

@section('content')
<div class="container py-4">
    <div class="max-w-7xl mx-auto">
        <!-- Assignment Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $assignment->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Tenggat: {{ date('d M Y, H:i', strtotime($assignment->due_date)) }}
                        @php
                            $now = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $isOverdue = $now->isAfter($dueDate);
                            
                            if($isOverdue) {
                                echo '<span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium rounded-full">Terlambat</span>';
                            } else {
                                $diff = $now->diffInDays($dueDate, false);
                                if($diff <= 3 && $diff >= 0) {
                                    echo '<span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium rounded-full">Segera berakhir</span>';
                                }
                            }
                        @endphp
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-2">
                    <a href="{{ route('teacher.assignments.show', [$classroom->id, $assignment->id]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Tugas
                    </a>
                    <a href="{{ route('teacher.classrooms.show', $classroom->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Kelas
                    </a>
                </div>
            </div>
        </div>

        <!-- Submitted Assignments -->
        @if($submissions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Tugas yang Dikumpulkan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pengumpulan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($submissions as $submission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ ($submission->user->first_name ?? '') . ' ' . ($submission->user->last_name ?? '') ?: 'Siswa' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $submission->user->email ?? 'Tidak ada email' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ date('d M Y, H:i', strtotime($submission->created_at)) }}</div>
                                <!-- For the date display in the submitted assignments section -->
                                @php
                                    $submissionDate = \Carbon\Carbon::parse($submission->created_at)->locale('id');
                                    $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                    $isLate = $submissionDate->isAfter($dueDate);
                                @endphp
                                @if($isLate)
                                    <div class="text-xs text-red-500">Terlambat ({{ $submissionDate->diffForHumans($dueDate, true) }} setelah tenggat)</div>
                                @else
                                    <div class="text-xs text-green-500">Tepat waktu ({{ $submissionDate->diffForHumans($dueDate, true) }} sebelum tenggat)</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Perlu Penilaian
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->graded)
                                    <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $submission->grade }}/{{ $assignment->max_points ?? 100 }}</span>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">                                
                                @if(!$submission->graded)
                                    <a href="{{ route('teacher.submissions.show', [$classroom->id, $assignment->id, $submission->id]) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Nilai</a>
                                @else
                                    <a href="{{ route('teacher.submissions.show', [$classroom->id, $assignment->id, $submission->id]) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300">Perbarui Nilai</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Students who haven't submitted -->
        @if($studentsWithoutSubmissions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Siswa Belum Mengumpulkan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($studentsWithoutSubmissions as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-block h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-600">
                                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ ($member->user->first_name ?? '') . ' ' . ($member->user->last_name ?? '') ?: 'Siswa' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->user->email ?? 'Tidak ada email' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Belum Mengumpulkan
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" onclick="sendReminder('{{ $member->user->email }}', '{{ $member->user->name }}')" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    Kirim Pengingat
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Send Reminder Notification (could be implemented later) -->
<script>
    function sendReminder(email, name) {
        // This could send an AJAX request to send an email reminder
        // For now, just show an alert
        alert(`Pengingat akan dikirim ke ${name} (${email})`);
    }
</script>
@endsection