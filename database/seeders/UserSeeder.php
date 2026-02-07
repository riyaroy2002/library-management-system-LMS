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
        User::create([
            'first_name'  => 'Super',
            'last_name'   => 'Admin',
            'email'       => 'lms@yopmail.com',
            'contact_no'  => '9999999999',
            'password'    => 'Password123#',
            'is_verified' => 1,
            'is_block'    => 0,
            'role'        => 'admin'
        ]);
    }
}
