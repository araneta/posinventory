<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $accounts = Account::search($search)->latest()->paginate($perPage);
        return view('account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'opening_balance' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $account = new Account();
        $account->name = $request->name;
        $account->account_number = $request->account_number;
        $account->opening_balance = $request->opening_balance;
        $account->save();

        return redirect()->back()->with('success', __('Account created successfully'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'opening_balance' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $account->name = $request->name;
        $account->account_number = $request->account_number;
        $account->opening_balance = $request->opening_balance;
        $account->save();

        return redirect()->back()->with('success', __('Account updated successfully'));
    }
}
