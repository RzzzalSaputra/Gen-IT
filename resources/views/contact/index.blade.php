<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hubungi Kami') }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $contacts->total() ?? 0 }} pesan terkirim
                </div>
                <a href="{{ route('contacts.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Pesan Baru
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
            
            <!-- Create New Message Card -->
            <div class="bg-gradient-to-r from-blue-900/50 to-purple-900/50 backdrop-blur-sm rounded-2xl border border-blue-700/50 overflow-hidden shadow-xl mb-8">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-100 mb-2">
                                Punya pertanyaan atau masukan?
                            </h3>
                            <p class="text-gray-300 mb-4">
                                Kirim pesan kepada kami dan kami akan membalas dalam waktu 24-48 jam.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-blue-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('contacts.create') }}" 
                        class="mt-2 inline-flex w-full sm:w-auto justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-xl transition-colors duration-200 shadow-lg hover:shadow-blue-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Pesan Baru
                    </a>
                </div>
            </div>
            
            <!-- Message History Section -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50 overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-200">Riwayat Pesan Anda</h3>
                </div>
                <div class="p-6">
                    @if(count($contacts) > 0)
                        <div class="space-y-6">
                            @foreach($contacts as $contact)
                                <div class="bg-gray-800/30 rounded-xl overflow-hidden shadow-lg border border-gray-700/30 transition-all duration-300 hover:border-blue-500/50 hover:shadow-blue-500/5">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-100 mb-3">
                                                    Pesan #{{ $contact->id }}
                                                </h3>
                                                <div class="text-sm text-gray-400 mb-4">
                                                    Dikirim {{ $contact->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div>
                                                @php
                                                    $statusClass = '';
                                                    $statusText = 'Tidak Diketahui';
                                                    
                                                    if ($contact->status) {
                                                        $option = \App\Models\Option::find($contact->status);
                                                        if ($option) {
                                                            if ($option->value == 'pending') {
                                                                $statusText = 'Menunggu';
                                                                $statusClass = 'bg-yellow-900/30 text-yellow-300 border-yellow-500/30';
                                                            } elseif ($option->value == 'responded') {
                                                                $statusText = 'Ditanggapi';
                                                                $statusClass = 'bg-green-900/30 text-green-300 border-green-500/30';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium {{ $statusClass }} backdrop-blur-sm border">
                                                    <span class="w-2 h-2 rounded-full mr-2 {{ $statusText == 'Menunggu' ? 'bg-yellow-400' : 'bg-green-400' }}"></span>
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-900/50 rounded-xl p-4 mb-4 text-gray-300">
                                            {{ $contact->message }}
                                        </div>
                                        
                                        @if($contact->respond_message)
                                            <div class="mt-6">
                                                <h4 class="text-lg font-medium text-gray-200 mb-2 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                    </svg>
                                                    Tanggapan Admin
                                                </h4>
                                                <div class="bg-blue-900/20 rounded-xl p-4 border border-blue-500/20 text-gray-300">
                                                    {{ $contact->respond_message }}
                                                </div>
                                                <div class="text-sm text-gray-500 mt-2">
                                                    Ditanggapi {{ $contact->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="mt-8">
                                {{ $contacts->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold mb-2 text-gray-200">Belum Ada Pesan</h3>
                            <p class="text-gray-400 mb-6">Anda belum mengirim pesan ke admin.</p>
                            <a href="{{ route('contacts.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-base font-medium rounded-xl hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Kirim Pesan Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>