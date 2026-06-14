# MiniFut — Dokumentasi Sistem dan Panduan Instalasi

**MiniFut** adalah sistem informasi manajemen penyewaan lapangan mini soccer (futsal) berbasis web. Sistem ini dirancang menggunakan PHP Native dan MySQL, dengan fokus pada pengalaman pengguna (UX) yang modern, antarmuka (UI) bertema gelap (dark-mode), serta keamanan data yang tangguh.

---

## 1. Struktur Direktori dan Kegunaan File

Berikut adalah peta struktur dari direktori utama sistem beserta penjelasan fungsinya masing-masing:

```text
ProyekAkhirPPW/
├── index.php              : Halaman utama (Landing Page) yang dilindungi oleh autentikasi pelanggan.
├── index.html             : Halaman utama versi statis (HTML murni) untuk keperluan demonstrasi antarmuka.
├── booking.php            : Modul pemesanan lapangan dinamis beserta API back-end (terintegrasi dengan 5 tabel).
├── booking.html           : Halaman pemesanan versi statis.
├── profile.php            : Halaman manajemen profil pelanggan (ubah informasi, kata sandi, foto, dan riwayat).
├── config.php             : Berkas konfigurasi basis data, manajemen sesi, fungsi keamanan, dan utilitas.
├── database/
│   ├── minifut_db.sql     : Berkas ekspor basis data lengkap (mencakup DDL, relasi, dan data sampel).
│   └── query_kompleks.sql : Berkas kumpulan objek basis data (View, Function, Procedure, Trigger) untuk kebutuhan evaluasi.
├── .htaccess              : Aturan keamanan server Apache untuk memblokir akses ke berkas sensitif.
│
├── assets/                : Direktori penyimpanan aset statis seperti gambar fasilitas.
│
├── auth/
│   ├── login.php          : Modul masuk (login) untuk pelanggan.
│   ├── register.php       : Modul pendaftaran akun pelanggan baru dengan validasi berlapis.
│   └── logout.php         : Modul keluar sesi (logout) pelanggan.
│
└── admin/                 : Direktori panel kendali administrator.
    ├── login.php          : Modul masuk khusus administrator.
    ├── logout.php         : Modul keluar sesi administrator.
    ├── dashboard.php      : Dasbor utama menampilkan ringkasan statistik.
    ├── lapangan.php       : Modul CRUD untuk data lapangan beserta fitur unggah foto.
    ├── pelanggan.php      : Modul CRUD untuk mengelola data pelanggan.
    ├── booking.php        : Modul manajemen data pemesanan, pencarian, dan paginasi.
    ├── jadwal.php         : Modul manajemen ketersediaan jadwal operasional.
    ├── pembayaran.php     : Modul konfirmasi dan verifikasi bukti pembayaran.
    ├── _header.php        : Komponen tata letak (layout) bagian atas dan navigasi sisi panel admin.
    ├── _footer.php        : Komponen tata letak (layout) bagian bawah panel admin.
    └── uploads/           : Direktori tempat menyimpan berkas foto yang diunggah pengguna.
        └── .htaccess      : Keamanan tambahan untuk mencegah eksekusi skrip PHP di dalam direktori unggahan.
```

---

## 2. Panduan Instalasi dan Setup Basis Data

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi pada lingkungan pengembangan lokal (localhost).

