@extends('panel::layouts.app')
@section('title', 'Review Titipan: ' . $submission->product_name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Detail Titipan #{{ $submission->id }}</h4>
        <a href="{{ panel_route('submissions.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h3>{{ $submission->product_name }}</h3>
                <p><strong>Penitip:</strong> {{ $submission->user->name ?? 'Pengguna tidak ditemukan' }} ({{ $submission->user->email }})</p>
                <p><strong>WhatsApp:</strong> {{ $submission->submitter_whatsapp }}</p>
                <p><strong>Harga:</strong> {{ currency_format($submission->price) }}</p>
                <hr>
                <h5>Deskripsi:</h5>
                <p>{!! nl2br(e($submission->description)) !!}</p>
            </div>
            <div class="col-md-4">
                <h5>Foto Produk:</h5>
                @if($submission->images)
                    @foreach(json_decode($submission->images) as $image)
                        <a href="{{ Storage::url($image) }}" target="_blank">
                            <img src="{{ Storage::url($image) }}" class="img-fluid mb-2 border rounded" alt="Foto Produk">
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <hr>

        
        @if($submission->status == 'pending')
            <div class="mt-4">
                <h5>Aksi Kurasi</h5>
                <div class="d-flex align-items-start">
                    {{-- Form untuk Setujui --}}
                    <form action="{{ panel_route('submissions.approve', $submission) }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-success">Setujui & Terbitkan</button>
                    </form>

                    {{-- Form untuk Tolak --}}
                    <form action="{{ panel_route('submissions.reject', $submission) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak submission ini?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </form>
                </div>

                {{-- Form untuk Minta Revisi --}}
                <div class="mt-4">
                    <form action="{{ panel_route('submissions.request_revision', $submission) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="admin_notes"><strong>Catatan untuk Revisi:</strong></label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" required placeholder="Contoh: Foto produk buram, tolong ganti dengan yang lebih jelas."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning mt-2">Kirim Permintaan Revisi</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4">
                Aksi tidak tersedia karena titipan ini sudah diproses (Status: <strong>{{ panel_trans('submission.status_' . $submission->status) }}</strong>).
            </div>
        @endif
        

    </div>
</div>
@endsection