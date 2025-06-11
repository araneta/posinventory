<?php

namespace App\Http\Controllers;

use App\Models\CustomRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $roles = CustomRole::search($search)->whereNot('name', 'Super Admin')->paginate($perPage);
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $role = Role::create(['name' => $request->name]);
        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
        $role->syncPermissions($permissions);

        return redirect()->route('role.index')->with('success', __('Role has been stored successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('pivot.permission_id')->toArray();
        return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
        $role->syncPermissions($permissions);

        return redirect()->route('role.index')->with('success', __('Role has been updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', __('Role has been deleted successfully!'));
    }
}
