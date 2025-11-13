<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username'  => 'admin',
                'email'     => 'admin2@example.com',
                'full_name' => 'Administrator',
                'role'      => 'admin',
                'status'    => 'active',
                'password'  => Hash::make('admin123'),
            ],
            [
                'username'  => 'teamlead',
                'email'     => 'teamlead@example.com',
                'full_name' => 'Team Lead',
                'role'      => 'teamlead',
                'status'    => 'active',
                'password'  => Hash::make('teamlead123'),
            ],
            [
                'username'  => 'developer',
                'email'     => 'developer@example.com',
                'full_name' => 'Developer',
                'role'      => 'developer',
                'status'    => 'active',
                'password'  => Hash::make('developer123'),
            ],
            [
                'username'  => 'designer',
                'email'     => 'designer@example.com',
                'full_name' => 'Designer',
                'role'      => 'designer',
                'status'    => 'active',
                'password'  => Hash::make('designer123'),
            ],
            [
                'username'  => 'user',
                'email'     => 'user@example.com',
                'full_name' => 'User',
                'role'      => 'user',
                'status'    => 'active',
                'password'  => Hash::make('user123'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']], // cek berdasarkan email
                $data // update field-field lainnya
            );
        }
    }
}
