<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $pemilikRole = Role::where('slug', 'pemilik_toko')->first();
        $karyawanRole = Role::where('slug', 'karyawan')->first();

        $users = [
            [
                'name' => 'Admin Toko',
                'username' => 'admin',
                'email' => 'admin@kelolain.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Pemilik Toko',
                'username' => 'pemilik',
                'email' => 'pemilik@kelolain.com',
                'password' => Hash::make('password123'),
                'role_id' => $pemilikRole?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Karyawan Kasir',
                'username' => 'karyawan',
                'email' => 'karyawan@kelolain.com',
                'password' => Hash::make('password123'),
                'role_id' => $karyawanRole?->id,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            // firstOrCreate: mencari akun berdasarkan username
            // 1. Jika data belum ada: Akun baru dibuat dengan password yang di-hash.
            // 2. Jika data sudah ada: Record tidak diubah sama sekali, sehingga
            //    password yang sudah diganti oleh pengguna TIDAK AKAN tertimpa kembali.
            User::firstOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                    'role_id' => $userData['role_id'],
                    'is_active' => $userData['is_active'],
                ]
            );
        }
    }
}
