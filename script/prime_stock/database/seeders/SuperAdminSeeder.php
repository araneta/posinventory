<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Mr Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $user->assignRole('Super Admin');
    }
}