### Tahap 1: Penempatan Direktori
Pindahkan seluruh direktori `ProyekAkhirPPW/` ke dalam direktori server lokal Anda, seperti `htdocs/` untuk XAMPP atau `www/` untuk WAMP.
Jalur direktori yang diharapkan: `C:\xampp\htdocs\ProyekAkhirPPW\`

### Tahap 2: Import Basis Data Utama
Berkas `minifut_db.sql` sudah mencakup seluruh struktur tabel (DDL), relasi antar tabel (Foreign Keys), dan data sampel (DML) yang dibutuhkan agar aplikasi langsung dapat berfungsi.

1. Buka antarmuka pengelolaan basis data (misalnya: phpMyAdmin).
2. Buat basis data baru dengan nama: `minifut_db`.
3. Buka basis data tersebut, lalu navigasikan ke tab **Import**.
4. Pilih berkas `minifut_db.sql` yang berada di akar (root) direktori proyek, lalu eksekusi (klik **Go**).

### Tahap 3: Import Objek Basis Data Lanjutan (Khusus Evaluasi)
Berkas `database/query_kompleks.sql` memuat implementasi tingkat lanjut seperti `View`, `Function`, `Procedure`, dan `Trigger`. Langkah ini bersifat kondisional dan umumnya digunakan untuk membuktikan penerapan kaidah basis data yang kompleks.

1. Pastikan Anda masih berada di dalam basis data `minifut_db`.
2. Navigasikan ke tab **SQL**.
3. Salin seluruh isi dari berkas `database/query_kompleks.sql`, tempel pada area kueri, lalu eksekusi (klik **Go**).

### Tahap 4: Konfigurasi Koneksi (Opsional)
Apabila kredensial server MySQL Anda berbeda dari bawaan default, sesuaikan konfigurasi pada berkas `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'minifut_db');
define('DB_USER', 'root'); // Ubah jika menggunakan nama pengguna berbeda
define('DB_PASS', '');     // Masukkan kata sandi jika ada
```

---

## 3. Rincian URL dan Kredensial Akses

### Daftar Tautan Akses Aplikasi
- Landing Page: `http://localhost/ProyekAkhirPPW/index.php`
- Pemesanan (Booking): `http://localhost/ProyekAkhirPPW/booking.php`
- Profil Pelanggan: `http://localhost/ProyekAkhirPPW/profile.php`
- Login Pelanggan: `http://localhost/ProyekAkhirPPW/auth/login.php`
- Registrasi Pelanggan: `http://localhost/ProyekAkhirPPW/auth/register.php`
- Login Administrator: `http://localhost/ProyekAkhirPPW/admin/login.php`
- Dasbor Administrator: `http://localhost/ProyekAkhirPPW/admin/dashboard.php`

Catatan: Berkas berekstensi `.html` dapat diakses untuk melihat tata letak antarmuka secara statis tanpa melibatkan proses dari server maupun basis data.

### Kredensial Default Administrator
- **Username:** `Khayr`
- **Password:** `minifut107`

---

## 4. Fitur dan Fungsionalitas Utama

### 4.1. Autentikasi dan Keamanan Akses
- Terdapat pemisahan sesi antara akses Administrator dan Pelanggan.
- Sandi pengguna dilindungi menggunakan metode hashing modern (`password_hash()`).
- Setiap halaman fungsional dilindungi oleh lapisan validasi hak akses untuk mencegah penerobosan URL.

### 4.2. Halaman Utama (Landing Page)
- Menyajikan antarmuka visual dinamis memanfaatkan WebGL dan animasi Three.js.
- Menampilkan fasilitas lapangan, ulasan, serta pilihan harga operasional yang dikemas secara interaktif.
- Optimalisasi navigasi yang responsif untuk berbagai ukuran perangkat (desktop, tablet, mobile).

### 4.3. Modul Pemesanan (Booking 4 Langkah)
Modul ini merangkum proses pemesanan ke dalam 4 tahapan yang mulus:
1. Pemilihan Lapangan berdasarkan ketersediaan.
2. Pemilihan Tanggal operasional.
3. Pemilihan Slot Jam (diambil secara langsung dari basis data; slot yang terisi akan secara otomatis terkunci).
4. Pengisian Detail Pelanggan beserta pilihan jenis pembayaran (Lunas atau DP).

### 4.4. Manajemen Profil Pengguna
- Pelanggan yang telah masuk dapat memperbarui data personal, termasuk mengunggah pasfoto profil.
- Terdapat integrasi informasi profil sosial media.
- Pelanggan dapat mengubah kata sandi dengan verifikasi keamanan tambahan.
- Menyediakan riwayat lengkap transaksi pemesanan beserta status pembayarannya.

