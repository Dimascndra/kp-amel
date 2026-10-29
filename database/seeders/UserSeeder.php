<?php

namespace Database\Seeders;

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
        User::updateOrCreate(
            ['email' => 'admin@bankmajalengka.co.id'],
            [
                'name' => 'Administrator BPR Majalengka',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0811-1111-6526',
                'email_verified_at' => now(),
            ]
        );
    }
}
