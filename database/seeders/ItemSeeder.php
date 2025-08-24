<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Dell Monitor 24"', 'description' => '24 inch Dell professional monitor', 'category_id' => 1, 'quantity' => 10, 'status' => 'available'],
            ['name' => 'LG Monitor 27"', 'description' => '27 inch LG gaming monitor', 'category_id' => 1, 'quantity' => 10, 'status' => 'available'],
            ['name' => 'Mechanical Keyboard', 'description' => 'RGB mechanical keyboard', 'category_id' => 2, 'quantity' => 10, 'status' => 'available'],
            ['name' => 'Wireless Mouse', 'description' => 'Ergonomic wireless mouse', 'category_id' => 2, 'quantity' => 10, 'status' => 'available'],
            ['name' => 'Canon Camera', 'description' => 'Professional DSLR camera', 'category_id' => 4, 'quantity' => 10, 'status' => 'available'],
            ['name' => 'Office Chair', 'description' => 'Ergonomic office chair', 'category_id' => 3, 'quantity' => 10, 'status' => 'available'],
        ];
        
        foreach ($items as $item) {
            DB::table('items')->insert([
                'name' => $item['name'],
                'description' => $item['description'],
                'category_id' => $item['category_id'],
                'quantity' => $item['quantity'],
                'status' => $item['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}