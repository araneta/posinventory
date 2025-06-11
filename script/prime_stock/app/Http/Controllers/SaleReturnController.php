<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\WareHouse;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleReturnController extends Controller
{
    use Transaction;

    /**
     * Sale Return List
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $warehouse = request()->input('warehouse', null);
        $customer = request()->input('customer', null);
        $status = request()->input('status', null);
        $date = request()->input('date', null);
        $sales = Sale::with('customer', 'warehouse', 'returns')
            ->warehouse($warehouse)
            ->customer($customer)
            ->returnStatus($status)
            ->returnDate($date)
            ->search($search)
            ->where('return_product', '!=', 0)
            ->latest()
            ->paginate($perPage);
        $warehouses = WareHouse::all();
        $customers = Customer::all();
        $status = ['paid' => __('Paid'), 'due' => __('Due')];
        $resetUrl = route('sale-return.index');
        return view('sale.return_list', compact('sales', 'warehouses', 'customers', 'status', 'resetUrl'));
    }

    /**
     * Sale Return Edit
     * @param $sale_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($sale_id)
    {
        $sale = Sale::with('customer', 'warehouse', 'returns')->findOrFail($sale_id);
        $accounts = Account::latest()->get();
        return view('sale.return-edit', compact('sale', 'accounts'));
    }

    /**
     * Sale Return Update
     * @param Request $request
     * @param $sale_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $sale_id)
    {

        $validator = Validator::make($request->all(), [
            'return_amount' => 'required',
            'return_discount' => 'required',
            'payable_amount' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill the required fields');
        }

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($sale_id);
            $sale->return_note = $request->return_note;
            $sale->return_amount = $request->return_amount;
            $sale->return_discount = $request->return_discount;
            $sale->payable_amount = $request->payable_amount;
            $sale->paying_amount = $request->paid_amount;
            $sale->return_date = $request->date;
            $sale->paying_date = $request->date;
            $sale->paying_status = $request->due_amount == 0 ? 'paid' : 'due';
            $totalProduct = count($request->product_id);
            if (isset($request->product_id) && is_array($request->product_id)) {
                foreach ($request->product_id as $key => $value) {
                    $sale->returns()->detach($value);
                    $sale->returns()->attach(
                        $value,
                        [
                            'quantity' => $request->quantity[$key],
                            'price' => $request->price[$key],
                            'total_price' => $request->total[$key],
                            'variation_id' => $request->variation_id[$key],
                            'variation_value' => $request->variation_value[$key],
                        ]
                    );
                }
            }
            $sale->return_product = $totalProduct;
            $sale->save();

            $this->updateTransaction([
                'account_id' => $request->account,
                'amount' => $request->paid_amount,
                'date' => $request->date,
                'type_id' => $sale->id,
                'type' => 'sale_return',
                'note' => 'Sale return for sale no ' . $sale->invoice_no,
                'id' => $sale->returnTransaction->id,
            ]);

            DB::commit();
            return redirect()->route('sale-return.index')->with('success', __('Sale return has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }
    }

    /**
     * Sale Return Show
     * @param $sale_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($sale_id)
    {
        $sale = Sale::with('customer', 'warehouse', 'returns')->findOrFail($sale_id);
        return view('sale.return-invoice', compact('sale'));
    }
}
