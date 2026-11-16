# Panduan Pengembang Proyek: Sistem Informasi Toko Sembako (Kelola.in)

Dokumen ini disusun sebagai pedoman arsitektur, standar penulisan kode (*coding convention*), alur kerja Git, dan panduan instalasi bagi seluruh anggota tim pengembang proyek **Kelola.in**.

---

## 1. Deskripsi Sistem Informasi Toko Sembako

**Kelola.in** adalah sistem informasi manajemen ritel sembako (sembilan bahan pokok) berbasis web yang dirancang untuk mendigitalkan seluruh operasional toko sembako:
* Pengelolaan inventaris sembako (beras, minyak, gula, telur, tepung, bumbu dapur, dll.).
* Manajemen satuan barang multi-kemasan (kilogram, liter, sak, dus, renteng, pcs).
* Pencatatan transaksi penjualan kasir dan rekap pemasukan harian.
* Pengendalian persediaan menggunakan metode **RORIS (*Re-Order Point Inventory System*)** untuk mendeteksi stok yang menipis dan mencatat pengadaan (*restock*) dari pemasok.
* Dasbor ringkasan eksekutif dan laporan keuangan untuk pemilik toko.

---

## 2. Teknologi yang Digunakan

* **Bahasa Pemrograman**: PHP 8.3+
* **Framework Backend**: Laravel 13 / 11 (Modern Architecture)
* **Database**: MySQL / MariaDB (via XAMPP)
* **Frontend Tooling**: Vite 8 & Tailwind CSS v4
* **Template Engine**: Laravel Blade
* **Version Control**: Git & GitHub
* **Editor**: Visual Studio Code

---

## 3. Tiga Aktor Sistem & Hak Akses

Sistem memiliki 3 tingkatan peran (*Role-Based Access Control / RBAC*):

| Aktor / Role | Slug | Hak Akses Utama |
| :--- | :--- | :--- |
| **Admin** | `admin` | • Manajemen Pengguna (CRUD akun, aktifkan/nonaktifkan user).<br>• Manajemen Role & Permission.<br>• Akses konfigurasi sistem dan pemeliharaan data. |
| **Pemilik Toko** | `pemilik_toko` | • Melihat Dasbor Ringkasan Bisnis (omset, transaksi, tren).<br>• Melihat dan mengekspor Laporan Pemasukan Harian/Bulanan.<br>• Melihat Laporan RORIS (stok menipis, pengadaan sembako). |
| **Karyawan (Kasir / Staf)** | `karyawan` | • Melakukan input transaksi kasir harian.<br>• Memeriksa ketersediaan stok barang sembako.<br>• Mencatat barang masuk / permohonan pengadaan barang (*restock*).<br>• *Dibatasi: Tidak dapat mengakses manajemen pengguna atau laporan finansial pemilik toko.* |

---

## 4. Pembagian Tugas 4 Anggota Kelompok

Setiap anggota memiliki fokus modul dan branch Git masing-masing:

### 1. Fadilah — Autentikasi, Hak Akses, & Kelola User (Admin)
* **Branch**: `feature/auth-user-admin`
* **Ruang Lingkup**:
  * Fitur Login, Logout, dan Pengaturan Profil.
  * Middleware Autentikasi & Verifikasi Status Akun (`is_active`).
  * CRUD Manajemen Pengguna untuk Admin.
  * Seeder Role, Permission, dan akun default.

### 2. Rizki — Kelola Barang, Kategori, & Satuan
* **Branch**: `feature/barang-kategori-satuan`
* **Ruang Lingkup**:
  * CRUD Kategori Barang (Bahan Pokok, Bumbu, Minuman, Produk Kemasan).
  * CRUD Satuan Barang (Kg, Liter, Sak, Dus, Pcs).
  * CRUD Master Data Barang (Kode/Barcode, Nama, Harga Beli, Harga Jual, Stok, Stok Minimum).

### 3. Nadrina — RORIS & Laporan RORIS
* **Branch**: `feature/roris-laporan`
* **Ruang Lingkup**:
  * Implementasi logika RORIS (*Re-Order Point* berdasarkan batas stok minimum & pengadaan barang).
  * Peringatan dini (*alert*) barang yang harus segera dipesan ulang ke supplier.
  * Pencatatan Barang Masuk / Restock dari pemasok.
  * Cetak & Ekspor Laporan Riwayat Pengadaan RORIS.

