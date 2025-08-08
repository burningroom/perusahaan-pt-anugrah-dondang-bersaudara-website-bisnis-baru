<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => 'web']
        );

        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'id' => '9f40a349-0fcc-497c-83a3-94badfdccb80',
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('secret2025'),
            ]
        );

        if (!$user->hasRole('super_admin'))
            $user->assignRole('super_admin');
    }
}
