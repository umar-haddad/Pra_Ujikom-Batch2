<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate(); //-> seperti menghapus semua data diganti dengan data dibawah


        User::create(
            [
                'id_level' => 1,
                'name' => 'Umar',
                'email' => 'umar@gmail.com',
                'password' => Hash::make('123'),
            ],
        );
        User::create(
            [
                'id_level' => 2,
                'name' => 'admin',
                'email' => 'admin123@gmail.com',
                'password' => Hash::make('123'),
            ]
        );
    }
}