### 4. Immanuel — Pemasukan Harian & Dashboard
* **Branch**: `feature/pemasukan-dashboard`
* **Ruang Lingkup**:
  * Antarmuka Kasir / Point of Sales (Input transaksi sembako, kalkulasi total, bayar, kembali).
  * Rekapitulasi Laporan Pemasukan Harian, Mingguan, dan Bulanan (filter tanggal & metode bayar).
  * Dasbor Utama: Kartu ringkasan omset harian, grafik tren penjualan, dan daftar produk terlaris (*fast-moving*).

---

## 5. Struktur Database dan Relasi Antar Tabel

```
                ┌──────────────┐
                │    roles     │
                └──┬────────┬──┘
                   │1       │N
                   │        └───┐
                   │N           │ (permission_role)
                ┌──▼───┐     ┌──▼──────────┐
                │users │     │ permissions │
                └──┬───┘     └─────────────┘
                   │1
         ┌─────────┴─────────┐
         │1                  │1
   ┌─────▼──────────┐  ┌─────▼──────────┐
   │pemasukan_harian│  │ roris_restock  │
   └─────┬──────────┘  └─────┬──────────┘
         │1                  │1
         │N                  │N
   ┌─────▼──────────┐  ┌─────▼──────────┐
   │detail_pemasukan│  │  detail_roris  │
   └─────▲──────────┘  └─────▲──────────┘
         │N                  │N
         └─────────┬─────────┘
                   │1
              ┌────┴───┐
              │ barang │
              └─▲────▲─┘
               N│    │N
       ┌────────┘    └────────┐
      1│                     1│
┌──────┴──────────┐     ┌─────┴────────┐
│ kategori_barang │     │satuan_barang │
└─────────────────┘     └──────────────┘
```

### Relasi Eloquent:
1. **User & Role**:
   * `User belongsTo Role` ($user->role)
   * `Role hasMany User` ($role->users)
2. **Role & Permission**:
   * `Role belongsToMany Permission` (via `permission_role`)
   * `Permission belongsToMany Role` (via `permission_role`)
3. **Barang & Kategori / Satuan**:
   * `Barang belongsTo KategoriBarang`
   * `Barang belongsTo SatuanBarang`
4. **Transaksi Pemasukan & Barang**:
   * `PemasukanHarian hasMany DetailPemasukan`
   * `DetailPemasukan belongsTo Barang`
   * `PemasukanHarian belongsTo User` (Kasir pencatat)
5. **RORIS Restock & Barang**:
   * `RorisRestock hasMany DetailRorisRestock`
   * `DetailRorisRestock belongsTo Barang`
   * `RorisRestock belongsTo User` (Staf pencatat)

---

## 6. Konvensi Penamaan File (Laravel Naming Conventions)

Patuhi standar penulisan resmi Laravel berikut:

| Komponen | Format Penamaan | Contoh Benar | Contoh Salah |
| :--- | :--- | :--- | :--- |
| **Model** | PascalCase, Singular (Tunggal) | `Barang.php`, `KategoriBarang.php` | `barangs.php`, `DataBarang.php` |
| **Controller** | PascalCase, Berakhiran `Controller` | `BarangController.php`, `UserController.php` | `Barang.php`, `barang_ctrl.php` |
| **Migration** | snake_case, Bentuk Jamak (Plural) | `create_barang_table.php`, `create_roles_table.php` | `create_table_barang.php` |
| **View (Blade)** | kebab-case / snake_case, di dalam subfolder | `resources/views/barang/index.blade.php` | `views/viewBarang.blade.php` |
| **Nama Rute** | snake/kebab-case dengan format titik | `barang.index`, `pemasukan.create` | `barang_index`, `lihat-barang` |

---

## 7. Konvensi Route dan Middleware

### Struktur Route Modular
Untuk menghindari *merge conflict* pada file `routes/web.php`, setiap modul memiliki file rute tersendiri di dalam direktori `routes/modules/`:

```
routes/
├── web.php                   <-- Hanya me-load file modul
└── modules/
    ├── auth.php              <-- Dikelola Fadilah
    ├── barang.php            <-- Dikelola Rizki
    ├── roris.php             <-- Dikelola Nadrina
    └── pemasukan.php         <-- Dikelola Immanuel
```

Pada `routes/web.php`:
```php
require __DIR__.'/modules/auth.php';
require __DIR__.'/modules/barang.php';
require __DIR__.'/modules/roris.php';
require __DIR__.'/modules/pemasukan.php';
```

