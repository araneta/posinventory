<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Variation;
use App\Models\WareHouse;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ImageUpload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', null);
        $perPage = request('per_page', settings('record_to_display', 10));
        $category = request('category', null);
        $brand = request('brand', null);
        $unit = request('unit', null);
        $type = request('type', null);
        $status = request('status', null);
        $products = Product::with(['category','brand','unit'])
                                ->category($category)
                                ->brand($brand)
                                ->unit($unit)
                                ->type($type)
                                ->status($status)
                                ->search($search)
                                ->latest()
                                ->paginate($perPage);
        $warehouses = WareHouse::get();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $units = Unit::active()->get();
        return view('product.index', compact('products', 'warehouses', 'categories', 'brands', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $units = Unit::active()->get();
        $variants = Variation::all();
        return view('product.create', compact('categories','brands', 'units', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        //dd($input);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'unit' => 'required',
            'sku' => 'required',
            'alert_quantity' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,svg,gif|max:5100000',
            'price' => 'required',
            'sale_price' => 'required',
            'type' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $data = [
            'name' => $input['name'],
            'category_id' => $input['category'],
            'brand_id' => $input['brand'],
            'unit_id' => $input['unit'],
            'sku' => $input['sku'],
            'note' => $input['note'],
            'status' => $input['status'],
            'code' => $input['code']
        ];
        $attachData = [];
        if($input['type'] === 'variation'){
            $data = array_merge($data, ['product_type' => 'variation']);
            foreach ($input['variation_value'] as $key => $value) {
                $attachData[$key] = [
                    'variation_id' => $input['variation_id'],
                    'price' => $input['price'][$key],
                    'sale_price' => $input['sale_price'][$key],
                    'alert_quantity' => $input['alert_quantity'][$key],
                    'quantity' => 0,
                    'value' => $value,
                ];
            }
        }
        if($input['type'] === 'single'){
            $data = array_merge($data, ['product_type' => 'single']);
            $data = array_merge($data, [
                'price' => $input['price'][0],
                'sale_price' => $input['sale_price'][0],
                'alert_quantity' => $input['alert_quantity'][0],
                'quantity' => 0,
            ]);
        }

        if ($request->hasFile('image')) {
            $image = $this->imageUploadTrait($input['image']);
            $data = array_merge($data, ['image' => $image]);
        }
        $product = Product::create($data);
        if($input['type'] === 'variation'){
            foreach ($attachData as $key => $value) {
                $product->variants()->attach($input['variation_id'], $value);
            }
        }
        return redirect()->route('product.index')->with('success', __('Product has been stored successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $units = Unit::active()->get();
        $variants = Variation::all();
        return view('product.edit', compact('product','categories','brands','units', 'variants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'unit' => 'required',
            'sku' => 'required',
            'alert_quantity' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,svg,gif|max:5100000',
            'price' => 'required',
            'sale_price' => 'required',
            'type' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $data = [
            'name' => $input['name'],
            'category_id' => $input['category'],
            'brand_id' => $input['brand'],
            'unit_id' => $input['unit'],
            'sku' => $input['sku'],
            'note' => $input['note'],
            'status' => $input['status'],
            'code' => $input['code']
        ];

        $attachData = [];
        if($input['type'] === 'variation'){
            $data = array_merge($data, ['product_type' => 'variation']);
            foreach ($input['variation_value'] as $key => $value) {
                $attachData[$key] = [
                    'variation_id' => $input['variation_id'],
                    'price' => $input['price'][$key],
                    'sale_price' => $input['sale_price'][$key],
                    'alert_quantity' => $input['alert_quantity'][$key],
                    'quantity' => 0,
                    'value' => $value,
                ];
            }
        }
        if($input['type'] === 'single'){
            $data = array_merge($data, ['product_type' => 'single']);
            $data = array_merge($data, [
                'price' => $input['price'][0],
                'sale_price' => $input['sale_price'][0],
                'alert_quantity' => $input['alert_quantity'][0],
                'quantity' => 0,
            ]);
        }

        if ($request->hasFile('image')) {
            $old = $product->image != 'default.png' ? $product->image : null;
            $image = $this->imageUploadTrait($input['image'], $old);
            $data = array_merge($data, ['image' => $image]);
        }
        $product->update($data);
        if($input['type'] === 'variation'){
            $product->variants()->detach();
            foreach ($attachData as $key => $value) {
                $product->variants()->attach($input['variation_id'], $value);
            }
        }
        return redirect()->route('product.index')->with('success', __('Product has been updated successfully!'));
    }

    /**
     * Variation Details
     */

    public function variant()
    {
        $productId = request()->get('productId');
        $product = Product::find($productId);
        return view('product.__variant_table', compact('product'));
    }

    /**
     * Stock Details
     */
    public function stock()
    {
        $productId = request()->get('productId');
        $product = Product::find($productId);
        $warehouses = WareHouse::get();

        return view('product.__stock_table', compact('product', 'warehouses'));
    }

    public function barcodeSearch(Request $request)
    {
        $products = Product::where('code', 'like', '%'.$request->barcode.'%')
                            ->orWhere('sku', 'like', '%'.$request->barcode.'%')
                            ->get();
        return view('product.__barcode_search', compact('products'))->render();
    }
}
