<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $brands = Brand::with('products')->search($search)->latest()->paginate($perPage);
        return view('brand.index', compact('brands'));
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

        $brand = Brand::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        if ($request->ajax()) {
            return response()->json($brand);
        }
        return redirect()->route('brand.index')->with('success', __('Brand has been stored successfully!'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $brand->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('brand.index')->with('success', __('Brand has been updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->back()->with('success', __('Brand has been deleted successfully!'));
    }
}
