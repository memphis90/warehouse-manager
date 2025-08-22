<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'existing_item',
            'purchase_request'
        ];
        
        foreach ($types as $type) {
            DB::table('request_types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
