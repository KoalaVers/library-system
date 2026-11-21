# Library System

**Sistem Informasi Perpustakaan Sederhana** merupakan aplikasi berbasis web yang dikembangkan menggunakan Laravel 13 untuk membantu pengelolaan operasional perpustakaan, mulai dari pengelolaan buku, kategori, anggota, pengguna, hingga transaksi peminjaman dan pengembalian buku.

Aplikasi ini menerapkan konsep **Model-View-Controller (MVC)**, Object-Relational Mapping (ORM) menggunakan Eloquent, autentikasi, validasi data, serta pembagian hak akses berdasarkan peran pengguna.

## Fitur Utama

### 1. Authentication
- Login dan logout pengguna.
- Autentikasi menggunakan sistem Laravel.
- Perlindungan halaman menggunakan middleware authentication.
- Pembatasan akses berdasarkan peran pengguna, yaitu Admin dan Petugas.

### 2. User Management
- Menampilkan daftar pengguna.
- Menambahkan pengguna baru.
- Mengubah data pengguna.
- Menghapus pengguna.
- Mengatur peran pengguna sebagai Admin atau Petugas.

### 3. Category Management
- Menampilkan daftar kategori buku.
- Menambahkan kategori.
- Mengubah kategori.
- Menghapus kategori.
- Menghubungkan kategori dengan data buku.

### 4. Book Management
- Menampilkan daftar buku.
- Melihat detail buku.
- Menambahkan buku.
- Mengubah data buku.
- Menghapus buku.
- Mengelola informasi judul, penulis, tahun terbit, stok, dan kategori.
- Mengunggah dan mengelola sampul buku.

### 5. Member Management
- Menampilkan daftar anggota perpustakaan.
- Melihat detail anggota.
- Menambahkan anggota.
- Mengubah data anggota.
- Menghapus anggota.
- Mengelola kode anggota dan informasi kontak.
- Melindungi data anggota yang masih memiliki keterkaitan dengan transaksi peminjaman.

### 6. Borrowing Management
- Menampilkan daftar transaksi peminjaman.
- Mencatat transaksi peminjaman buku.
- Memilih anggota dan buku yang dipinjam.
- Menentukan jumlah buku yang dipinjam.
- Mencatat tanggal peminjaman dan batas pengembalian.
- Menentukan batas pengembalian satu minggu setelah tanggal peminjaman.
- Memvalidasi ketersediaan stok buku.
- Mengurangi stok buku secara otomatis ketika transaksi peminjaman dibuat.
- Memproses pengembalian buku.
- Memperbarui tanggal dan status pengembalian.
- Mengembalikan stok buku secara otomatis setelah buku dikembalikan.

### 7. Dashboard
Dashboard menampilkan ringkasan informasi perpustakaan, meliputi:
- Total judul buku.
- Total stok buku.
- Total anggota.
- Jumlah peminjaman yang masih berlangsung.

## Fitur Tambahan

Selain fitur utama, aplikasi juga dilengkapi beberapa fitur tambahan untuk meningkatkan kemudahan penggunaan dan pengelolaan data.

### 1. Pencarian Buku
Memungkinkan pengguna mencari data buku berdasarkan kata kunci sehingga informasi buku lebih mudah ditemukan.

### 2. Pagination
Membagi daftar buku ke dalam beberapa halaman agar tampilan data lebih terstruktur dan mudah dinavigasi.

### 3. Filter Data
Menyediakan penyaringan data buku berdasarkan kategori dan kondisi stok untuk memudahkan pencarian buku tertentu.

### 4. Dashboard Statistik
Menyajikan ringkasan statistik perpustakaan berupa jumlah judul buku, total stok, jumlah anggota, dan transaksi peminjaman yang masih berlangsung.

### 5. Upload Cover Buku
Memungkinkan pengunggahan dan pengelolaan gambar sampul buku sebagai informasi visual tambahan pada data buku.

### 6. Export Data Sederhana
Menyediakan fitur ekspor data ke format CSV, meliputi:
- Ekspor data buku.
- Ekspor data transaksi peminjaman.

## Hak Akses Pengguna

Aplikasi memiliki dua jenis pengguna, yaitu Admin dan Petugas. Setiap pengguna memiliki hak akses yang disesuaikan dengan tanggung jawabnya.

| Fitur | Admin | Petugas |
|---|:---:|:---:|
| Login dan Logout | ✓ | ✓ |
| Dashboard | ✓ | ✓ |
| Manajemen Pengguna | ✓ | — |
| Melihat Kategori | ✓ | ✓ |
| Mengelola Kategori | ✓ | — |
| Melihat Buku | ✓ | ✓ |
| Menambah, Mengubah, dan Menghapus Buku | ✓ | — |
| Manajemen Anggota | ✓ | ✓ |
| Manajemen Peminjaman | ✓ | ✓ |
| Pengembalian Buku | ✓ | ✓ |

Pembatasan hak akses diterapkan menggunakan middleware dan mekanisme otorisasi Laravel sesuai kebutuhan masing-masing fitur.

