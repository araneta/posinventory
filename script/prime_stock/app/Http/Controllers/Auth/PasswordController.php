<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        // check current is correct
        if(!Hash::check($request->current_password, $request->user()->password)){
            return back()->with('error', 'Current password is incorrect.');
        }

        if($validator->fails()){
            return back()->with('error', $validator->errors()->first());
        }

        $validated = $validator->validate();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
