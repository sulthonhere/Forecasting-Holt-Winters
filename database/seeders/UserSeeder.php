<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'name' => 'Admin Sanjaya',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'role' => 1,
            'jabatan' => 'Admin All Role',
            'created_by' => 0
        ]);

        User::updateOrCreate([
            'name' => 'Mulyono',
            'email' => 'manager1@gmail.com',
            'password' => 'manager',
            'role' => 2,
            'jabatan' => 'Manager Utama',
            'created_by' => 0
        ]);
        User::updateOrCreate([
            'name' => 'Soebandi',
            'email' => 'manager2@gmail.com',
            'password' => 'manager',
            'role' => 2,
            'jabatan' => 'Kepala Operasional',
            'created_by' => 0
        ]);

        User::updateOrCreate([
            'name' => 'Putri Tanjung Timur',
            'email' => 'staff1@gmail.com',
            'password' => 'staff',
            'role' => 3,
            'jabatan' => 'Staff Tata Kelola 1',
            'created_by' => 0
        ]);
        User::updateOrCreate([
            'name' => 'Putra Huta Barat',
            'email' => 'staff2@gmail.com',
            'password' => 'staff',
            'role' => 3,
            'jabatan' => 'Staff Tata Kelola 2',
            'created_by' => 0
        ]);
    }
}
