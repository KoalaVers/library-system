# Library System

**Sistem Informasi Perpustakaan Sederhana** merupakan aplikasi berbasis web yang dikembangkan menggunakan Laravel untuk membantu pengelolaan data perpustakaan, mulai dari buku, kategori, anggota, pengguna, hingga transaksi peminjaman dan pengembalian buku.

Aplikasi ini menerapkan konsep **Model-View-Controller (MVC)**, autentikasi, serta pembagian hak akses berdasarkan peran pengguna.

## Fitur Utama

### 1. Authentication
- Login pengguna.
- Logout pengguna.
- Perlindungan halaman menggunakan middleware authentication.

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

### 4. Book Management
- Menampilkan daftar buku.
- Melihat detail buku.
- Menambahkan buku.
- Mengubah data buku.
- Menghapus buku.
- Menghubungkan buku dengan kategori.

### 5. Member Management
- Menampilkan daftar anggota.
- Melihat detail anggota.
- Menambahkan anggota.
- Mengubah data anggota.
- Menghapus anggota.

### 6. Borrowing Management
- Mencatat transaksi peminjaman buku.
- Memilih anggota dan buku yang dipinjam.
- Mencatat tanggal peminjaman dan tanggal pengembalian yang ditentukan.
- Menentukan batas pengembalian satu minggu setelah tanggal peminjaman.
- Mengurangi stok buku ketika transaksi peminjaman dibuat.
- Memproses pengembalian buku.
- Memperbarui status transaksi dan mengembalikan stok buku.

### 7. Dashboard
Dashboard menampilkan ringkasan informasi perpustakaan, meliputi:
- Total judul buku.
- Total stok buku.
- Total anggota.
- Jumlah peminjaman yang masih berlangsung.

## Hak Akses Pengguna

Aplikasi memiliki dua jenis pengguna:

| Fitur | Admin | Petugas |
|---|:---:|:---:|
| Login dan Logout | ✓ | ✓ |
| Dashboard | ✓ | ✓ |
| Manajemen Pengguna | ✓ | — |
| Melihat Kategori | ✓ | ✓ |
| Mengelola Kategori | ✓ | — |
| Manajemen Buku | ✓ | ✓ |
| Manajemen Anggota | ✓ | ✓ |
| Manajemen Peminjaman | ✓ | ✓ |
| Pengembalian Buku | ✓ | ✓ |

## Teknologi yang Digunakan

- **PHP** – Bahasa pemrograman backend.
- **Laravel 13** – Framework pengembangan aplikasi web.
- **MySQL** – Sistem manajemen basis data.
- **Composer** – Dependency manager PHP.
- **Git dan GitHub** – Version control dan kolaborasi pengembangan proyek.
- **HTML dan Blade** – Penyusunan tampilan aplikasi.

## Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat sudah memiliki:
- PHP 8.4 atau versi yang kompatibel.
- Composer.
- MySQL.
- Git.
- Web browser.

## Instalasi dan Konfigurasi

### 1. Clone Repository

Buka terminal, kemudian jalankan:

```bash
git clone https://github.com/KoalaVers/library-system.git
```

Masuk ke direktori proyek:

```bash
cd library-system
```

### 2. Instal Dependency

Jalankan perintah:

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Pada Windows CMD:

```cmd
copy .env.example .env
```

Kemudian buat application key:

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

### 5. Jalankan Migration

Jalankan perintah berikut untuk membuat tabel database:

```bash
php artisan migrate
```

### 6. Jalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

**Catatan:** Akun pengguna harus tersedia di database agar dapat melakukan login. Gunakan akun yang telah disiapkan melalui seeder atau dibuat oleh Admin.

## Struktur Proyek

Aplikasi dikembangkan menggunakan struktur MVC Laravel dengan beberapa direktori utama:

| Direktori | Fungsi |
|---|---|
| `app/Models` | Menyimpan model dan relasi database. |
| `app/Http/Controllers` | Mengelola logika aplikasi. |
| `app/Http/Middleware` | Mengatur autentikasi dan hak akses. |
| `database/migrations` | Mengatur struktur tabel database. |
| `database/seeders` | Menyediakan data awal aplikasi. |
| `resources/views` | Menyimpan tampilan aplikasi berbasis Blade. |
| `routes/web.php` | Mengatur routing aplikasi. |

## Pengujian

Untuk menjalankan pengujian otomatis Laravel, gunakan:

```bash
php artisan test
```

## Repository

GitHub: https://github.com/KoalaVers/library-system


## Anggota Kelompok


| 1 | Annisa Balqis Kusuma Putri | 2410631250005 |
| 2 | Akbar Tri Putranto | 2410631250002 |
| 3 | Raynaldi Dwi Arnanto | 2410631250072 |

**Kelas: 5A**