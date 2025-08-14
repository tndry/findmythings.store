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
            <input class="form-control" type="file" id="images" name="images[]" multiple>
            @if(isset($submission) && $submission->images)
                <div class="mt-2">
                    <small>{{ __('front/submission.current_photos') }}</small>
                    <div class="d-flex">
                        @foreach(json_decode($submission->images) as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Foto produk" class="img-thumbnail me-2" style="width: 100px; height: 100px; object-fit: cover;">
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
<script>
console.log('Subcategories script loaded');

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded');
    
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
    
    console.log('Script setup complete');
});
</script>
@endpush