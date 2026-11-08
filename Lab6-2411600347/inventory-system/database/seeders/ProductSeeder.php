<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Plywood Sheet 4x8ft', 'sku' => 'LUM-001', 'category' => 'Lumber', 'quantity' => 45, 'reorder_level' => 15, 'unit_price' => 28.99, 'supplier' => 'National Lumber Co.'],
            ['name' => 'Treated 2x4 Lumber', 'sku' => 'LUM-002', 'category' => 'Lumber', 'quantity' => 8, 'reorder_level' => 20, 'unit_price' => 6.50, 'supplier' => 'National Lumber Co.'],
            ['name' => 'Plywood Sheet 1/2in', 'sku' => 'LUM-003', 'category' => 'Lumber', 'quantity' => 0, 'reorder_level' => 10, 'unit_price' => 22.00, 'supplier' => 'National Lumber Co.'],
            ['name' => 'Galvanized Nails 5kg', 'sku' => 'HW-001', 'category' => 'Hardware', 'quantity' => 60, 'reorder_level' => 20, 'unit_price' => 12.50, 'supplier' => 'FastenPro'],
            ['name' => 'Hex Bolts Assorted Set', 'sku' => 'HW-002', 'category' => 'Hardware', 'quantity' => 5, 'reorder_level' => 10, 'unit_price' => 15.75, 'supplier' => 'FastenPro'],
            ['name' => 'Door Hinges (Pair)', 'sku' => 'HW-003', 'category' => 'Hardware', 'quantity' => 32, 'reorder_level' => 10, 'unit_price' => 8.99, 'supplier' => 'FastenPro'],
            ['name' => 'Cordless Drill 18V', 'sku' => 'TL-001', 'category' => 'Tools', 'quantity' => 10, 'reorder_level' => 5, 'unit_price' => 89.99, 'supplier' => 'ProTools Inc.'],
            ['name' => 'Claw Hammer 16oz', 'sku' => 'TL-002', 'category' => 'Tools', 'quantity' => 3, 'reorder_level' => 8, 'unit_price' => 14.99, 'supplier' => 'ProTools Inc.'],
            ['name' => 'Tape Measure 25ft', 'sku' => 'TL-003', 'category' => 'Tools', 'quantity' => 25, 'reorder_level' => 10, 'unit_price' => 9.50, 'supplier' => 'ProTools Inc.'],
            ['name' => 'Electrical Wire 12AWG (100ft)', 'sku' => 'EL-001', 'category' => 'Electrical', 'quantity' => 18, 'reorder_level' => 10, 'unit_price' => 45.00, 'supplier' => 'CircuitSupply'],
            ['name' => 'Outlet Switch Plate', 'sku' => 'EL-002', 'category' => 'Electrical', 'quantity' => 0, 'reorder_level' => 15, 'unit_price' => 3.25, 'supplier' => 'CircuitSupply'],
            ['name' => 'Circuit Breaker 20A', 'sku' => 'EL-003', 'category' => 'Electrical', 'quantity' => 14, 'reorder_level' => 8, 'unit_price' => 12.99, 'supplier' => 'CircuitSupply'],
            ['name' => 'PVC Pipe 1in (10ft)', 'sku' => 'PLB-001', 'category' => 'Plumbing', 'quantity' => 40, 'reorder_level' => 15, 'unit_price' => 7.99, 'supplier' => 'AquaFlow Supply'],
            ['name' => 'Pipe Fittings Set', 'sku' => 'PLB-002', 'category' => 'Plumbing', 'quantity' => 6, 'reorder_level' => 10, 'unit_price' => 18.50, 'supplier' => 'AquaFlow Supply'],
            ['name' => 'Interior Latex Paint (1 Gallon)', 'sku' => 'PT-001', 'category' => 'Paint', 'quantity' => 22, 'reorder_level' => 10, 'unit_price' => 32.99, 'supplier' => 'ColorMax Paints'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}