### Konvensi Middleware Pembatasan Akses:
* Gunakan middleware `auth` untuk seluruh halaman yang memerlukan login.
* Lindungi route sesuai role masing-masing:
```php
// Contoh rute khusus Admin:
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Contoh rute Kasir & Admin:
Route::middleware(['auth', 'role:karyawan,admin'])->group(function () {
    Route::resource('pemasukan', PemasukanController::class);
});
```

---

## 8. Konvensi Branch Git & Kolaborasi

### 1. Struktur Cabang (*Branching Model*)
* **`main`**: Kode stabil yang siap dipresentasikan / dirilis.
* **`develop`**: Cabang integrasi bersama seluruh tim.
* **`feature/*`**: Cabang fitur milik masing-masing pengembang.

### 2. Format Pesan Commit (*Conventional Commits*)
Tulis pesan commit secara deskriptif:
* `feat: tambah form input barang sembako` (fitur baru)
* `fix: perbaiki kalkulasi kembalian kasir` (perbaikan bug)
* `docs: perbarui panduan instalasi` (perubahan dokumentasi)
* `style: perbaiki tampilan tabel daftar user` (tampilan/UI)

### 3. Alur Kerja Sebelum Mendorong Kode (*Push*)
1. Pindah ke branch fitur Anda: `git checkout feature/nama-fitur`
2. Tarik pembaruan dari develop: `git pull origin develop`
3. Selesaikan konflik lokal jika ada, lalu uji coba sistem.
4. Lakukan commit dan push: `git push origin feature/nama-fitur`
5. Buka Pull Request (PR) ke branch `develop`.

---

## 9. Cara Instalasi Proyek (Bagi Anggota Baru / Clone Baru)

Bagi anggota kelompok yang baru mengunduh proyek dari GitHub, ikuti langkah berikut di terminal VS Code:

1. **Clone repositori**:
   ```bash
   git clone https://github.com/ursystempass/Kelola.in.git
   cd kelolain
   ```

2. **Instal dependensi PHP**:
   ```bash
   composer install
   ```

3. **Instal dependensi JavaScript / CSS**:
   ```bash
   npm install
   ```

4. **Salin file lingkungan (*Environment*)**:
   ```bash
   cp .env.example .env
   ```
   *(Pada Windows PowerShell bisa menggunakan: `copy .env.example .env`)*

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database di file `.env`**:
   Pastikan Apache dan MySQL di XAMPP sudah berjalan (*running*). Buka `.env` dan sesuaikan:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kelolain
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Buat database baru bernama `kelolain` di phpMyAdmin jika belum ada)*.

7. **Jalankan Migration dan Seeder**:
   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan Frontend Build & Server**:
   Buka dua tab terminal di VS Code:
   * Terminal 1: `npm run dev`
   * Terminal 2: `php artisan serve`
   Akses aplikasi di browser melalui: `http://localhost:8000`

---

## 10. Cara Menjalankan Migration dan Seeder

* **Menjalankan migrasi baru**:
  ```bash
  php artisan migrate
  ```
* **Mereset ulang database secara bersih (Hati-hati: menghapus semua data lama)**:
  ```bash
  php artisan migrate:fresh --seed
  ```
* **Menjalankan seeder tertentu**:
  ```bash
  php artisan db:seed --class=RolePermissionSeeder
  ```

---

## 11. Aturan Emas Kolaborasi Tim (Mencegah Konflik)

1. **DILARANG mengubah migration yang sudah di-merge ke develop/main**:
   Jika Anda membutuhkan perubahan kolom, buat file migration baru bertipe `add_kolom_to_tabel_table.php`.
2. **DILARANG mengedit controller, view, atau model milik anggota lain**:
   Jika membutuhkan integrasi antar-modul (contoh: Immanuel membutuhkan data barang dari Rizki), panggil Model `Barang` yang sudah ada, jangan membuat model tandingan.
3. **JANGAN PERNAH meng-commit file `.env`**:
   File `.env` berisi konfigurasi lokal masing-masing perangkat dan wajib masuk ke `.gitignore`.
4. **Wajib `git pull` setiap hari**:
   Sebelum mulai menulis kode baru di hari kerja, selalu tarik (*pull*) pembaruan dari branch `develop` agar kode lokal Anda tidak tertinggal jauh.
5. **Komunikasi Aktif**:
   Jika ada perubahan struktur basis data yang berdampak pada modul lain, komunikasikan terlebih dahulu di grup tim sebelum melakukan *merge*.

---

*Panduan ini dibuat untuk kelancaran pengerjaan Sistem Informasi Toko Sembako (Kelola.in). Selamat berkarya!*
