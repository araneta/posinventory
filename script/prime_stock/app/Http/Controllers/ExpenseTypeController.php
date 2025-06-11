<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $types = ExpenseType::latest()->search($search)->paginate($perPage);
        return view('expense.type', compact('types'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        ExpenseType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('expense-type.index')->with('success', __('Expense Type has been stored successfully!'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $expenseType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('expense-type.index')->with('success', __('Expense Type has been updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType)
    {
        $expenseType->delete();
        return redirect()->back()->with('success', __('Expense Type has been deleted successfully!'));
    }
}
