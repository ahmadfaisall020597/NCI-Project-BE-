<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@news.com',
            'password' => Hash::make('AdminPassword123'),
        ]);

        User::create([
            'name' => 'Admin2',
            'email' => 'admin2@news.com',
            'password' => Hash::make('Admin2Password123')
        ]);
    }
}
