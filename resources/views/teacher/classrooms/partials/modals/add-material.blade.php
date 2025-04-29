<!-- Add Material Modal -->
<div class="fixed inset-0 z-50 overflow-y-auto hidden" id="addMaterialModal" aria-labelledby="addMaterialModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addMaterialModal')"></div>
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                <button type="button" class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none" onclick="closeModal('addMaterialModal')">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('teacher.materials.store', $classroom->id) }}" method="POST" enctype="multipart/form-data" class="sm:overflow-visible max-h-[90vh] overflow-y-auto">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4 sm:mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="addMaterialModalLabel">Tambah Materi Baru</h3>
                    </div>
                    
                    <div class="sm:flex sm:flex-wrap sm:gap-x-4">
                        <div class="mb-4 sm:mb-2 sm:w-full">
                            <label for="materialTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul</label>
                            <input type="text" name="title" id="materialTitle" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        {{-- Hidden field for material type, automatically set to classroom --}}
                        @php
                            // Try to find the classroom material type dynamically first
                            $classroomOption = App\Models\Option::where('type', 'classroom_material_type')
                                ->where('value', 'classroom')
                                ->first();
                            
                            // If not found, use the known ID from the database (ID 44)
                            $optionId = $classroomOption ? $classroomOption->id : 44;
                        @endphp
                        <input type="hidden" name="type" value="{{ $optionId }}">
                        
                        <div class="mb-4 sm:mb-3 sm:w-full">
                            <label for="materialContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konten</label>
                            <textarea class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:h-24" id="materialContent" name="content" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3 sm:w-1/2">
                            <label for="materialFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lampiran (Opsional)</label>
                            <input type="file" name="file" id="materialFile"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-0 text-xs text-gray-500 dark:text-gray-400">Ukuran file maksimal: 10 MB</p>
                        </div>
                        
                        <div class="mb-3 sm:w-1/2">
                            <label for="materialImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Sampul (Opsional)</label>
                            <input type="file" name="img" id="materialImage" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-0 text-xs text-gray-500 dark:text-gray-400">Format yang didukung: JPEG, PNG, GIF</p>
                        </div>
                    
                        <div class="mb-3 sm:w-full">
                            <label for="materialLink" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tautan Eksternal (Opsional)</label>
                            <input type="url" name="link" id="materialLink" placeholder="https://example.com" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-2 sm:gap-0">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tambah Materi
                    </button>
                    <button type="button" onclick="closeModal('addMaterialModal')" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>