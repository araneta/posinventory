<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $types = ExpenseType::all();
        $expenses = Expense::latest()->search($search)->paginate($perPage);
        return view('expense.index', compact('types', 'expenses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'amount' => 'required',
            'date' => 'required|max:200'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $data = [
            'type_id' => $input['type'],
            'amount' => $input['amount'],
            'date' => $input['date'],
            'note' => $input['note'],
        ];
        Expense::create($data);

        return redirect()->route('expense.index')->with('success', __('Expense has been stored successfully!'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'amount' => 'required',
            'date' => 'required|max:200'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $data = [
            'type_id' => $input['type'],
            'amount' => $input['amount'],
            'date' => $input['date'],
            'note' => $input['note'],
        ];
        $expense->update($data);

        return redirect()->route('expense.index')->with('success', __('Expense has been updated successfully!'));
    }
}
