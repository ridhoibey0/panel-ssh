<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'balance' => 100000
        ]);

        $customer->assignRole('customer');

        $reseller = User::create([
            'name' => 'Reseller',
            'email' => 'reseller@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        $reseller->assignRole('reseller');
    }
}