### 4.5. Panel Administrator
- Dasbor dengan metrik analitik pemesanan terkini.
- Pengelolaan master data lapangan, pelanggan, jadwal, dan transaksi.
- Fasilitas konfirmasi pembayaran secara manual untuk memverifikasi pesanan pelanggan.
- Seluruh tabel dilengkapi dengan fitur pencarian spesifik dan paginasi (10 entri per halaman) guna menunjang kinerja pemuatan data.

---

## 5. Implementasi Validasi dan Keamanan Basis Data

### Perlindungan Terhadap Serangan
- **SQL Injection:** Seluruh operasi yang berinteraksi dengan basis data menggunakan metode `Prepared Statements` (PDO).
- **Cross-Site Scripting (XSS):** Semua data yang dicetak ke layar HTML melalui proses penyaringan karakter khusus (`htmlspecialchars()`).
- **Session Hijacking:** Mengimplementasikan perlakuan khusus pada sesi dengan flag `HttpOnly`, `SameSite=Strict`, serta pembaruan ID sesi `session_regenerate_id()`.

### Integritas Data Operasional
- Transaksi pemesanan melibatkan penyisipan (insert) pada beberapa tabel secara sekuensial. Proses ini dibungkus di dalam blok `PDO::beginTransaction()`. Kegagalan pada salah satu tahap akan otomatis membatalkan seluruh operasi (`rollBack()`) guna mencegah kerusakan relasi data.
- Kalkulasi harga dan waktu sepenuhnya dieksekusi pada level server (Back-End) untuk menghindari manipulasi pada level klien (Inspect Element).

### Pengelolaan Berkas Unggahan
- Mekanisme penyaringan tipe ekstensi (hanya memperbolehkan berkas gambar).
- Pembatasan batas maksimal ukuran berkas (5MB).
- Pengubahan nama berkas dengan ID unik untuk mencegah timpaan berkas ber-nama sama secara tidak sengaja.
- Penambahan kontrol ekstensi `.htaccess` dalam direktori unggahan untuk mencegah penyusupan dan eksekusi skrip berbahaya.

---

## 6. Teknologi yang Digunakan

- **Bahasa Sisi Server:** PHP 8+ (Metodologi Native)
- **Basis Data:** MySQL/MariaDB terintegrasi via PHP Data Objects (PDO)
- **Struktur Antarmuka:** HTML5 dan CSS3 (Vanilla)
- **Interaktivitas Sisi Klien:** JavaScript (Vanilla)
- **Pustaka Visual Khusus:** Three.js (Grafik 3D), GSAP (Animasi interaktif), Bootstrap Icons (Tipografi ikonografis)

---

## 7. Penanganan Kendala (Troubleshooting)

**Proses unggah foto tidak berhasil disimpan**
Pastikan direktori `admin/uploads/` telah tersedia dan perizinan tulis (write permission) pada layanan server telah diizinkan. 

**Kegagalan saat proses login meski kata sandi benar**
Pastikan data pengguna di dalam tabel telah menggunakan mekanisme *hash*. Sandi dasar yang tersimpan dalam format plaintext (teks biasa) tidak akan dikenali oleh sistem autentikasi modern aplikasi ini.

**Pesan error mengenai kolom yang tidak tersedia (Misal: FOTO_PROFIL tidak ditemukan)**
Hal ini menandakan basis data belum di-impor secara lengkap. Silakan lakukan proses impor ulang menggunakan berkas `minifut_db.sql` yang telah disediakan, yang mana sudah memuat rancangan lengkap seluruh kolom terkait tanpa butuh migrasi tambahan.

**Akses langsung pada halaman profil tertolak**
Halaman `profile.php` dikhususkan bagi pengguna yang telah terautentikasi. Lakukan proses login pada rute `auth/login.php` terlebih dahulu untuk membuka akses.