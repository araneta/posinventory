<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Supplier 1', 'email' => 'supplier1@example.com', 'phone' => '1234567890', 'address' => '123 Main St, City', 'company' => 'Company 1'],
            ['name' => 'Supplier 2', 'email' => 'supplier2@example.com', 'phone' => '1234567891', 'address' => '456 Side St, Town', 'company' => 'Company 2'],
        ];

        $customers = [
            ['name' => 'Customer 1', 'email' => 'customer1@example.com', 'phone' => '1234567891', 'address' => '123 Main St, City'],
            ['name' => 'Customer 2', 'email' => 'customer2@example.com', 'phone' => '1234567892', 'address' => '456 Side St, Town'],
        ];

        foreach ($suppliers as $supplier) {
            \App\Models\Supplier::create($supplier);
        }

        foreach ($customers as $customer) {
            \App\Models\Customer::create($customer);
        }
    }
}
