<!DOCTYPE html>
<html>
<head>
    <title>Update Status Titipan</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2>Halo, {{ $submission->user->name }}!</h2>

    <p>Ada pembaruan status untuk barang titipan Anda:</p>
    
    <p><strong>Nama Produk:</strong> {{ $submission->product_name }}</p>
    <p><strong>Status Baru:</strong> {{ front_trans('submission.status_' . $submission->status) }}</p>

    @if($submission->status == 'revision_needed' && $submission->admin_notes)
        <div style="background-color: #fffbe6; border: 1px solid #ffe58f; padding: 15px; border-radius: 5px;">
            <p><strong>Catatan dari Admin:</strong></p>
            <p style="white-space: pre-wrap;">{{ $submission->admin_notes }}</p>
            <p>Silakan perbarui titipan Anda sesuai catatan di atas.</p>
        </div>
    @elseif($submission->status == 'rejected')
        <p>Mohon maaf, titipan Anda belum dapat kami setujui saat ini.</p>
    @endif

    <p style="margin-top: 25px;">
        <a href="{{ account_route('submissions.index') }}" style="background-color: #0d6efd; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Lihat Titipan Saya
        </a>
    </p>

    <p>Terima kasih,<br>Tim findmythings</p>
</body>
</html>