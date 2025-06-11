<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Transaction Controller
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = request()->per_page ?? settings('record_to_display', 10);
        $search = request()->input('search', null);
        $account = request()->input('account', null);
        $type = request()->input('type', null);
        $date = request()->input('date', null);
        $transactions = Transaction::latest()
            ->account($account)
            ->type($type)
            ->date($date)
            ->search($search)
            ->paginate($perPage);
        $accounts = Account::all();
        $types = [
            'deposit' => 'Deposit',
            'sale' => 'Sale',
            'purchase' => 'Purchase',
            'sale_return' => 'Sale Return',
            'purchase_return' => 'Purchase Return',
            'expense' => 'Expense',
            'transfer' => 'Transfer',
            'income' => 'Income',
        ];
        return view('transaction.index', compact('transactions', 'accounts', 'types'));
    }
}
