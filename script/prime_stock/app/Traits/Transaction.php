<?php

namespace App\Traits;

trait Transaction
{
    /**
     * Create Transaction
     */
    public function createTransaction($data): void
    {
        \App\Models\Transaction::create([
            'account_id' => $data['account_id'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'notes' => $data['note'],
            'date' => $data['date'],
            'type_id' => $data['type_id'] ?? null,
        ]);
    }

    /**
     * Update Transaction
     */
    public function updateTransaction($data): void
    {
        \App\Models\Transaction::where('id', $data['id'])->update([
            'account_id' => $data['account_id'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'notes' => $data['note'],
            'date' => $data['date'],
            'type_id' => $data['type_id'] ?? null,
        ]);
    }

}
