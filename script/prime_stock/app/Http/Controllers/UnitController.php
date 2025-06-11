<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $units = Unit::with('products')->latest()->search($search)->paginate($perPage);
        return view('units.index', compact('units'));
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

        $unit = Unit::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        if ($request->ajax()) {
            return response()->json($unit);
        }
        return redirect()->route('units.index')->with('success', __('Units has been stored successfully!'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $unit->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('units.index')->with('success', __('Unit has been updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->back()->with('success', __('Unit has been deleted successfully!'));
    }
}
