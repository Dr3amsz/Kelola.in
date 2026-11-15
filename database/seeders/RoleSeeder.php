<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'Pemilik Toko',
                'slug' => 'pemilik_toko',
            ],
            [
                'name' => 'Karyawan',
                'slug' => 'karyawan',
            ],
        ];

        foreach ($roles as $role) {
            // firstOrCreate mencegah duplikasi jika seeder dijalankan berulang kali
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
