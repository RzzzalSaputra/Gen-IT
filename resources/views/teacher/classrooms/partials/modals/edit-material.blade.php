<!-- Edit Material Modal -->
<div class="fixed inset-0 overflow-y-auto hidden" id="editMaterialModal{{ $material->id }}" aria-labelledby="editMaterialModalLabel{{ $material->id }}" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="editMaterialForm{{ $material->id }}" action="{{ route('teacher.materials.update', [$classroom->id, $material->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="editMaterialModalLabel{{ $material->id }}">Edit Material</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" onclick="document.getElementById('editMaterialModal{{ $material->id }}').classList.add('hidden')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Hidden fields for removal tracking -->
                    <input type="hidden" name="remove_file" id="remove_file{{ $material->id }}" value="0">
                    <input type="hidden" name="remove_img" id="remove_img{{ $material->id }}" value="0">
                    <input type="hidden" name="remove_link" id="remove_link{{ $material->id }}" value="0">
                    
                    <!-- Preserve the type value as hidden input -->
                    <input type="hidden" name="type" value="{{ $material->type }}">
                    
                    <div class="mb-4">
                        <label for="editMaterialTitle{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" name="title" id="editMaterialTitle{{ $material->id }}" value="{{ $material->title }}" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editMaterialContent{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                        <textarea class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="editMaterialContent{{ $material->id }}" name="content" rows="5" required>{{ $material->content }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="editMaterialFile{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachment</label>
                            @if($material->file)
                                <div class="mb-2 flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-md p-2">
                                    <a href="{{ asset('storage/' . $material->file) }}" target="_blank" class="flex items-center group">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900 rounded-md mr-3">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate">
                                                {{ basename($material->file) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Click to view or download
                                            </p>
                                        </div>
                                    </a>
                                    <button type="button" 
                                            onclick="document.getElementById('remove_file{{ $material->id }}').value='1'; this.parentElement.classList.add('hidden');"
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="file" id="editMaterialFile{{ $material->id }}"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new file to replace the current one</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="editMaterialImage{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Image</label>
                            @if($material->img)
                                <div class="mb-2 flex items-center justify-between">
                                    <img src="{{ asset('storage/' . $material->img) }}" alt="Current Image" class="h-20 w-auto object-cover rounded">
                                    <button type="button" 
                                            onclick="document.getElementById('remove_img{{ $material->id }}').value='1'; this.parentElement.classList.add('hidden');"
                                            class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="img" id="editMaterialImage{{ $material->id }}" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the current one</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editMaterialLink{{ $material->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">External Link</label>
                        <div class="flex items-center">
                            <input type="url" name="link" id="editMaterialLink{{ $material->id }}" value="{{ $material->link }}" placeholder="https://example.com"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @if($material->link)
                                <button type="button" 
                                        onclick="document.getElementById('remove_link{{ $material->id }}').value='1'; document.getElementById('editMaterialLink{{ $material->id }}').value='';"
                                        class="ml-2 text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Fixed button section -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="document.getElementById('editMaterialModal{{ $material->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>