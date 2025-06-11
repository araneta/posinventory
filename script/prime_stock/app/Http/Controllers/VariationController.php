<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $variations = Variation::latest()->search($search)->paginate($perPage);
        return view('variations.index', compact('variations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'values' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', __('Please fill the required fields'));
        }

        $variation = new Variation();
        $variation->name = $request->name;
        $variation->values = $request->values;
        $variation->save();

        return redirect()->back()->with('success', __('Variation created successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Variation $variation)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'values' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', __('Please fill the required fields'));
        }

        $variation->name = $request->name;
        $variation->values = $request->values;
        $variation->save();

        return redirect()->back()->with('success', __('Variation updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variation $variation)
    {
        $variation->delete();
        return redirect()->back()->with('success', __('Variation deleted successfully'));
    }
}
