{{-- Google OAuth IPB - Independent dari sistem InnoShop --}}
<div class="google-ipb-auth-section mt-4">
  <div class="d-flex align-items-center mb-3">
    <hr class="flex-grow-1">
    <span class="px-3 text-muted small">atau</span>
    <hr class="flex-grow-1">
  </div>
  <div class="text-center">
    <a href="{{ route('google.ipb.redirect') }}"
       class="btn btn-outline-secondary w-100 py-3 d-flex align-items-center justify-content-center position-relative google-ipb-btn">
      <!-- Google Icon SVG -->
      <svg class="me-3" width="20" height="20" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      <span class="fw-semibold">Masuk dengan Akun IPB</span>
      <!-- Badge for special features -->
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary text-white small px-2 py-1" style="font-size: 0.7rem;">
        TERCEPAT
      </span>
    </a>
    <!-- Benefits for Google OAuth -->
    <div class="mt-3 text-center">
      <div class="row text-muted small">
        <div class="col-4">
          <i class="bi bi-check-circle-fill text-success me-1"></i>
          Auto verifikasi
        </div>
        <div class="col-4">
          <i class="bi bi-lightning-fill text-warning me-1"></i>
          Login cepat
        </div>
        <div class="col-4">
          <i class="bi bi-shield-check text-primary me-1"></i>
          Aman
        </div>
      </div>
    </div>
    <div class="mt-2">
      <small class="text-muted">Menggunakan sistem akun IPB yang sudah ada</small>
    </div>
  </div>
</div>

<style>
.google-ipb-btn {
  border: 2px solid #dadce0;
  color: #3c4043;
  transition: all 0.3s ease;
}
.google-ipb-btn:hover {
  border-color: #4285f4;
  box-shadow: 0 2px 8px rgba(66, 133, 244, 0.15);
  color: #3c4043;
  text-decoration: none;
}
.google-ipb-auth-section hr {
  border: none;
  height: 1px;
  background: linear-gradient(90deg, transparent, #dadce0, transparent);
}
</style>
