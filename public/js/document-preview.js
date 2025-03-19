class DocumentPreview {
    constructor() {
        this.modal = document.getElementById('filePreviewModal');
        this.previewFrame = document.getElementById('filePreviewFrame');
        this.previewContainer = document.getElementById('previewContainer');
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.querySelectorAll('.file-preview-trigger').forEach(button => {
            button.addEventListener('click', (e) => this.showPreview(e));
        });

        document.querySelectorAll('.close-file-preview').forEach(button => {
            button.addEventListener('click', () => this.closePreview());
        });

        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closePreview();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.closePreview();
            }
        });
    }

    showPreview(e) {
        const button = e.currentTarget;
        const fileUrl = button.dataset.fileUrl;
        const fileType = button.dataset.fileType;

        this.showLoading();
        this.modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        switch(fileType) {
            case 'pdf':
                this.showPdfPreview(fileUrl);
                break;
            case 'doc':
            case 'docx':
            case 'ppt':
            case 'pptx':
                this.showDocumentPreview(fileUrl, fileType);
                break;
            default:
                this.hideLoading();
                this.showError('Unsupported file type');
        }
    }

    showPdfPreview(url) {
        const viewerUrl = `/pdfjs/web/viewer.html?file=${encodeURIComponent(url)}`;
        this.previewFrame.src = viewerUrl;
        this.previewFrame.onload = () => this.hideLoading();
    }

    showDocumentPreview(url, type) {
        // Show document thumbnail and download button
        const extension = type.toUpperCase();
        this.previewContainer.innerHTML = `
            <div class="text-center p-8">
                <div class="mx-auto w-24 h-24 mb-4 bg-gray-800 rounded-lg flex items-center justify-center">
                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200 mb-2">${extension} Document</h3>
                <p class="text-gray-400 mb-4">This file type can be downloaded and viewed in its native application.</p>
                <a href="${url}" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download ${extension}
                </a>
            </div>
        `;
        this.hideLoading();
    }

    showLoading() {
        this.previewContainer.innerHTML = `
            <div class="flex items-center justify-center h-[60vh]">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
            </div>
        `;
    }

    hideLoading() {
        const loader = this.previewContainer.querySelector('.animate-spin')?.parentElement;
        if (loader) loader.remove();
    }

    showError(message) {
        this.previewContainer.innerHTML = `
            <div class="text-center p-8">
                <div class="text-red-500 mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200">${message}</h3>
            </div>
        `;
    }

    closePreview() {
        this.modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        this.previewFrame.src = '';
        this.previewContainer.innerHTML = '';
    }
}