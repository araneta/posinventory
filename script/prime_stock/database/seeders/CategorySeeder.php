<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'status' => 1],
            ['name' => 'Clothing', 'status' => 1],
            ['name' => 'Books', 'status' => 1],
            ['name' => 'Home & Garden', 'status' => 1],
        ];

        $units = [
            ['name' => 'Piece', 'status' => 1],
            ['name' => 'Kilogram', 'status' => 1],
            ['name' => 'Liter', 'status' => 1],
            ['name' => 'Meter', 'status' => 1],
        ];

        $brands = [
            ['name' => 'Apple', 'status' => 1],
            ['name' => 'Samsung', 'status' => 1],
            ['name' => 'Nike', 'status' => 1],
            ['name' => 'Adidas', 'status' => 1],
        ];

        $warehouses = [
            ['name' => 'Main Warehouse', 'address' => '123 Main St, City', 'status' => 1],
            ['name' => 'Secondary Warehouse', 'address' => '456 Side St, Town', 'status' => 1],
        ];

        $variations = [
            ['name' => 'Color', 'values' => ['Red', 'Blue', 'Green']],
            ['name' => 'Size', 'values' => ['Small', 'Medium', 'Large']],
        ];

    

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        foreach ($units as $unit) {
            \App\Models\Unit::create($unit);
        }

        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }

        foreach ($warehouses as $warehouse) {
            \App\Models\Warehouse::create($warehouse);
        }

        foreach ($variations as $variation) {
            \App\Models\Variation::create($variation);
        }
    }
}
