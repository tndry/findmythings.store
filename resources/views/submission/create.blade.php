@extends('front::layouts.app')

@section('body-class', 'page-submission-create')

@section('content')
<div class="container my-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h2>{{ isset($submission) ? __('front/submission.title_edit') : __('front/submission.title') }}</h2>
    <p>{{ isset($submission) ? __('front/submission.description_edit') : __('front/submission.description') }}</p>

    {{-- Menentukan action dan method form secara dinamis --}}
    @php
        $currentLocale = app()->getLocale();
        $formAction = isset($submission) ? account_route('submissions.update', $submission) : route($currentLocale . '.front.submission.store');
        $formMethod = 'POST'; // Method form selalu POST
    @endphp

    <form action="{{ $formAction }}" method="{{ $formMethod }}" enctype="multipart/form-data">
        @csrf
        {{-- Jika ini adalah form edit, kita perlu menambahkan method PUT --}}
        @if(isset($submission))
            @method('PUT')
        @endif

        {{-- Menampilkan error validasi jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label for="product_name" class="form-label">{{ __('front/submission.product_name') }}</label>
            {{-- Mengisi value dengan data lama jika ada --}}
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $submission->product_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="category_parent" class="form-label">{{ __('front/submission.category') }}</label>
            <select class="form-select" id="category_parent" name="category_parent" required>
                <option value="">{{ __('front/submission.select_parent_category') }}</option>
                {{-- Menggunakan variabel $parentCategories dari controller --}}
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('category_parent', $submission->category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->fallbackName() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Dropdown untuk Sub-kategori (awalnya tersembunyi) --}}
        <div class="mb-3" id="subcategory_wrapper" style="display: none;">
            <label for="category_id" class="form-label">{{ __('front/submission.subcategory') }}</label>
            <select class="form-select" id="category_id" name="category_id" required>
                {{-- Opsi akan diisi oleh JavaScript --}}
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">{{ __('front/submission.price') }}</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $submission->price ?? '') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="submitter_whatsapp" class="form-label">{{ __('front/submission.whatsapp_number') }}</label>
            <input type="text" class="form-control" id="submitter_whatsapp" name="submitter_whatsapp" value="{{ old('submitter_whatsapp', $submission->submitter_whatsapp ?? '') }}" required placeholder="{{ __('front/submission.whatsapp_placeholder') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('front/submission.product_description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $submission->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">{{ __('front/submission.product_photos') }}</label>
            <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg">
            <small class="form-text text-muted">{{ __('front/submission.photo_upload_hint') }}</small>
            
            {{-- Preview Container --}}
            <div id="image-preview-container" class="mt-3" style="display: none;">
                <label class="form-label">{{ __('front/submission.photo_preview') }}</label>
                <div id="image-preview-grid" class="row g-2">
                    <!-- Previews will be inserted here -->
                </div>
                <small class="form-text text-muted text-info">
                    <i class="fas fa-info-circle"></i> {{ __('front/submission.drag_drop_hint') }}
                </small>
            </div>
            
            @if(isset($submission) && $submission->images)
                <div class="mt-2">
                    <label class="form-label">{{ __('front/submission.current_photos') }}</label>
                    <div id="existing-images-grid" class="row g-2">
                        @foreach(json_decode($submission->images) as $index => $image)
                            <div class="col-md-4 existing-image-item" data-index="{{ $index }}">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Foto produk" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-existing-btn" data-index="{{ $index }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-75 text-white px-2 py-1 small">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">{{ __('front/submission.photo_replace_note') }}</small>
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($submission) ? __('front/submission.submit_button_edit') : __('front/submission.submit_button') }}</button>
    </form>
</div>
@endsection

@push('footer')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Image Preview Styles */
.image-preview-item {
    position: relative;
    cursor: move;
    transition: transform 0.2s ease;
}

.image-preview-item:hover {
    transform: scale(1.02);
}

