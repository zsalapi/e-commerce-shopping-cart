<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\Product::create([
            'name' => 'Sample Product',
            'price' => 19.99,
            'stock_quantity' => 10
        ]);
    }
}
