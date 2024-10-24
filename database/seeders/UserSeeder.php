<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            User::Create([
                'name' => 'Admin',
                'email' => 'admin@mtservice.it',
                'password' => Hash::make('mtservice'),
                'role' => 'admin',
            ]);
        }

        User::Create([
            'name' => 'User',
            'email' => 'user@mtservice.it',
            'password' => Hash::make('mtservice'),
            'role' => 'user',
        ]);
    }
}
