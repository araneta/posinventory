<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'Cash', 'account_number' => '1234567890', 'opening_balance' => 10000],
            ['name' => 'Bank', 'account_number' => '1234567891', 'opening_balance' => 10000],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::create($account);
        }
    }
}
