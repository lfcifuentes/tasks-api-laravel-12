<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use App\Models\User;
// use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create default user
        $password = Hash::make('password');
        User::factory()->create([
            'name' => 'Luis Cifuentes',
            'email' => 'lfcifuentes28@gmail.com',
            'password' => $password,
            'email_verified_at' => now(),
        ]);
    }
}
