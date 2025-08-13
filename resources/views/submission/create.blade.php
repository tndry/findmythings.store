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
            <label for="category_id" class="form-label">{{ __('front/submission.category') }}</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="" disabled {{ old('category_id', $submission->category_id ?? '') == '' ? 'selected' : '' }}>{{ __('front/submission.select_category') }}</option>
                @forelse($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $submission->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{-- PERBAIKAN: Gunakan fallbackName() untuk nama yang bisa diterjemahkan --}}
                        {{ $category->fallbackName() }}
                    </option>
                @empty
                    <option value="" disabled>{{ __('front/submission.no_categories') }}</option>
                @endforelse
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