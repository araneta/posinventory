<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\WareHouse;
use App\Traits\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    use Transaction;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $warehouse = request()->input('warehouse', null);
        $supplier = request()->input('supplier', null);
        $status = request()->input('status', null);
        $date = request()->input('date', null);
        $purchases = Purchase::with(['supplier', 'warehouse'])
            ->warehouse($warehouse)
            ->supplier($supplier)
            ->status($status)
            ->date($date)
            ->search($search)
            ->latest()
            ->paginate($perPage);

        $warehouses = WareHouse::all();
        $suppliers = Supplier::all();
        $status = ['paid' => 'Paid', 'due' => 'Due'];
        $resetUrl = route('purchase.index');
        return view('purchase.index', compact('purchases', 'warehouses', 'suppliers', 'status', 'resetUrl'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $purchase = Purchase::latest()->first();
        $accounts = Account::latest()->get();
        if ($purchase) {
            $invoice_id = $purchase->invoice_no;
            $invoice_id = (int)substr($invoice_id, 2);
            ++$invoice_id;
            $invoice_id = str_pad($invoice_id, 6, "0", STR_PAD_LEFT);
            $invoice_id = 'p-' . $invoice_id;
        } else {
            $invoice_id = 'p-000001';

        }
        return view('purchase.create', compact('suppliers', 'warehouses', 'invoice_id', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required',
            'supplier' => 'required',
            'date' => 'required',
            'warehouse' => 'required',
            'total_price' => 'required',
            'discount' => 'required',
            'payable_amount' => 'required',
            'product_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'invoice_no' => $input['invoice_no'],
                'supplier_id' => $input['supplier'],
                'date' => $input['date'],
                'warehouse_id' => $input['warehouse'],
                'total_price' => $input['total_price'],
                'tax_amount' => $input['tax'],
                'discount' => $input['discount'],
                'payable_amount' => $input['payable_amount'],
                'paying_amount' => $input['paid_amount'],
                'status' => $input['due_amount'] == 0 ? 'paid' : 'due',
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $this->productPivotSave($input, $purchase);
            }

            $this->createTransaction([
                'account_id' => $input['account'],
                'amount' => $input['paid_amount'],
                'type' => 'purchase',
                'note' => 'Purchase for ' . $purchase->invoice_no,
                'date' => $input['date'],
                'type_id' => $purchase->id,
            ]);
            DB::commit();
            return redirect()->route('purchase.show', $purchase->id)->with('success', __('Purchase has been stored successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return view('purchase.invoice', compact('purchase'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $accounts = Account::latest()->get();
        return view('purchase.edit', compact('suppliers', 'warehouses', 'purchase', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required',
            'supplier' => 'required',
            'date' => 'required',
            'warehouse' => 'required',
            'total_price' => 'required',
            'discount' => 'required',
            'payable_amount' => 'required',
            'product_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $purchase->update([
                'supplier_id' => $input['supplier'],
                'date' => $input['date'],
                'warehouse_id' => $input['warehouse'],
                'total_price' => $input['total_price'],
                'tax_amount' => $input['tax'],
                'discount' => $input['discount'],
                'payable_amount' => $input['payable_amount'],
                'paying_amount' => $input['paid_amount'],
                'status' => $input['due_amount'] == 0 ? 'paid' : 'due',
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $returnData = $this->productPivotSave($input, $purchase, 'edit');
            }

            $this->updateTransaction([
                'id' => $purchase->transaction->id,
                'account_id' => $input['account'],
                'amount' => $input['paid_amount'],
                'type' => 'purchase',
                'note' => 'Purchase for ' . $purchase->invoice_no,
                'date' => $input['date'],
                'type_id' => $purchase->id,
            ]);

            DB::commit();
            return redirect()->route('purchase.show', $purchase->id)->with('success', __('Purchase has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }
    }

    /**
     * Product Search With name and Code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product(Request $request)
    {
        $query = $request->input('search');
        $wareHouse = $request->input('warehouse') ?? null;
        $products = Product::select("name as value", "id", "price", "quantity", "sale_price", "product_type", "code", "image", "unit_id")
            ->where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->with(['variants', 'unit'])
            ->get();
        if ($wareHouse) {
            $products = $products->map(function ($product) use ($wareHouse) {
                if($product->product_type == 'single'){
                    $product->quantity_in_wirehouse = stock_quantity($product->id, $wareHouse);
                }
                if($product->product_type == 'variation'){
                    $product->variants->map(function($variation) use ($product, $wareHouse){
                        $variation->pivot->current_stock = variant_stock_quantity($product->id, $wareHouse, $variation->pivot->variation_id, $variation->pivot->value);
                    });
                }
                return $product;
            });
        }
        return response()->json($products);
    }

    /**
     * @throws \Exception
     */
    public function invoice($purchase_id, $source = 'purchase')
    {
        $invoice_id = random_int(100000, 999999);
        $purchase = Purchase::with(['supplier', 'warehouse', 'returns', 'products', 'returns.product'])->find($purchase_id)->toArray();

        $purchase['invoice_id'] = $invoice_id;
        $pdf = Pdf::loadView('pdf.__invoice_pdf', ['data' => $purchase, 'source' => $source]);
        return $pdf->download('invoice-' . $invoice_id . '.pdf');
    }

    /**
     * Give Payment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function givePayment(Request $request)
    {
        $purchase = Purchase::find($request->id);
        $amount = $request->paying_amount;
        $purchase->paying_amount += $request->paying_amount;
        $purchase->paying_date = today();
        $purchase->save();
        if ($purchase->paying_amount == $purchase->payable_amount) {
            $purchase->status = 'paid';
        }
        $purchase->save();
        $this->createTransaction([
            'type' => 'purchase',
            'account_id' => defaultAccount(),
            'amount' => $amount,
            'date' => date('Y-m-d'),
            'note' => 'Supplier Payable Amount',
            'type_id' => $purchase->id
        ]);
        return redirect()->route('purchase.index')->with('success', __('Payment given successfully!'));
    }

    /**
     * Received Payment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function receivePayment(Request $request)
    {
        $purchase = Purchase::find($request->id);
        $amount = $request->received_amount;
        $purchase->received_amount += $request->received_amount;
        $purchase->receive_date = today();
        $purchase->save();
        if ($purchase->received_amount == $purchase->receivable_amount) {
            $purchase->return_status = 'received';
        }
        $purchase->save();
        $this->createTransaction([
            'type' => 'purchase_return',
            'account_id' => defaultAccount(),
            'amount' => $amount,
            'date' => date('Y-m-d'),
            'note' => 'Supplier Receivable Amount',
            'type_id' => $purchase->id
        ]);
        return redirect()->back()->with('success', __('Payment received successfully!'));
    }

    /**
     * Purchase Return
     * @param $purchase_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function purchaseReturn($purchase_id)
    {
        $purchase = Purchase::find($purchase_id);
        $accounts = Account::latest()->get();
        return view('purchase.return', compact('purchase', 'accounts'));
    }

    /**
     * Purchase Return Store
     * @param Request $request
     * @param $purchase_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchaseReturnPost(Request $request, $purchase_id)
    {
        $purchase = Purchase::find($purchase_id);
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();

        try{
            $purchase->update([
                'return_note' => $input['note'],
                'return_discount' => $input['discount'] ?? 0,
                'return_amount' => $input['total_amount'] ?? 0,
                'receivable_amount' => $input['receivable_amount'] ?? 0,
                'received_amount' => $input['received_amount'] ?? 0,
                'return_status' => $input['due_amount'] == 0 ? 'received' : 'due',
                'receive_date' => today(),
                'return_date' => today(),
            ]);
            $totalProduct = 0;
            if (isset($input['product_id']) && is_array($input['product_id'])) {
                foreach ($input['product_id'] as $key => $data) {
                    if ($input['quantity'][$key] > $input['product_quantity'][$key]) {
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be low to stock quantity!'));
                    }
                    if($input['quantity'][$key] == 0){
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be greater than 0!'));
                    }
                    if($input['quantity'][$key] > $input['purchase_quantity'][$key]){
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity must be less than purchase quantity!'));
                    }

                    if($request->has('variation_id')){
                        $variation_id = $input['variation_id'][$key];
                    }else{
                        $variation_id = null;
                    }

                    if($request->has('variation_value')){
                        $variation_value = $input['variation_value'][$key];
                    }else{
                        $variation_value = null;
                    }

                    $totalProduct += $input['quantity'][$key];
                    ProductReturn::create([
                        'product_id' => $data,
                        'purchase_id' => $purchase->id,
                        'type' => 'purchase',
                        'return_quantity' => $input['quantity'][$key],
                        'return_price' => $input['price'][$key],
                        'total_return_price' => $input['total'][$key],
                        'variation_id' => $variation_id,
                        'variation_value' => $variation_value,
                    ]);

                    if($variation_id){
                        $product = Product::find($data);
                        $product->variants()->where('variation_id', $variation_id)->where('value', $variation_value)->decrement('quantity', $input['quantity'][$key]);
                    }else{
                        $product = Product::find($data);
                        $product->quantity -= $input['quantity'][$key];
                        $product->save();
                    }
                }
            }

            if ($totalProduct != 0) {
                $purchase->update([
                    'return_product' => $totalProduct
                ]);
            }

            $this->createTransaction([
                'account_id' => $input['account'],
                'amount' => $input['received_amount'],
                'type' => 'purchase_return',
                'note' => 'Purchase Return for ' . $purchase->invoice_no,
                'date' => $input['date'],
                'type_id' => $purchase->id,
            ]);

            DB::commit();
            return redirect()->route('purchase-return.index')->with('success', __('Product return successfully!'));
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }

    }

    /**
     * @param array $input
     * @param Purchase $purchase
     * @return int
     */
    private function productPivotSave(array $input, Purchase $purchase, $status = 'add'): void
    {
        foreach ($input['product_id'] as $key => $data) {
            $product = Product::find($data);
            if ($product->product_type === 'variation') {

                if ($status === 'add') {
                    $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->increment('quantity', $input['quantity'][$key]);
                } else {
                    $currentQuantity = $purchase->products()->find($data)->pivot->quantity;
                    //Purchase Product detach where product id and variation id and variation value is same
                    $purchase->products()->wherePivot('variation_id', $input['variation_id'][$key])->wherePivot('variation_value', $input['variation_value'][$key])->detach($data);
                    if ($currentQuantity > $input['quantity'][$key]) {
                        $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->decrement('quantity', $currentQuantity - $input['quantity'][$key]);
                    } else {
                        $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->increment('quantity', $input['quantity'][$key] - $currentQuantity);
                    }
                }
                $purchase->products()->attach($data, [
                    'quantity' => $input['quantity'][$key],
                    'price' => $input['price'][$key],
                    'total_price' => $input['total'][$key],
                    'variation_id' => $input['variation_id'][$key],
                    'variation_value' => $input['variation_value'][$key],
                ]);
            } else {
                if ($status === 'add') {
                    $product->quantity += $input['quantity'][$key];
                } else {
                    $currentQuantity = $purchase->products()->find($data)->pivot->quantity;
                    $purchase->products()->detach($data);
                    if ($currentQuantity > $input['quantity'][$key]) {
                        $product->quantity += $currentQuantity - $input['quantity'][$key];
                    } else {
                        $product->quantity -= $input['quantity'][$key] - $currentQuantity;
                    }
                    $product->save();
                }
                $purchase->products()->attach($data, [
                    'quantity' => $input['quantity'][$key],
                    'price' => $input['price'][$key],
                    'total_price' => $input['total'][$key],
                ]);
            }
        }
    }
}
