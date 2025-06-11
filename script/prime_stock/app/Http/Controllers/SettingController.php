<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Setting;
use App\Models\WareHouse;
use App\Traits\ImageUpload;
use App\Traits\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Sabberworm\CSS\Settings;

class SettingController extends Controller
{
    use ImageUpload, Notification;
    /**
     * Setting Index Method
     */
    public function index()
    {
        $customers = Customer::all();
        $warehouses = Warehouse::all();
        return view('setting.index', compact('customers', 'warehouses'));
    }

    /**
     * Setting Update Method
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_title' => 'required',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'timezone' => 'required',
            'record_to_display' => 'required',
            'currency_format' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all the required fields');
        }

        Setting::set('site_title', $request->site_title);
        Setting::set('currency', $request->currency);
        Setting::set('currency_symbol', $request->currency_symbol);
        Setting::set('timezone', $request->timezone);
        Setting::set('record_to_display', $request->record_to_display);
        Setting::set('currency_format', $request->currency_format);
        Setting::set('footer_text', $request->footer_text);
        Setting::set('company_name', $request->company_name);
        Setting::set('company_address', $request->company_address);
        Setting::set('company_phone', $request->company_phone);
        Setting::set('company_email', $request->company_email);
        Setting::set('default_customer', $request->default_customer);
        Setting::set('default_warehouse', $request->default_warehouse);

        if($request->has('logo') && $request->logo != null) {
            $filePath = $request->logo;
            $destinationPath = base_path('assets/uploads');
            $absolutePath = Storage::path($filePath);
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            if (File::exists($absolutePath)) {
                if(File::exists(base_path('assets/uploads/'.Setting::get('logo')))) {
                    File::delete(base_path('assets/uploads/'.Setting::get('logo')));
                }
                $moved = File::move($absolutePath, $destinationPath . '/' . basename($absolutePath));
                Setting::set('logo', basename($absolutePath));
            } else {
                return redirect()->back()->with('error', __('Please select a valid image file'));
            }

        }
        if($request->has('favicon') && $request->favicon != null) {
            $filePath = $request->favicon;
            $destinationPath = base_path('assets/uploads');
            $absolutePath = Storage::path($filePath);
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            if (File::exists($absolutePath)) {
                if(File::exists(base_path('assets/uploads/'.Setting::get('favicon')))) {
                    File::delete(base_path('assets/uploads/'.Setting::get('favicon')));
                }
                $moved = File::move($absolutePath, $destinationPath . '/' . basename($absolutePath));
                Setting::set('favicon', basename($absolutePath));
            } else {
                return redirect()->back()->with('error', __('Please select a valid image file'));
            }
        }
        return redirect()->back()->with('success', __('Settings updated successfully'));
    }

    /**
     * Setting Update FIle Method
     * @param Request $request
     * @return false|\Illuminate\Http\RedirectResponse|string
     */
    public function updateFile(Request $request)
    {
        $files = $request->allFiles();

        if(empty($files)) {
            return redirect()->back()->with('error', __('Please select a valid image file'));
        }
        if(count($files) > 1) {
            return redirect()->back()->with('error', __('Please select only one file'));
        }

        $requestKey = array_key_first($files);

        $files = $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        return $file->store(
            path: 'tmp/'.now()->timestamp.'-'.Str::random(20)
        );
    }

    /**
     * Email Setting
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function email()
    {
        return view('setting.email');
    }

    /**
     * Email Setting Update
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', __('Please fill all the required fields'));
        }

        Setting::set('mail_driver', $request->mail_driver);
        Setting::set('mail_host', $request->mail_host);
        Setting::set('mail_port', $request->mail_port);
        Setting::set('mail_username', $request->mail_username);
        Setting::set('mail_password', $request->mail_password);
        Setting::set('mail_encryption', $request->mail_encryption);
        Setting::set('mail_from_address', $request->mail_from_address);
        Setting::set('mail_from_name', $request->mail_from_name);

        return redirect()->back()->with('success', __('Email settings updated successfully'));
    }

    /**
     * Email Test
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', __('Please enter a valid email address'));
        }

        $subject = 'Test Email';
        $message = 'This is a test email from your application. If you are receiving this email, it means your email settings are working fine.';
        try {
           $this->sendMailNotification($request->email, $subject, $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to send test email. Please check your email settings'));
        }

        return redirect()->back()->with('success', __('Test email sent successfully'));
    }
}