.image-preview-item.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.image-preview-item .remove-btn {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.image-preview-item:hover .remove-btn {
    opacity: 1;
}

.drag-over {
    border: 2px dashed #007bff !important;
    background-color: rgba(0, 123, 255, 0.1);
}

.image-order-number {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.preview-image {
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

/* Drag placeholder */
.drag-placeholder {
    border: 2px dashed #ccc;
    background-color: #f8f9fa;
    border-radius: 8px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

/* File input enhancement */
#images {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

#images:hover {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}
</style>

<script>
console.log('Subcategories script loaded');

// Global variables for image handling
let selectedFiles = [];
let existingImages = [];

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded');
    
    // Initialize subcategories functionality
    initSubcategoriesFeature();
    
    // Initialize image preview functionality
    initImagePreviewFeature();
});

function initSubcategoriesFeature() {
    const parentCategorySelect = document.getElementById('category_parent');
    const subCategorySelect = document.getElementById('category_id');
    const subCategoryWrapper = document.getElementById('subcategory_wrapper');
    
    console.log('Elements found:', {
        parent: !!parentCategorySelect,
        sub: !!subCategorySelect, 
        wrapper: !!subCategoryWrapper
    });
    
    if (!parentCategorySelect || !subCategorySelect || !subCategoryWrapper) {
        console.error('Required elements not found!');
        return;
    }

    parentCategorySelect.addEventListener('change', function () {
        const parentId = this.value;
        console.log('Parent category changed to:', parentId);

        subCategorySelect.innerHTML = '';
        subCategoryWrapper.style.display = 'none';

        if (!parentId) {
            console.log('No parent selected');
            return;
        }
        
        console.log('Loading subcategories for parent:', parentId);
        subCategorySelect.innerHTML = '<option value="" selected disabled>{{ __('front/submission.loading') }}</option>';
        subCategoryWrapper.style.display = 'block';
        
        fetch('/api/subcategories/' + parentId)
            .then(response => {
                console.log('API Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Subcategories data:', data);
                subCategorySelect.innerHTML = '<option value="" selected disabled>{{ __('front/submission.select_subcategory') }}</option>';

                if (data.length > 0) {
                    data.forEach(function (subcategory) {
                        console.log('Adding subcategory:', subcategory);
                        const option = new Option(subcategory.name, subcategory.id);
                        subCategorySelect.appendChild(option);
                    });
                    subCategoryWrapper.style.display = 'block';
                    console.log('Subcategories loaded successfully');
                } else {
                    subCategoryWrapper.style.display = 'none';
                    console.log('No subcategories found');
                    alert('{{ __('front/submission.no_subcategory_alert') }}');
                }
            })
            .catch(error => {
                console.error('Error fetching subcategories:', error);
                subCategoryWrapper.style.display = 'none';
                alert('{{ __('front/submission.error_alert') }}');
            });
    });
    
    // Load subcategories on page load if parent category is already selected
    const oldParentCategory = "{{ old('category_parent', $submission->category->parent_id ?? '') }}";
    const oldCategoryId = "{{ old('category_id', $submission->category_id ?? '') }}";
    
    if (oldParentCategory) {
        console.log('Loading subcategories for old parent:', oldParentCategory);
        parentCategorySelect.value = oldParentCategory;
        parentCategorySelect.dispatchEvent(new Event('change'));
        
        // Set selected subcategory after a short delay
        setTimeout(function() {
            if (oldCategoryId) {
                subCategorySelect.value = oldCategoryId;
            }
        }, 500);
    }
    
    console.log('Subcategories setup complete');
}

function initImagePreviewFeature() {
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewGrid = document.getElementById('image-preview-grid');
    
    if (!fileInput || !previewContainer || !previewGrid) {
        console.error('Image preview elements not found');
        return;
    }
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        handleFileSelection(e.target.files);
    });
    
    // Initialize existing images for editing
    initExistingImages();
    
    console.log('Image preview feature initialized');
}

function handleFileSelection(files) {
    const maxFiles = 3;
    selectedFiles = Array.from(files).slice(0, maxFiles);
    
    if (files.length > maxFiles) {
        alert(`Maksimal ${maxFiles} gambar. ${files.length - maxFiles} gambar terakhir tidak akan digunakan.`);
    }
    
    displayImagePreviews();
}

function displayImagePreviews() {
    const previewContainer = document.getElementById('image-preview-container');
    const previewGrid = document.getElementById('image-preview-grid');
    
    if (selectedFiles.length === 0) {
        previewContainer.style.display = 'none';
        return;
    }
    
    previewContainer.style.display = 'block';
    previewGrid.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageItem = createImagePreviewItem(e.target.result, index, file.name, false);
            previewGrid.appendChild(imageItem);
        };
        reader.readAsDataURL(file);
    });
}

function createImagePreviewItem(src, index, fileName, isExisting) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col-md-4 image-preview-item';
    colDiv.draggable = true;
    colDiv.dataset.index = index;
    colDiv.dataset.existing = isExisting;
    
    colDiv.innerHTML = `
        <div class="position-relative">
            <img src="${src}" alt="${fileName}" class="img-thumbnail w-100 preview-image">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-btn">
                <i class="fas fa-times"></i>
            </button>
            <div class="position-absolute bottom-0 start-0 m-2">
                <span class="image-order-number">${index + 1}</span>
            </div>
            <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white px-2 py-1 small">
                ${fileName.length > 15 ? fileName.substring(0, 15) + '...' : fileName}
            </div>
        </div>
    `;
    
    // Add event listeners
    setupDragAndDrop(colDiv);
    setupRemoveButton(colDiv);
    
    return colDiv;
}

function setupDragAndDrop(element) {
    element.addEventListener('dragstart', function(e) {
        e.dataTransfer.setData('text/plain', this.dataset.index);
        this.classList.add('dragging');
    });
    
    element.addEventListener('dragend', function(e) {
        this.classList.remove('dragging');
    });
    
    element.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    
    element.addEventListener('dragleave', function(e) {
        this.classList.remove('drag-over');
    });
    
    element.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        const draggedIndex = parseInt(e.dataTransfer.getData('text/plain'));
        const targetIndex = parseInt(this.dataset.index);
        
        if (draggedIndex !== targetIndex) {
            reorderImages(draggedIndex, targetIndex);
        }
    });
}

function setupRemoveButton(element) {
    const removeBtn = element.querySelector('.remove-btn');
    removeBtn.addEventListener('click', function() {
        const index = parseInt(element.dataset.index);
        removeImage(index);
    });
}

function reorderImages(fromIndex, toIndex) {
    // Move the file in the selectedFiles array
    const movedFile = selectedFiles.splice(fromIndex, 1)[0];
    selectedFiles.splice(toIndex, 0, movedFile);
    
    // Refresh the display
    displayImagePreviews();
    updateFileInput();
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    displayImagePreviews();
    updateFileInput();
}

function updateFileInput() {
    const fileInput = document.getElementById('images');
    const dt = new DataTransfer();
    
    selectedFiles.forEach(file => {
        dt.items.add(file);
    });
    
    fileInput.files = dt.files;
}

function initExistingImages() {
    // Handle remove existing images
    document.querySelectorAll('.remove-existing-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            this.closest('.existing-image-item').remove();
            // You might want to add logic to track removed images for backend processing
        });
    });
}
</script>
@endpush