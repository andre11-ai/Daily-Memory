<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'appe' => 'Principal',
            'email' => 'admin@dailymemory.com',
            'username' => 'admin',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuario',
            'appe' => 'Regular',
            'email' => 'usuario@dailymemory.com',
            'username' => 'usuario',
            'password' => Hash::make('usuario1234'),
            'role' => 'user',
        ]);
    }
}
