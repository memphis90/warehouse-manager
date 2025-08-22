<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Monitors', 'description' => 'Computer monitors and displays'],
            ['name' => 'Keyboards', 'description' => 'Keyboards and input devices'],
            ['name' => 'Office Supplies', 'description' => 'General office supplies'],
            ['name' => 'Cameras', 'description' => 'Cameras and recording equipment'],
            ['name' => 'Others', 'description' => 'Other miscellaneous items'],
        ];
        
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
