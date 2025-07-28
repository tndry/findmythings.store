<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email - findmythings</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .button { display: inline-block; padding: 12px 25px; margin: 20px 0; background-color: #E91E63; color: #fff; text-decoration: none; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 0.8em; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat Datang di findmythings!</h2>
        <p>Satu langkah lagi! Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mengaktifkan akun Anda.</p>
        <a href="{{ $url }}" class="button">Verifikasi Email Sekarang</a>
        <p>Jika tombol tidak berfungsi, salin dan tempel link berikut di browser Anda:</p>
        <p><small>{{ $url }}</small></p>
        <p class="footer">Terima kasih,<br>Tim findmythings</p>
    </div>
</body>
</html>