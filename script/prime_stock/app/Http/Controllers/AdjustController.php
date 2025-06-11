<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdjustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $adjustments = Adjustment::with('warehouse', 'products')->search($search)->latest()->paginate($perPage);
        return view('adjustment.index', compact('adjustments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = WareHouse::all();
        return view('adjustment.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $adjustment = Adjustment::create([
                'warehouse_id' => $request->warehouse,
                'date' => $request->date,
                'note' => $request->note,
                'total_products' => count($request->product_id),
            ]);

            foreach($request->product_id as $key => $product_id){
                $product = Product::find($product_id);


                if(!$product){
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Product not found');
                }
                
                if($product->product_type == 'variation'){
                    $variation_id = $request->variation_id[$key];
                    $variation_value = $request->variation_value[$key];
                }else{
                    $variation_id = null;
                    $variation_value = null;
                }

                $adjustment->products()->create([
                    'product_id' => $product_id,
                    'quantity' => $request->qty[$key],
                    'type' => $request->type[$key],
                    'variation_id' => $variation_id,
                    'variation_value' => $variation_value,
                ]);
                if($request->type[$key] === 'add'){
                    $product->increment('quantity', $request->qty[$key]);
                }else{
                    $product->decrement('quantity', $request->qty[$key]);
                }
            }
            DB::commit();
            return redirect()->route('adjustment.index')->with('success', __('Adjustment created successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adjustment $adjustment)
    {
        $warehouses = WareHouse::all();
        return view('adjustment.edit', compact('adjustment', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adjustment $adjustment)
    {
        $validator = Validator::make($request->all(), [
            'warehouse' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $adjustment->update([
                'warehouse_id' => $request->warehouse,
                'date' => $request->date,
                'note' => $request->note,
                'total_products' => count($request->product_id),
            ]);

            foreach($request->product_id as $key => $product_id){
                $product = Product::find($product_id);
                if(!$product){
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Product not found');
                }
                
                $productQty = $adjustment->products()->where('product_id', $product_id)->first()->quantity ?? 0;
                if($request->type[$key] === 'add'){
                    $product->increment('quantity', $request->qty[$key] - $productQty);
                }else{
                    $product->decrement('quantity', $productQty - $request->qty[$key]);
                }
                if($product->product_type == 'variation'){
                    $variation_id = $request->variation_id[$key];
                    $variation_value = $request->variation_value[$key];
                }else{
                    $variation_id = null;
                    $variation_value = null;
                }
                $adjustment->products()->update([
                    'product_id' => $product_id,
                    'quantity' => $request->qty[$key],
                    'type' => $request->type[$key],
                    'variation_id' => $variation_id,
                    'variation_value' => $variation_value,
                ]);
            }
            DB::commit();
            return redirect()->route('adjustment.index')->with('success', __('Adjustment updated successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }
}
