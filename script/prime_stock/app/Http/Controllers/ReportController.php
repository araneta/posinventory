<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Supplier Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function supplierPaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $supplier = request()->supplier ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::whereIn('type', ['purchase', 'purchase_return'])
            ->where('amount', '!=', 0)
            ->search($search)
            ->date($date)
            ->supplier($supplier)
            ->paginate($perPage);
        $suppliers = Supplier::latest()->get();
        return view('report.supplier-payment-report', compact('payments', 'suppliers'));
    }

    /**
     * Customer Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function customerPaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $customer = request()->customer ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::whereIn('type', ['sale', 'sale_return'])
            ->search($search)
            ->date($date)
            ->customer($customer)
            ->where('amount', '!=', 0)
            ->paginate($perPage);
        $customers = Customer::latest()->get();
        return view('report.customer-payment-report', compact('payments', 'customers'));
    }

    /**
     * Purchase Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function purchaseReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $supplier = request()->supplier ?? null;
        $date = request()->date ?? null;
        $purchases = Purchase::latest()
            ->search($search)
            ->date($date)
            ->warehouse($warehouse)
            ->supplier($supplier)
            ->paginate($perPage);
        $warehouses = Warehouse::latest()->get();
        $suppliers = Supplier::latest()->get();
        return view('report.purchase-report', compact('purchases', 'warehouses', 'suppliers'));
    }

    /**
     * Sale Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function saleReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $customer = request()->customer ?? null;
        $sales = Sale::latest()
            ->search($search)
            ->warehouse($warehouse)
            ->customer($customer)
            ->paginate($perPage);
        $warehouses = Warehouse::latest()->get();
        $customers = Customer::latest()->get();
        return view('report.sale-report', compact('sales', 'warehouses', 'customers'));
    }

    /**
     * Product Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function productReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $category = request()->category ?? null;
        $type = request()->type ?? null;
        $products = Product::latest()
            ->search($search)
            ->category($category)
            ->type($type)
            ->paginate($perPage);
        $categories = Category::latest()->get();
        return view('report.product-report', compact('products', 'categories'));
    }

    /**
     * Sale Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function salePaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $customer = request()->customer ?? null;
        $account = request()->account ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::where('type', 'sale')
            ->where('amount', '!=', 0)
            ->search($search)
            ->warehouse($warehouse)
            ->customer($customer)
            ->account($account)
            ->date($date)
            ->paginate($perPage);
        $warehouses = Warehouse::latest()->get();
        $customers = Customer::latest()->get();
        $accounts = Account::latest()->get();
        return view('report.sale-payment-report', compact('payments', 'warehouses', 'customers', 'accounts'));
    }

    /**
     * Purchase Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function purchasePaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $supplier = request()->supplier ?? null;
        $account = request()->account ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::where('type', 'purchase')
            ->search($search)
            ->warehouse($warehouse)
            ->supplier($supplier)
            ->account($account)
            ->date($date)
            ->where('amount', '!=', 0)
            ->paginate($perPage);
        $warehouses = Warehouse::latest()->get();
        $suppliers = Supplier::latest()->get();
        $accounts = Account::latest()->get();
        return view('report.purchase-payment-report', compact('payments', 'warehouses', 'suppliers', 'accounts'));
    }

    /**
     * Sale Return Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function saleReturnPaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $customer = request()->customer ?? null;
        $account = request()->account ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::where('type', 'sale_return')
            ->where('amount', '!=', 0)
            ->search($search)
            ->warehouse($warehouse)
            ->customer($customer)
            ->account($account)
            ->date($date)
            ->paginate($perPage);
        $warehouses = Warehouse::latest()->get();
        $customers = Customer::latest()->get();
        $accounts = Account::latest()->get();
        return view('report.sale-return-payment-report', compact('payments', 'warehouses', 'customers', 'accounts'));
    }

    /**
     * Purchase Return Payment Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function purchaseReturnPaymentReport()
    {
        $search = request()->search ?? null;
        $perPage = request()->per_page ?? settings('default_per_page');
        $warehouse = request()->warehouse ?? null;
        $supplier = request()->supplier ?? null;
        $account = request()->account ?? null;
        $date = request()->date ?? null;
        $payments = Transaction::where('type', 'purchase_return')
            ->where('amount', '!=', 0)
            ->search($search)
            ->warehouse($warehouse)
            ->supplier($supplier)
            ->account($account)
            ->date($date)
            ->paginate(10);
        $warehouses = Warehouse::latest()->get();
        $suppliers = Supplier::latest()->get();
        $accounts = Account::latest()->get();
        return view('report.purchase-return-payment-report', compact('payments', 'warehouses', 'suppliers', 'accounts'));
    }

    /**
     * Stock Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function stockReport()
    {
        $warehouse = request()->warehouse;
        $warehouses = Warehouse::latest()->paginate(10);
        if($warehouse && $warehouse != 'all'){
            $products = Product::latest()->paginate(10);
        }else{
            $products = null;
        }
        return view('report.stock-report', compact('products', 'warehouses'));
    }

    /**
     * Profit Loss Report
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function profitLoss()
    {
        $warehouse = request()->warehouse ?? null;
        $date = request()->date ?? null;
        $totalSale = Sale::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('receivable_amount');
        $totalPurchase = Purchase::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('payable_amount');
        $totalSaleReturn = Sale::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('return_amount');
        $totalPurchaseReturn = Purchase::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('return_amount');
        $totalExpense = Expense::latest()->date($date)->sum('amount');
        $purchaseTax = Purchase::latest()->warehouse($warehouse)
            ->date($date)
            ->get()->map(function($purchase){
            return $purchase->totalTaxAmount();
        })->sum();
        $saleTax = Sale::latest()->warehouse($warehouse)
            ->date($date)
            ->get()->map(function($sale){
            return $sale->totalTaxAmount();
        })->sum();
        $totalTax = $purchaseTax + $saleTax;
        $purchaseDiscount = Purchase::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('discount');
        $saleDiscount = Sale::latest()->warehouse($warehouse)
            ->date($date)
            ->sum('discount');
        $totalDiscount = $purchaseDiscount + $saleDiscount;
        $grossProfit = ($totalSale - $totalSaleReturn) - ($totalPurchase - $totalPurchaseReturn) - $totalTax + $totalDiscount;
        $netProfit = $grossProfit - $totalExpense;
        $warehouses = Warehouse::latest()->get();
        return view('report.profit-loss', compact('totalSale', 'totalPurchase', 'totalSaleReturn', 'totalPurchaseReturn', 'totalExpense', 'totalTax', 'totalDiscount', 'grossProfit', 'netProfit', 'warehouses'));
    }
}
