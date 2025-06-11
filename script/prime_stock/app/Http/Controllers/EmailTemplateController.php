<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $emailTemplates = EmailTemplate::latest()->search($search)->paginate($perPage);
        return view('email-template.index', compact('emailTemplates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-template.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'type' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }

        $emailTemplate->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'type' => $request->type,
        ]);

        return redirect()->route('email-template.index')->with('success', __('Email template has been updated successfully!'));
    }
}
