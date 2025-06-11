<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Deposit;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    use Transaction;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $deposits = Deposit::latest()->search($search)->paginate($perPage);
        $accounts = Account::all();
        return view('deposit.index', compact('deposits', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try{
            $deposit = Deposit::create([
                'account_id' => $request->account,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
            ]);
            $this->createTransaction([
                'account_id' => $request->account,
                'amount' => $request->amount,
                'type' => 'deposit',
                'date' => $request->date,
                'note' => $request->note,
                'type_id' => $deposit->id,
            ]);
            DB::commit();
            return redirect()->back()->with('success', __('Deposit created successfully'));
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Deposit could not be created'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposit $deposit)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try{
            $deposit->update([
                'account_id' => $request->account,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
            ]);
            $this->updateTransaction([
                'id' => $deposit->transaction->id,
                'account_id' => $request->account,
                'amount' => $request->amount,
                'type' => 'deposit',
                'date' => $request->date,
                'note' => $request->note,
                'type_id' => $deposit->id,
            ]);
            DB::commit();
            return redirect()->back()->with('success', __('Deposit updated successfully'));
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Deposit could not be updated'));
        }
    }
}