## Teknologi yang Digunakan

- **PHP 8.4** – Bahasa pemrograman backend.
- **Laravel 13** – Framework pengembangan aplikasi web.
- **MySQL** – Sistem manajemen basis data.
- **Eloquent ORM** – Pengelolaan data dan relasi basis data.
- **Blade** – Template engine untuk membangun tampilan aplikasi.
- **HTML, CSS, dan JavaScript** – Teknologi pengembangan antarmuka.
- **Composer** – Dependency manager PHP.
- **Node.js dan NPM** – Pengelolaan dependency dan proses build frontend.
- **Git dan GitHub** – Version control dan kolaborasi pengembangan proyek.

## Struktur Database

Aplikasi menggunakan enam tabel utama yang saling berhubungan untuk mendukung proses pengelolaan perpustakaan.

| Tabel | Keterangan |
|---|---|
| `users` | Menyimpan data pengguna dan peran akses. |
| `categories` | Menyimpan data kategori buku. |
| `books` | Menyimpan informasi buku, kategori, sampul, dan stok. |
| `members` | Menyimpan data anggota perpustakaan. |
| `borrowings` | Menyimpan transaksi peminjaman. |
| `borrowing_details` | Menyimpan rincian buku dalam setiap transaksi peminjaman. |

### Relasi Antar Tabel

- `categories` memiliki relasi one-to-many dengan `books`.
- `users` memiliki relasi one-to-many dengan `borrowings`.
- `members` memiliki relasi one-to-many dengan `borrowings`.
- `borrowings` memiliki relasi one-to-many dengan `borrowing_details`.
- `books` memiliki relasi one-to-many dengan `borrowing_details`.
- `borrowings` dan `books` memiliki relasi many-to-many melalui tabel `borrowing_details`.

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:
- PHP 8.4 atau versi yang kompatibel dengan Laravel 13.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM.
- Git.
- Web browser.

## Instalasi dan Konfigurasi

### 1. Clone Repository

Buka terminal, kemudian jalankan perintah:

```bash
git clone https://github.com/KoalaVers/library-system.git
```

Masuk ke direktori proyek:

```bash
cd library-system
```

### 2. Instal Dependency

Instal dependency PHP menggunakan Composer:

```bash
composer install
```

Kemudian instal dependency frontend:

```bash
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Pada Windows CMD:

```cmd
copy .env.example .env
```

Buat application key menggunakan perintah:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Buat database MySQL dengan nama:

```text
library-system
```

Selanjutnya, sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library-system
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password berdasarkan konfigurasi MySQL masing-masing.

### 5. Jalankan Migration dan Seeder

Jalankan perintah berikut untuk membuat tabel sekaligus mengisi data awal aplikasi:

```bash
php artisan migrate --seed
```

Seeder akan membuat akun Admin, Petugas, dan data buku awal yang telah disediakan.

### 6. Konfigurasi Penyimpanan Sampul Buku

Jalankan perintah berikut untuk membuat symbolic link penyimpanan file agar sampul buku dapat diakses melalui direktori publik:

```bash
php artisan storage:link
```

### 7. Build Frontend

Jalankan perintah berikut untuk membangun aset frontend:

```bash
npm run build
```

### 8. Jalankan Aplikasi

Jalankan server pengembangan Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

## Akun Demo

Akun berikut tersedia setelah menjalankan seeder:

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password123 |
| Petugas | petugas@example.com | password123 |

**Catatan:** Akun demo digunakan untuk kebutuhan pengembangan dan pengujian lokal. Untuk penggunaan pada lingkungan produksi, gunakan kredensial yang aman.

## Struktur Proyek

Aplikasi dikembangkan menggunakan struktur MVC Laravel dengan beberapa direktori utama:

| Direktori | Fungsi |
|---|---|
| `app/Models` | Menyimpan model dan relasi basis data. |
| `app/Http/Controllers` | Mengelola logika aplikasi. |
| `app/Http/Middleware` | Mengatur middleware autentikasi dan hak akses. |
| `app/Policies` | Mengatur otorisasi akses terhadap fitur dan data. |
| `database/migrations` | Mengatur struktur tabel basis data. |
| `database/seeders` | Menyediakan data awal aplikasi. |
| `resources/views` | Menyimpan tampilan aplikasi berbasis Blade. |
| `routes/web.php` | Mengatur routing aplikasi. |
| `public` | Menyimpan aset publik dan akses file storage. |

## Pengujian

Untuk menjalankan pengujian otomatis aplikasi menggunakan Laravel, jalankan:

```bash
php artisan test
```

## Repository

Source code aplikasi dapat diakses melalui GitHub:

https://github.com/KoalaVers/library-system

## Anggota Kelompok

| No. | Nama | NIM |
|:---:|---|:---:|
| 1 | Annisa Balqis Kusuma Putri | 2410631250005 |
| 2 | Akbar Tri Putranto | 2410631250002 |
| 3 | Raynaldi Dwi Arnanto | 2410631250072 |

**Kelas: 5A**