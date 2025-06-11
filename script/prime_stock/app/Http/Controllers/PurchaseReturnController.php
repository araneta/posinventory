<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ProductReturn;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\WareHouse;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnController extends Controller
{
    use Transaction;

    /**
     * Purchase Return List
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $warehouse = request()->input('warehouse', null);
        $supplier = request()->input('supplier', null);
        $status = request()->input('status', null);
        $date = request()->input('date', null);
        $purchases = Purchase::with(['supplier', 'warehouse', 'products'])
            ->warehouse($warehouse)
            ->supplier($supplier)
            ->returnStatus($status)
            ->returnDate($date)
            ->search($search)
            ->where('return_product', '!=', 0)
            ->latest()
            ->paginate($perPage);
        $warehouses = WareHouse::all();
        $suppliers = Supplier::all();
        $status = ['received' => 'Received', 'due' => 'Due'];
        $resetUrl = route('purchase-return.index');
        return view('purchase.return_list', compact('purchases', 'warehouses', 'suppliers', 'status', 'resetUrl'));
    }

    /**
     * Purchase Return Edit
     * @param $purchase_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($purchase_id)
    {
        $purchase = Purchase::with(['supplier', 'warehouse', 'products', 'returns'])->findOrFail($purchase_id);
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $accounts = Account::latest()->get();
        return view('purchase.return-edit', compact('purchase', 'suppliers', 'warehouses', 'accounts'));
    }

    /**
     * Purchase Return Update
     * @param Request $request
     * @param $purchase_id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, $purchase_id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required',
            'supplier' => 'required',
            'date' => 'required',
            'warehouse' => 'required',
            'return_amount' => 'required',
            'return_discount' => 'required',
            'receivable_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $purchase = Purchase::findOrFail($purchase_id);
        DB::beginTransaction();
        try {
            $purchase->update([
                'supplier_id' => $input['supplier'],
                'return_date' => $input['date'],
                'warehouse_id' => $input['warehouse'],
                'return_amount' => $input['return_amount'],
                'return_discount' => $input['return_discount'],
                'receivable_amount' => $input['receivable_amount'],
                'received_amount' => $input['received_amount'],
                'return_status' => $input['due_amount'] == 0 ? 'received' : 'due',
            ]);
            if (isset($input['product_id']) && is_array($input['product_id'])) {
                foreach ($input['product_id'] as $key => $product_id) {
                    $purchaseReturn = ProductReturn::with(['product'])->where('purchase_id', $purchase_id)->where('id', $product_id)->first();
                    $product = $purchaseReturn->product;
                    $purchase = $purchaseReturn->purchase;
                    if($request->has('variation_id')){
                        $variation_id = $input['variation_id'][$key];
                        $productVariation = $product->variants->where('pivot.variation_id', $input['variation_id'][$key])->where('pivot.value', $input['variation_value'][$key])->first();
                        $stockQuantity = $productVariation->pivot->quantity;
                        $purchaseQuantity = $purchase->products->where('pivot.variation_id', $variation_id)->where('pivot.variation_value', $input['variation_value'][$key])->first()->pivot->quantity;
                    }else{
                        $variation_id = null;
                        $stockQuantity = $product->quantity;
                        $purchaseQuantity = $purchase->products->first()->pivot->quantity;
                    }
                    if($request->has('variation_value')){
                        $variation_value = $input['variation_value'][$key];
                    }else{
                        $variation_value = null;
                    }

                    if ($input['quantity'][$key] > $stockQuantity) {
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be low to stock quantity!'));
                    }
                    if($input['quantity'][$key] == 0){
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be greater than 0!'));
                    }
                    if($input['quantity'][$key] > $purchaseQuantity){
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be less than purchase quantity!'));
                    }
                    if (!$purchaseReturn) {
                        $purchaseReturn = new ProductReturn();
                        $purchaseReturn->purchase_id = $purchase_id;
                        $purchaseReturn->product_id = $product->id;
                        $purchaseReturn->return_price = $input['price'][$key];
                        $purchaseReturn->return_quantity = $input['quantity'][$key];
                    } else {
                        $purchaseReturn->update([
                            'purchase_id' => $purchase_id,
                            'product_id' => $product->id,
                            'return_quantity' => $input['quantity'][$key],
                            'return_price' => $input['price'][$key],
                            'total_return_price' => $input['quantity'][$key] * $input['price'][$key],
                        ]);
                    }
                }
            }

            $this->updateTransaction([
                'amount' => $input['return_amount'],
                'account_id' => $input['account'],
                'type' => 'purchase_return',
                'type_id' => $purchase_id,
                'note' => 'Purchase return for ' . $purchase->invoice_no,
                'date' => $input['date'],
                'id' => $purchase->returnTransaction->id,
            ]);

            DB::commit();
            return redirect()->route('purchase-return.index')->with('success', __('Purchase return has been updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }
    }

    /**
     * Purchase Return Details
     * @param $purchase_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($purchase_id)
    {
        $purchase = Purchase::with(['supplier', 'warehouse', 'products', 'returns'])->findOrFail($purchase_id);
        return view('purchase.return-invoice', compact('purchase'));
    }
}
