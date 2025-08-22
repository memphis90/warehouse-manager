<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['pending', 'approved', 'rejected', 'delivered', 'returned'];
        
        foreach ($statuses as $status) {
            DB::table('request_statuses')->insert([
                'name' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
