<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $transfers = Transfer::with(['fromWarehouse', 'toWarehouse', 'products'])->search($search)->latest()->paginate($perPage);
        return view('transfer.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = WareHouse::all();
        return view('transfer.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_warehouse' => 'required',
            'to_warehouse' => 'required',
            'date' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $input = $request->all();
        DB::beginTransaction();
        try{
            $transfer = Transfer::create([
                'from_warehouse_id' => $input['from_warehouse'],
                'to_warehouse_id' => $input['to_warehouse'],
                'date' => $input['date'],
                'note' => $input['note'],
                'total_product' => count($input['product_id']),
            ]);

            foreach ($input['product_id'] as $key => $product) {
                $products = Product::find($product);
                if($products->product_type == 'variation'){
                    $variation_id = $request->variation_id[$key];
                    $variation_value = $request->variation_value[$key];
                }else{
                    $variation_id = null;
                    $variation_value = null;
                }
                $transfer->products()->create([
                    'product_id' => $product,
                    'quantity' => $request->qty[$key],
                    'variation_id' => $variation_id,
                    'variation_value' => $variation_value,
                ]);
            }
            DB::commit();
            return redirect()->route('transfer.index')->with('success', __('Transfer has been stored successfully!'));
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong! Please try again.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transfer $transfer)
    {
        $warehouses = WareHouse::all();
        return view('transfer.edit', compact('transfer', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfer $transfer)
    {
        $validator = Validator::make($request->all(), [
            'from_warehouse' => 'required',
            'to_warehouse' => 'required',
            'date' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $input = $request->all();
        DB::beginTransaction();
        try{
            $transfer->update([
                'from_warehouse_id' => $input['from_warehouse'],
                'to_warehouse_id' => $input['to_warehouse'],
                'date' => $input['date'],
                'note' => $input['note'],
                'total_product' => count($input['product_id']),
            ]);

            $transfer->products()->delete();
            foreach ($input['product_id'] as $key => $product) {
                $products = Product::find($product);
                if($products->product_type == 'variation'){
                    $variation_id = $request->variation_id[$key];
                    $variation_value = $request->variation_value[$key];
                }else{
                    $variation_id = null;
                    $variation_value = null;
                }
                $transfer->products()->create([
                    'product_id' => $product,
                    'quantity' => $input['qty'][$key],
                    'variation_id' => $variation_id,
                    'variation_value' => $variation_value,
                ]);
            }
            DB::commit();
            return redirect()->route('transfer.index')->with('success', __('Transfer has been updated successfully!'));
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
    }
}
