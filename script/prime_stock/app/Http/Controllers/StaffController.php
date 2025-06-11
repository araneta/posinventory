<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Traits\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    use Notification;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $staffs = User::search($search)->latest()->paginate($perPage);
        $roles = Role::all();
        return view('staff.index', compact('staffs', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $staff = new User();
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->save();
        $staff->assignRole($request->role);

        $emailTemplate = EmailTemplate::where('type', 'default')->first();
        $shortCodes = [
            'full_name' => $staff->name,
            'user_name' => $staff->name,
            'company_name' => settings('company_name'),
            'contact_no' => settings('company_phone'),
            'invoice_no' => '',
            'email' => $staff->email,
            'password' => $request->password,
            'url' => route('login'),
        ];
        $processedMessage = replaceShortcodes($emailTemplate->content, $shortCodes);
        $this->sendMailNotification($staff->email, $emailTemplate->subject, $processedMessage);
        return redirect()->back()->with('success', __('Staff created successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $staff = User::findOrFail($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        if($request->password && $request->password != ''){
            $staff->password = bcrypt($request->password);
        }
        $staff->save();
        $staff->syncRoles([$request->role]);

        return redirect()->back()->with('success', __('Staff updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $staff = User::findOrFail($id);
        if($staff->status == 'banned') {
            $staff->status = 'active';
        }else{
            $staff->status = 'banned';
        }
        $staff->save();
        return redirect()->back()->with('success', __('Staff status updated successfully'));
    }
}
