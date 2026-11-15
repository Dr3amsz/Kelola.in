<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Master daftar permission sesuai modul Toko Sembako
        $permissions = [
            // Modul Autentikasi & Kelola User (Fadilah)
            ['name' => 'Kelola Pengguna', 'slug' => 'kelola-user'],
            ['name' => 'Kelola Hak Akses', 'slug' => 'kelola-role-permission'],

            // Modul Master Data Barang, Kategori, & Satuan (Rizki)
            ['name' => 'Kelola Kategori Barang', 'slug' => 'kelola-kategori'],
            ['name' => 'Kelola Satuan Barang', 'slug' => 'kelola-satuan'],
            ['name' => 'Kelola Barang', 'slug' => 'kelola-barang'],
            ['name' => 'Lihat Katalog Barang', 'slug' => 'lihat-barang'],

            // Modul RORIS & Restock (Nadrina)
            ['name' => 'Kelola Pengadaan RORIS', 'slug' => 'kelola-roris'],
            ['name' => 'Lihat Laporan RORIS', 'slug' => 'lihat-laporan-roris'],

            // Modul Kasir, Pemasukan Harian, & Dashboard (Immanuel)
            ['name' => 'Input Transaksi Kasir', 'slug' => 'input-transaksi'],
            ['name' => 'Lihat Pemasukan Harian', 'slug' => 'lihat-pemasukan'],
            ['name' => 'Lihat Laporan Pemasukan', 'slug' => 'lihat-laporan-pemasukan'],
            ['name' => 'Lihat Dashboard', 'slug' => 'lihat-dashboard'],
        ];

        // Buat atau cari permission agar tidak terjadi duplikasi
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name']]
            );
        }

        // 2. Pemetaan Hak Akses ke Masing-Masing Role
        $adminRole = Role::where('slug', 'admin')->first();
        $pemilikRole = Role::where('slug', 'pemilik_toko')->first();
        $karyawanRole = Role::where('slug', 'karyawan')->first();

        // Admin: Hak akses penuh ke seluruh fitur sistem
        if ($adminRole) {
            $allPermissionIds = Permission::pluck('id')->toArray();
            $adminRole->permissions()->syncWithoutDetaching($allPermissionIds);
        }

        // Pemilik Toko: Pengawasan bisnis, dashboard, laporan pemasukan, cek stok, dan laporan RORIS
        if ($pemilikRole) {
            $pemilikPermissions = Permission::whereIn('slug', [
                'lihat-dashboard',
                'lihat-pemasukan',
                'lihat-laporan-pemasukan',
                'lihat-barang',
                'lihat-laporan-roris',
            ])->pluck('id')->toArray();

            $pemilikRole->permissions()->syncWithoutDetaching($pemilikPermissions);
        }

        // Karyawan: Operasional kasir, melihat katalog barang, rekap transaksi hari berjalan, dan input restock
        if ($karyawanRole) {
            $karyawanPermissions = Permission::whereIn('slug', [
                'lihat-barang',
                'input-transaksi',
                'lihat-pemasukan',
                'kelola-roris',
            ])->pluck('id')->toArray();

            $karyawanRole->permissions()->syncWithoutDetaching($karyawanPermissions);
        }
    }
}
