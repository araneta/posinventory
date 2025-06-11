<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    use Transaction;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $suppliers = Supplier::latest()->search($search)->paginate($perPage);
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
            'email' => 'email|required|unique:suppliers',
            'phone' => 'required|unique:suppliers'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $supplier = Supplier::create($input);
        if ($request->ajax()) {
            return response()->json($supplier);
        }
        return redirect()->route('supplier.index')->with('success', __('Supplier has been stored successfully!'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
            'email' => ['required', 'email', 'unique:suppliers,email,'.$supplier->id],
            'phone' => ['required', 'unique:suppliers,phone,'.$supplier->id]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $supplier->update($input);
        return redirect()->route('supplier.index')->with('success', __('Supplier has been updated successfully!'));
    }

    /**
     * Supplier Payment
     */
    public function payment($supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);
        return view('supplier.payment', compact('supplier'));
    }

    /**
     * paymentClear
     */
    public function paymentClear(Request $request, $supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);
        //Update Supplier Paying Amount, Paying Date, Status also Purchase  Received Amount, Return Status, Return Date
        DB::beginTransaction();
        try{
            foreach ($supplier->purchases as $purchase) {
                if($purchase->status !== 'paid'){
                    $amount = $purchase->dueAmount();
                    $purchase->update([
                        'paying_amount' => $purchase->payable_amount,
                        'paying_date' => date('Y-m-d'),
                        'status' => 'paid',
                    ]);
                    $this->createTransaction([
                        'type' => 'purchase',
                        'account_id' => defaultAccount(),
                        'amount' => $amount,
                        'date' => date('Y-m-d'),
                        'note' => 'Supplier Payable Amount',
                        'type_id' => $purchase->id
                    ]);
                }
            }

            foreach ($supplier->purchases as $purchase) {
                if($purchase->return_status !== 'received'){
                    $amount = $purchase->returnDueAmount();
                    $receivable_amount = $purchase->receivable_amount;
                    $purchase->received_amount = $receivable_amount;
                    $purchase->receive_date = date('Y-m-d');
                    $purchase->return_status = 'received';
                    $purchase->save();
                    $this->createTransaction([
                        'type' => 'purchase_return',
                        'account_id' => defaultAccount(),
                        'amount' => $amount,
                        'date' => date('Y-m-d'),
                        'note' => 'Supplier Receivable Amount',
                        'type_id' => $purchase->id
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('supplier.index')->with('success', __('Supplier payment has been cleared successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }

    }
}
