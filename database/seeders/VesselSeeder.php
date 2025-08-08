<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VesselMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class VesselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => '9f40a390-0dac-4f75-9ae5-c5e3670c3f51',
                'user_id' => '9f40a349-0fcc-497c-83a3-94badfdccb80',
                'registration_sign' => 'Tanda Kapal A',
                'name' => 'TugBoat Super',
                'code' => 'TBS',
                'type' => 'tb',
                'drt' => 100,
                'grt' => 100,
                'loa' => 100,
                'kind' => 'TUGBOAT',
                'width' => 100,
                'max_draft' => 100,
                'front_draft' => 100,
                'back_draft' => 100,
                'central_draft' => 100,
                'route_type' => 'TRYK_TB',
                'flag' => 'IDN',
                'call_sign' => 'CALL_TB',
                'imo_number' => 'IMO_TB',
                'created_at' => '2025-06-27 04:48:28',
                'updated_at' => '2025-06-27 04:48:28'
            ],
            [
                'id' => '9f40a3f3-8962-411b-abf7-a18b9259acdd',
                'user_id' => '9f40a349-0fcc-497c-83a3-94badfdccb80',
                'registration_sign' => 'Tanda Kapal B',
                'name' => 'Tongkang Super',
                'code' => 'TKS',
                'type' => 'bg',
                'drt' => 100,
                'grt' => 100,
                'loa' => 100,
                'kind' => 'TONGKANG',
                'width' => 100,
                'max_draft' => 100,
                'front_draft' => 100,
                'back_draft' => 100,
                'central_draft' => 100,
                'route_type' => 'TRYK_BG',
                'flag' => 'IDN',
                'call_sign' => 'CALL_BG',
                'imo_number' => 'IMO_BG',
                'created_at' => '2025-06-27 04:49:33',
                'updated_at' => '2025-06-27 04:49:33'
            ]
        ];
        VesselMaster::insert($data);
    }
}
