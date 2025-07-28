@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}


@section('content')
<div class="container my-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h2>{{ isset($submission) ? 'Perbaiki Detail Titipan' : 'Mulai Jual Barangmu' }}</h2>
    <p>{{ isset($submission) ? 'Silakan perbarui informasi barang Anda sesuai catatan dari admin.' : 'Isi formulir di bawah ini untuk menitipkan barang bekasmu.' }}</p>

    {{-- Menentukan action dan method form secara dinamis --}}
    @php
        $formAction = isset($submission) ? account_route('submissions.update', $submission) : route('submission.store');
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
            <label for="product_name" class="form-label">Nama Produk</label>
            {{-- Mengisi value dengan data lama jika ada --}}
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $submission->product_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="" disabled {{ old('category_id', $submission->category_id ?? '') == '' ? 'selected' : '' }}>Pilih Kategori</option>
                @forelse($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $submission->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{-- PERBAIKAN: Gunakan fallbackName() untuk nama yang bisa diterjemahkan --}}
                        {{ $category->fallbackName() }}
                    </option>
                @empty
                    <option value="" disabled>Tidak ada kategori tersedia.</option>
                @endforelse
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $submission->price ?? '') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="submitter_whatsapp" class="form-label">Nomor WhatsApp</label>
            <input type="text" class="form-control" id="submitter_whatsapp" name="submitter_whatsapp" value="{{ old('submitter_whatsapp', $submission->submitter_whatsapp ?? '') }}" required placeholder="Contoh: 08123456789">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $submission->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Foto Produk (Maksimal 3 foto)</label>
            <input class="form-control" type="file" id="images" name="images[]" multiple>
            @if(isset($submission) && $submission->images)
                <div class="mt-2">
                    <small>Foto saat ini:</small>
                    <div class="d-flex">
                        @foreach(json_decode($submission->images) as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Foto produk" class="img-thumbnail me-2" style="width: 100px; height: 100px; object-fit: cover;">
                        @endforeach
                    </div>
                    <small class="text-muted">Mengupload foto baru akan menggantikan semua foto lama.</small>
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($submission) ? 'Simpan Perubahan' : 'Kirim Titipan' }}</button>
    </form>
</div>
@endsection