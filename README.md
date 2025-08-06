<p align="center">
    <img src="public/images/findmythings_logo.png" alt="findmythings Logo" width="300">
</p>

# findmythings

**findmythings** adalah platform marketplace berbasis komunitas yang dirancang khusus untuk mahasiswa IPB University. Aplikasi ini bertujuan untuk mempermudah mahasiswa dalam menjual dan membeli barang-barang bekas (preloved) di lingkungan kampus.



---

## Latar Belakang

Banyak mahasiswa, terutama yang akan lulus atau pindah kos, memiliki barang-barang layak pakai yang sudah tidak terpakai. Di sisi lain, mahasiswa baru seringkali membutuhkan barang-barang tersebut dengan harga yang terjangkau. `findmythings` hadir sebagai jembatan untuk menghubungkan penjual dan pembeli di dalam komunitas kampus yang terpercaya.

## Fitur Utama (MVP)

* **Sistem Titip Jual:** Pengguna dapat dengan mudah menitipkan barang bekas mereka melalui formulir online.
* **Kurasi Admin:** Setiap barang yang dititipkan akan ditinjau oleh admin untuk memastikan kualitas dan kelayakan.
* **Kontak Langsung via WhatsApp:** Pembeli dapat langsung menghubungi penjual melalui tombol WhatsApp yang tersedia di setiap halaman produk.
* **Dasbor Pengguna:** Pengguna dapat melacak status barang titipannya (Menunggu, Disetujui, Ditolak, Revisi, Terjual).
* **Alur Revisi:** Admin dapat meminta revisi, dan pengguna akan mendapatkan notifikasi email beserta catatan untuk memperbaiki detail titipannya.
* **Multibahasa:** Mendukung Bahasa Indonesia dan Inggris.

## Teknologi yang Digunakan

* **Framework:** Laravel 11 (berbasis [Innoshop Open Source](https://github.com/innocommerce/innoshop))
* **Bahasa:** PHP, JavaScript
* **Database:** MySQL
* **Frontend:** Blade Templates, Bootstrap, Swiper.js
* **Server:** Bitnami (Production), Laragon (Development)

## Quick Start

### Development Setup
```bash
# Clone repository
git clone https://github.com/yourusername/findmythings.git
cd findmythings

# Copy environment file
cp .env.example .env

# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Configure your .env file with database and email settings
# Then run migrations
php artisan migrate
```

### Production Deployment
Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk panduan lengkap deployment ke server production.

## Deployment Status
- ✅ Local Development Ready
- ✅ Image Processing Fixed
- ✅ Production Security Configured
- ✅ Ready for GitHub & Azure Bitnami Deployment




## Kontributor

Terima kasih kepada semua yang telah berkontribusi dalam proyek ini.

* Tandry Simamora - *Project Lead & Developer*
    

---

<p align="center">
    Proyek ini dibuat dengan ❤️ untuk komunitas IPB University.
</p>