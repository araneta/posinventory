<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\WareHouse;
use App\Traits\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
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
        $customer = request()->input('customer', null);
        $status = request()->input('status', null);
        $date = request()->input('date', null);
        $sales = Sale::with('customer', 'warehouse')
            ->warehouse($warehouse)
            ->customer($customer)
            ->status($status)
            ->date($date)
            ->search($search)
            ->latest()
            ->paginate($perPage);
        $warehouses = WareHouse::all();
        $customers = Customer::all();
        $status = ['received' => __('Received'), 'due' => __('Due')];
        $resetUrl = route('sale.index');
        return view('sale.index', compact('sales', 'warehouses', 'customers', 'status', 'resetUrl'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $sale = Sale::latest()->first();
        $accounts = Account::latest()->get();
        if ($sale) {
            $invoice_id = $sale->invoice_no;
            $invoice_id = (int) substr($invoice_id, 2);
            ++$invoice_id;
            $invoice_id = str_pad($invoice_id, 6, "0", STR_PAD_LEFT);
            $invoice_id = 's-' . $invoice_id;
        } else {
            $invoice_id = 's-000001';
        }

        return view('sale.create', compact('customers', 'warehouses', 'invoice_id', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required',
            'customer' => 'required',
            'date' => 'required',
            'warehouse' => 'required',
            'total_price' => 'required',
            'discount' => 'required',
            'receivable_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'invoice_no' => $input['invoice_no'],
                'customer_id' => $input['customer'],
                'date' => $input['date'],
                'warehouse_id' => $input['warehouse'],
                'total_price' => $input['total_price'],
                'discount' => $input['discount'],
                'tax_amount' => $input['tax'],
                'receivable_amount' => $input['receivable_amount'],
                'received_amount' => $input['received_amount'],
                'status' => $input['due_amount'] == 0 ? 'received' : 'due',
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $returnData = $this->productPivotSave($input, $sale);
                if ($returnData == 0) {
                    $sale->delete();
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Product quantity is not available!'));
                }
            }

            $this->createTransaction([
                'account_id' => $input['account'],
                'amount' => $input['received_amount'],
                'type' => 'sale',
                'type_id' => $sale->id,
                'note' => 'Sale for ' . $sale->invoice_no,
                'date' => $input['date'],
            ]);

            DB::commit();
            return redirect()->route('sale.show', $sale->id)->with('success', __('Sale has been stored successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sale.invoice', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        $accounts = Account::latest()->get();
        return view('sale.edit', compact('customers', 'warehouses', 'sale', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required',
            'customer' => 'required',
            'date' => 'required',
            'warehouse' => 'required',
            'total_price' => 'required',
            'discount' => 'required',
            'receivable_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $oldSale = Sale::find($sale->id);
            $sale->update([
                'invoice_no' => $input['invoice_no'],
                'customer_id' => $input['customer'],
                'date' => $input['date'],
                'warehouse_id' => $input['warehouse'],
                'total_price' => $input['total_price'],
                'discount' => $input['discount'],
                'tax_amount' => $input['tax'],
                'receivable_amount' => $input['receivable_amount'],
                'received_amount' => $input['received_amount'],
                'status' => $input['due_amount'] == 0 ? 'received' : 'due',
            ]);

            if (isset($input['product_id']) && is_array($input['product_id'])) {
                $returnData = $this->productPivotSave($input, $sale, 'edit');
                if ($returnData == 0) {
                    $sale->update([
                        'invoice_no' => $oldSale->invoice_no,
                        'customer_id' => $oldSale->customer_id,
                        'date' => $oldSale->date,
                        'warehouse_id' => $oldSale->warehouse_id,
                        'total_price' => $oldSale->total_price,
                        'discount' => $oldSale->discount,
                        'receivable_amount' => $oldSale->receivable_amount,
                        'status' => $oldSale->status,
                    ]);
                    DB::rollBack();
                    return redirect()->back()->with('error', __('Product quantity is not available!'));
                }
            }

            $this->updateTransaction([
                'account_id' => $input['account'],
                'amount' => $input['received_amount'],
                'type' => 'sale',
                'type_id' => $sale->id,
                'note' => 'Sale for ' . $sale->invoice_no,
                'date' => $input['date'],
                'id' => $sale->transaction->id,
            ]);
            DB::commit();
            return redirect()->route('sale.show', $sale->id)->with('success', __('Sale has been updated successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }

    }

    /**
     * @throws \Exception
     */
    public function invoice($sale_id, $source = 'sale')
    {
        $invoice_id = random_int(100000, 999999);
        $sale = Sale::with(['customer', 'warehouse', 'products', 'returns'])->find($sale_id)->toArray();
        $sale['invoice_id'] = $invoice_id;
        $pdf = Pdf::loadView('pdf.__sale_invoice_pdf', ['data' => $sale, 'source' => $source]);
        return $pdf->download('invoice-' . $invoice_id . '.pdf');
    }

    /**
     * Received Payment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function receivePayment(Request $request)
    {
        $sale = Sale::find($request->id);
        $amount = $request->received_amount;
        $sale->received_amount += $amount;
        $sale->received_date = today();
        $sale->save();
        if ($sale->received_amount == $sale->receivable_amount) {
            $sale->status = 'received';
        }
        $sale->save();

        $this->createTransaction([
            'account_id' => defaultAccount(),
            'amount' => $amount,
            'type' => 'sale',
            'type_id' => $sale->id,
            'note' => 'Sale payment received for ' . $sale->invoice_no,
            'date' => today(),
        ]);

        return redirect()->back()->with('success', __('Payment received successfully!'));
    }

    /**
     * Give Payment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function givePayment(Request $request)
    {
        $sale = Sale::find($request->id);
        $amount = $request->payable_amount;
        $sale->paying_amount += $amount;
        $sale->paying_date = today();
        $sale->save();
        if ($sale->paying_amount == $sale->payable_amount) {
            $sale->paying_status = 'paid';
        }
        $sale->save();

        $this->createTransaction([
            'account_id' => defaultAccount(),
            'amount' => $amount,
            'type' => 'sale',
            'type_id' => $sale->id,
            'note' => 'Sale payment given for ' . $sale->invoice_no,
            'date' => today(),
        ]);

        return redirect()->back()->with('success', __('Payment given successfully!'));
    }

    /**
     * Sale Return
     * @param $sale_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function saleReturn($sale_id)
    {
        $sale = Sale::find($sale_id);
        $accounts = Account::latest()->get();
        return view('sale.return', compact('sale', 'accounts'));
    }

    /**
     * Sale Return Post
     * @param Request $request
     * @param $sale_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saleReturnPost(Request $request, $sale_id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $sale = Sale::find($sale_id);
            $sale->return_note = $input['note'];
            $sale->return_date = $input['date'];
            $sale->return_amount = $input['total_amount'];
            $sale->return_discount = $input['discount'];
            $sale->payable_amount = $input['payable_amount'];
            $sale->paying_amount = $input['paid_amount'];
            $sale->paying_status = $input['due_amount'] == 0 ? 'paid' : 'due';
            $return_product = 0;
            if (isset($input['product_id']) && is_array($input['product_id'])) {
                foreach ($input['product_id'] as $key => $value) {

                    if ($input['quantity'][$key] >= $input['sale_quantity'][$key]) {
                        DB::rollBack();
                        return redirect()->back()->with('error', __('Return quantity is not grater than sale quantity!'));
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
                    $return_product += $input['quantity'][$key];

                    if($variation_id){
                        $product = Product::find($value);
                        $product->variants()->where('variation_id', $variation_id)->where('value', $variation_value)->increment('quantity', $input['quantity'][$key]);
                    }else{
                        $product = Product::find($value);
                        $product->quantity += $input['quantity'][$key];
                        $product->save();
                    }

                    $sale->returns()->attach($value, [
                        'quantity' => $input['quantity'][$key],
                        'price' => $input['price'][$key],
                        'total_price' => $input['total'][$key],
                        'variation_id' => $variation_id,
                        'variation_value' => $variation_value,
                    ]);
                }
            }
            $sale->return_product = $return_product;
            $sale->save();

            $this->createTransaction([
                'account_id' => $input['account'],
                'amount' => $input['paid_amount'],
                'type' => 'sale_return',
                'type_id' => $sale->id,
                'note' => 'Sale return for ' . $sale->invoice_no,
                'date' => $input['date'],
            ]);

            DB::commit();
            return redirect()->route('sale.return.show', $sale->id)->with('success', __('Sale has been returned successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }

    }

    /**
     * Product Details Save Function
     * @param array $input
     * @param Sale $sale
     */
    private function productPivotSave(array $input, Sale $sale, $status = 'add')
    {
        foreach ($input['product_id'] as $key => $data) {
            $product = Product::find($data);
            if ($product->product_type === 'variation') {
                if ($status === 'add') {
                    //increase quantity product variation
                    $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->decrement('quantity', $input['quantity'][$key]);
                } else {
                    $currentQuantity = $sale->products()->find($data)->pivot->quantity;
                    $sale->products()->wherePivot('variation_id', $input['variation_id'][$key])->wherePivot('variation_value', $input['variation_value'][$key])->detach($data);
                    if ($currentQuantity > $input['quantity'][$key]) {
                        $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->decrement('quantity', $currentQuantity - $input['quantity'][$key]);
                    } else {
                        $product->variants()->where('variation_id', $input['variation_id'][$key])->where('value', $input['variation_value'][$key])->increment('quantity', $input['quantity'][$key] - $currentQuantity);
                    }
                }
                $sale->products()->attach($data, [
                    'quantity' => $input['quantity'][$key],
                    'price' => $input['price'][$key],
                    'total_price' => $input['total'][$key],
                    'variation_id' => $input['variation_id'][$key],
                    'variation_value' => $input['variation_value'][$key],
                ]);
            }else{
                if ($status === 'add') {
                    $product->quantity -= $input['quantity'][$key];
                } else {
                    $currentQuantity = $sale->products()->find($data)->pivot->quantity;
                    $sale->products()->detach($data);
                    if ($currentQuantity > $input['quantity'][$key]) {
                        $product->quantity += $currentQuantity - $input['quantity'][$key];
                    } else {
                        $product->quantity -= $input['quantity'][$key] - $currentQuantity;
                    }
                    $product->save();
                }
                $sale->products()->attach($data, [
                    'quantity' => $input['quantity'][$key],
                    'price' => $input['price'][$key],
                    'total_price' => $input['total'][$key],
                ]);
            }
        }
        return 1;
    }
}
