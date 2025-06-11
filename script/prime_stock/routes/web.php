<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturncontroller;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WareHouseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'app_mode']], function(){

    //Dashboard Route
    Route::controller(DashboardController::class)->group(function (){
       Route::get('/dashboard','index')->name('dashboard');
       Route::post('/widget-update','widgetUpdate')->name('widget-update');
       Route::get('/language-update','languageUpdate')->name('language-update');
    });
    //Language Controller
    Route::resource('language', LanguageController::class);
    Route::get('language-keyword/{language}', [LanguageController::class, 'languageKeyword'])->name('language-keyword');
    Route::post('language-keyword-update', [LanguageController::class, 'keywordUpdate'])->name('language-keyword-update');
    Route::get('language-sync-missing', [LanguageController::class, 'syncMissing'])->name('language-sync-missing');

    //Product Manage
    Route::resource('category', CategoryController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('units', UnitController::class);
    Route::resource('product', ProductController::class);
    Route::get('products/variation-details', [ProductController::class, 'variant'])->name('product.variant');
    Route::get('products/stock-details', [ProductController::class, 'stock'])->name('product.stock');
    Route::post('barcode-search', [ProductController::class, 'barcodeSearch'])->name('barcode.search');

    //Warehouse
    Route::resource('warehouse', WareHouseController::class);
    //Supplier
    Route::resource('supplier', SupplierController::class);
    Route::get('/supplier/payment/{supplier_id}', [SupplierController::class, 'payment'])->name('supplier.payment');
    Route::post('/supplier/payment-clear/{supplier_id}', [SupplierController::class, 'paymentClear'])->name('supplier.payment.clear');
    //Customer
    Route::resource('customer', CustomerController::class);
    Route::get('/customer/payment/{customer_id}', [CustomerController::class, 'payment'])->name('customer.payment');
    Route::post('/customer/payment-clear/{customer_id}', [CustomerController::class, 'paymentClear'])->name('customer.payment.clear');
    Route::get('/customer/notify/{customer_id}', [CustomerController::class, 'notify'])->name('customer.notify');
    Route::post('/customer/notify/{customer_id}', [CustomerController::class, 'notifySend'])->name('customer.notify.send');
    //Expense Type
    Route::resource('expense-type', ExpenseTypeController::class);
    //Expense
    Route::resource('expense', ExpenseController::class);
    //Purchase Controller
    Route::resource('purchase', PurchaseController::class);
    Route::get('purchase/product/search', [PurchaseController::class , 'product'])->name('purchase.product');
    Route::get('purchase/invoice/download/{purchase_id}/{source?}', [PurchaseController::class, 'invoice'])->name('invoice.download');
    Route::post('purchase/give/payment', [PurchaseController::class, 'givePayment'])->name('purchase.give.payment');
    Route::post('purchase/receive/payment', [PurchaseController::class, 'receivePayment'])->name('purchase.receive.payment');
    Route::get('/purchase/return/{purchase_id}', [PurchaseController::class, 'purchaseReturn'])->name('purchase.return');
    Route::post('/purchase/return/{purchase_id}', [PurchaseController::class, 'purchaseReturnPost'])->name('purchase.return.post');

    //Purchase Return
    Route::get('purchase-return', [PurchaseReturnController::class, 'index'])->name('purchase-return.index');
    Route::get('purchase-return/edit/{purchase_id}', [PurchaseReturncontroller::class, 'edit'])->name('purchase-return.edit');
    Route::post('purchase-return/update/{purchase_id}', [PurchaseReturncontroller::class, 'update'])->name('purchase-return.update');
    Route::get('purchase/return/invoice/{purchase_id}', [PurchaseReturnController::class, 'show'])->name('purchase.return.show');

    //Sale Controller
    Route::resource('sale', SaleController::class);
    Route::post('sale/receive/payment', [SaleController::class, 'receivePayment'])->name('sale.receive.payment');
    Route::post('sale/give/payment', [SaleController::class, 'givePayment'])->name('sale.give.payment');
    Route::get('sale/invoice/download/{sale_id}/{source?}', [SaleController::class, 'invoice'])->name('sale.invoice.download');
    Route::get('/sale/return/{sale_id}', [SaleController::class, 'saleReturn'])->name('sale.return');
    Route::post('/sale/return/{sale_id}', [SaleController::class, 'saleReturnPost'])->name('sale.return.post');

    //Sale Return
    Route::get('sale-return', [SaleReturnController::class, 'index'])->name('sale-return.index');
    Route::get('sale-return/edit/{sale_id}', [SaleReturnController::class, 'edit'])->name('sale-return.edit');
    Route::post('sale-return/update/{sale_id}', [SaleReturnController::class, 'update'])->name('sale-return.update');
    Route::get('sale/return/invoice/{sale_id}', [SaleReturnController::class, 'show'])->name('sale.return.show');
    //Adjustment Controller
    Route::resource('adjustment', \App\Http\Controllers\AdjustController::class);
    //Transfer Controller
    Route::resource('transfer', \App\Http\Controllers\TransferController::class);
    //Account Controller
    Route::resource('account', \App\Http\Controllers\AccountController::class);
    //Deposit Controller
    Route::resource('deposit', \App\Http\Controllers\DepositController::class);
    //Transaction Controller
    Route::get('transaction', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
    // Variation
    Route::resource('variation', \App\Http\Controllers\VariationController::class);
    // Settings
    Route::controller(\App\Http\Controllers\SettingController::class)->group(function (){
        Route::get('/settings', 'index')->name('settings');
        Route::post('/settings', 'update')->name('settings.update');
        Route::post('/settings/update-file', 'updateFile')->name('settings.update.file');
        Route::get('settings/email', 'email')->name('settings.email');
        Route::post('settings/email', 'emailUpdate')->name('settings.email.update');
        Route::post('settings/email-test', 'emailTest')->name('settings.email.test');
    });
    //Email Template
    Route::resource('email-template', \App\Http\Controllers\EmailTemplateController::class);
    //Print Labels
    Route::controller(\App\Http\Controllers\PrintLabelController::class)->group(function (){
        Route::get('print-labels', 'index')->name('print-labels');
        Route::post('print-labels', 'print')->name('print-labels.print');
    });

    //Role Resource
    Route::resource('role', \App\Http\Controllers\RoleController::class);
    //Staff Resource
    Route::resource('staff', \App\Http\Controllers\StaffController::class);
    //Report Controller
    Route::controller(\App\Http\Controllers\ReportController::class)->group(function (){
        Route::get('report/payment/supplier', 'supplierPaymentReport')->name('report.payment.supplier');
        Route::get('report/payment/customer', 'customerPaymentReport')->name('report.payment.customer');
        Route::get('report/stock', 'stockReport')->name('report.stock');
        Route::get('report/sale', 'saleReport')->name('report.sale');
        Route::get('report/purchase', 'purchaseReport')->name('report.purchase');
        Route::get('report/product', 'productReport')->name('report.product');
        Route::get('report/payment/sale', 'salePaymentReport')->name('report.payment.sale');
        Route::get('report/payment/purchase', 'purchasePaymentReport')->name('report.payment.purchase');
        Route::get('report/payment/sale/return', 'saleReturnPaymentReport')->name('report.payment.sale.return');
        Route::get('report/payment/purchase/return', 'purchaseReturnPaymentReport')->name('report.payment.purchase.return');
        Route::get('report/profit-loss', 'profitLoss')->name('report.profit.loss');
    });
    //Extra Controller
    Route::controller(\App\Http\Controllers\ExtraController::class)->group(function (){
        Route::get('/clear', 'clear')->name('clear');
        Route::get('/server', 'server')->name('server');
        Route::get('/application', 'application')->name('application');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::redirect('/','login');

require __DIR__.'/auth.php';
