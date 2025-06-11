<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard' => ['dashboard'],
            'category' => [
                'create-category',
                'list-category',
                'edit-category',
                'delete-category',
            ],
            'brand' => [
                'create-brand',
                'list-brand',
                'edit-brand',
                'delete-brand',
            ],
            'unit' => [
                'create-unit',
                'list-unit',
                'edit-unit',
                'delete-unit',
            ],
            'variation' => [
                'create-variation',
                'list-variation',
                'edit-variation',
                'delete-variation',
            ],
            'product' => [
                'create-product',
                'list-product',
                'edit-product',
                'print-product-labels',
            ],
            'purchase' => [
                'create-purchase',
                'list-purchase',
                'edit-purchase',
                'invoice-purchase',
                'give-supplier-payment',
            ],
            'purchase-return' => [
                'create-purchase-return',
                'list-purchase-return',
                'edit-purchase-return',
                'invoice-purchase-return',
                'receive-supplier-payment',
            ],
            'sale' => [
                'create-sale',
                'list-sale',
                'edit-sale',
                'invoice-sale',
                'receive-customer-payment',
            ],
            'sale-return' => [
                'create-sale-return',
                'list-sale-return',
                'edit-sale-return',
                'invoice-sale-return',
                'give-customer-payment',
            ],
            'warehouse' => [
                'create-warehouse',
                'list-warehouse',
                'edit-warehouse',
            ],
            'supplier' => [
                'create-supplier',
                'list-supplier',
                'edit-supplier',
                'payment-supplier',
            ],
            'customer' => [
                'create-customer',
                'list-customer',
                'edit-customer',
                'payment-customer',
                'notify-customer'
            ],
            'staff' => [
                'create-staff',
                'list-staff',
                'edit-staff',
                'ban-staff',
                'unban-staff',
            ],
            'role' => [
                'create-role',
                'list-role',
                'edit-role',
                'delete-role',
            ],
            'expense-type' => [
                'create-expense-type',
                'list-expense-type',
                'edit-expense-type',
                'delete-expense-type',
            ],
            'expense' => [
                'create-expense',
                'list-expense',
                'edit-expense',
                'delete-expense',
            ],
            'adjustment' => [
                'create-adjustment',
                'list-adjustment',
                'edit-adjustment',
            ],
            'transfer' => [
                'create-transfer',
                'list-transfer',
                'edit-transfer',
            ],
            'account' => [
                'create-account',
                'list-account',
                'edit-account',
            ],       
            'deposit' => [
                'create-deposit',
                'list-deposit',
                'edit-deposit',
            ],
            'transaction' => [
                'list-transaction',
                'filter-transaction',
            ],
            'report' => [
                'profit-loss-report',
                'stock-report',
                'supplier-payment-report',
                'customer-payment-report',
                'purchase-report',
                'sales-report',
                'product-report',
                'payment-sale-report',
                'payment-purchase-report',
                'payment-sale-return-report',
                'payment-purchase-return-report',
            ],
            'language' => [
                'list-language',
                'edit-language',
                'create-language',
                'translate-language',
                'sync-language',
            ],
            'setting' => [
                'system-setting',
                'mail-setting',
                'email-template',
            ],
        ];
        
        foreach ($permissions as $key => $permission) {
            foreach ($permission as $value){
                Permission::create([
                    'group_name' => $key,
                    'name' => $value
                ]);
            }
        }
    }
}
