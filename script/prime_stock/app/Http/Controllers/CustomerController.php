<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\Notification;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use Transaction, Notification;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', settings('record_to_display', 10));
        $search = request('search', null);
        $customers = Customer::latest()->search($search)->paginate($perPage);
        return view('customer.index', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
            'email' => 'email|required|unique:customers',
            'phone' => 'required|unique:customers',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $customer = Customer::create($input);
        if ($request->ajax()) {
            return response()->json($customer);
        }
        return redirect()->route('customer.index')->with('success', __('Customer has been stored successfully!'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:15',
            'email' => 'email',
            'phone' => ['required', 'unique:customers,phone,' . $customer->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $customer->update($input);
        return redirect()->route('customer.index')->with('success', __('Customer has been updated successfully!'));
    }

    /**
     * payment
     */
    public function payment($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return view('customer.payment', compact('customer'));
    }

    /**
     * paymentClear
     */
    public function paymentClear(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);

        DB::beginTransaction();
        try {
            foreach ($customer->sales as $sale) {
                $amount = $sale->receivable_amount - $sale->received_amount;
                if ($sale->status === 'due') {
                    $sale->status = 'received';
                    $sale->received_amount = $sale->receivable_amount;
                    $sale->received_date = date('Y-m-d');
                    $sale->save();
                    $this->createTransaction([
                        'type' => 'sale',
                        'account_id' => defaultAccount(),
                        'amount' => $amount,
                        'type_id' => $sale->id,
                        'note' => 'Sale Payment Received',
                        'date' => date('Y-m-d'),
                    ]);
                }

                $paid_amount = $sale->payable_amount - $sale->paying_amount;
                if ($sale->paying_status === 'due') {
                    $sale->paying_status = 'paid';
                    $sale->paying_amount = $sale->payable_amount;
                    $sale->paying_date = date('Y-m-d');
                    $sale->save();
                    $this->createTransaction([
                        'type' => 'sale_return',
                        'account_id' => defaultAccount(),
                        'amount' => $paid_amount,
                        'type_id' => $sale->id,
                        'note' => 'Sale Payment Paid',
                        'date' => date('Y-m-d'),
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('customer.index')->with('success', __('Payment has been cleared successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Payment could not be cleared'));
        }
    }

    public function notify($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return view('customer.notify', compact('customer'));
    }

    public function notifySend(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $shortCodes = [
            'full_name' => $customer->name,
            'user_name' => $customer->name,
            'company_name' => settings('company_name'),
            'contact_no' => settings('company_phone'),
            'invoice_no' => '',
            'email' => $customer->email,
            'password' => '',
            'url' => route('login'),
        ];
        $processedMessage = replaceShortcodes($request->content, $shortCodes);

        $this->sendMailNotification($customer->email, $request->subject, $processedMessage);
        return redirect()->route('customer.index')->with('success', __('Notification has been sent successfully!'));
    }
}
