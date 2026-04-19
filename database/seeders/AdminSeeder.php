<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@yd.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'manager@yd.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('manager123'),
                'is_active' => true,
            ]
        );
    }
}
