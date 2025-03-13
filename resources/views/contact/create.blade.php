<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Contact') }}
        </h2>
    </x-slot>

    <div class="pt-16 bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-12">
            <div class="bg-gray-900 overflow-hidden shadow-2xl rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-200">Create a new contact</h3>

                    <!-- Menampilkan pesan jika user belum login -->
                    @if (!Auth::check())
                        <div class="alert alert-danger mb-4 p-4 bg-red-500 text-white rounded">
                            Harap Login Dahulu
                        </div>
                    @else
                        <!-- Form untuk menambahkan kontak baru -->
                        <form id="contactForm">
                            @csrf
                            <div class="mb-6">
                                <label for="message" class="block text-gray-200 font-medium">Message</label>
                                <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 mt-2 bg-gray-800 border border-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-400 transition duration-300">Create Contact</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.getElementById("contactForm");

                if (!form) {
                    console.error("Form not found!");
                    return;
                }

                form.addEventListener("submit", function (e) {
                    e.preventDefault(); // Mencegah form terkirim langsung

                    Swal.fire({
                        title: "Konfirmasi",
                        text: "Apakah kamu yakin ingin mengirim pesan ini?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Kirim!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let formData = new FormData(form);

                            fetch("{{ route('contacts.store') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.errors) {
                                    Swal.fire({
                                        title: "Gagal!",
                                        text: Object.values(data.errors).join("\n"),
                                        icon: "error",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Berhasil!",
                                        text: "Kontak berhasil dibuat!",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        form.reset(); // Reset form setelah sukses
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Terjadi kesalahan pada server.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>