<?php

namespace Database\Seeders;

use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = ['super-admin', 'admin-bup', 'admin-pandu', 'admin-bongkar-muat', 'pandu', 'agent', 'finance'];

        foreach ($datas as $data) {

            Role::create([
                'name' => $data
            ]);

        }
    }
}
