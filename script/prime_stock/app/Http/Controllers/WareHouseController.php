<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WareHouseController extends Controller
{

    public function __construct()
    {
       // $this->middleware('warehouse_admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->input('per_page', settings('record_to_display', 10));
        $search = request()->input('search', null);
        $warehouses = WareHouse::latest()->search($search)->paginate($perPage);
        return view('warehouse.index', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'user_name' => 'required_if:is_admin,1',
            'email' => 'required_if:is_admin,1|unique:users,email',
            'password' => 'required_if:is_admin,1|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $warehouse = WareHouse::create($input);
            if($request->has('is_admin')) {
                $staff = User::create([
                    'name' => $request->user_name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'status' => 'active',
                ]);
                $staff->assignRole('Super Admin');
                $warehouse->update(['staff_id' => $staff->id]);
            }
            DB::commit();
            return redirect()->route('warehouse.index')->with('success', __('Warehouse has been stored successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $wareHouse = WareHouse::find($id);
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'user_name' => 'required_if:is_admin,1',
            'email' => 'required_if:is_admin,1|email|unique:users,email,' . $wareHouse->staff_id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $wareHouse->update($input);
            if($request->has('is_admin')) {
                $staff = User::find($wareHouse->staff_id);
                $staff->name = $request->user_name;
                $staff->email = $request->email;
                if($request->password && $request->password != ''){
                    $staff->password = bcrypt($request->password);
                }
                $staff->save();
            }
            DB::commit();
            return redirect()->route('warehouse.index')->with('success', __('Warehouse has been updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
