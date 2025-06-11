<?php

/**
 * Product and Warehouse stock quantity
 */

use App\Models\Adjustment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transfer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Permission as ModelsPermission;

/**
 * Stock Quantity
 * @param $product_id
 * @param $warehouse_id
 * @param $variation_id
 * @param $variation_value
 * @return int
 */
if (!function_exists('stock_quantity')) {
    function stock_quantity($product_id, $warehouse_id, $variation_id = null, $variation_value = null)
    {

        $adjustmentAdd = Adjustment::where('warehouse_id', $warehouse_id)->get()->map(function ($a) use ($product_id) {
            return $a->products->where('product_id', $product_id)->where('type', 'add')->sum('quantity');
        });

        $adjustmentSub = Adjustment::where('warehouse_id', $warehouse_id)->get()->map(function ($a) use ($product_id) {
            return $a->products->where('product_id', $product_id)->where('type', 'subtract')->sum('quantity');
        });

        $sale = Sale::with('products')->where('warehouse_id', $warehouse_id)->get()->map(function ($s) use ($product_id) {
            return $s->products->where('pivot.product_id', $product_id)->sum('pivot.quantity');
        });
        $saleReturn = Sale::with('returns')->where('warehouse_id', $warehouse_id)->get()->map(function ($s) use ($product_id) {
            return $s->returns->where('pivot.product_id', $product_id)->sum('pivot.quantity');
        });

        $purchase = Purchase::with('products')->where('warehouse_id', $warehouse_id)->get()->map(function ($p) use ($product_id) {
            return $p->products->where('pivot.product_id', $product_id)->sum('pivot.quantity');
        });
        $purchaseReturn = Purchase::with('returns')->where('warehouse_id', $warehouse_id)->get()->map(function ($p) use ($product_id) {
            return $p->returns->where('product_id', $product_id)->sum('return_quantity');
        });
        $transferIn = Transfer::where('to_warehouse_id', $warehouse_id)->get()->map(function ($t) use ($product_id) {
            return $t->products->where('product_id', $product_id)->sum('quantity');
        });

        $transferOut = Transfer::where('from_warehouse_id', $warehouse_id)->get()->map(function ($t) use ($product_id) {
            return $t->products->where('product_id', $product_id)->sum('quantity');
        });

        $adjustmentAdd = $adjustmentAdd->sum();
        $adjustmentSub = $adjustmentSub->sum();
        $sale = $sale->sum();
        $saleReturn = $saleReturn->sum();
        $purchase = $purchase->sum();
        $purchaseReturn = $purchaseReturn->sum();
        $transferIn = $transferIn->sum();
        $transferOut = $transferOut->sum();
        return $adjustmentAdd - $adjustmentSub + $purchase - $purchaseReturn + $transferIn - $transferOut - $sale + $saleReturn;
    }
}

/**
 * Variant Stock Quantity
 * @param $product_id
 * @param $warehouse_id
 * @param $variation_id
 * @param $variation_value
 * @return int
 */
if (!function_exists('variant_stock_quantity')) {
    function variant_stock_quantity($product_id, $warehouse_id, $variation_id, $variation_value)
    {
        $sale = Sale::with('products')->where('warehouse_id', $warehouse_id)->get()->map(function ($s) use ($product_id, $variation_id, $variation_value) {
            return $s->products->where('pivot.product_id', $product_id)
                ->where('pivot.variation_id', $variation_id)
                ->where('pivot.variation_value', $variation_value)
                ->sum('pivot.quantity');
        });

        $saleReturn = Sale::with('returns')->where('warehouse_id', $warehouse_id)->get()->map(function ($s) use ($product_id, $variation_id, $variation_value) {
            return $s->returns->where('pivot.product_id', $product_id)
                ->where('pivot.variation_id', $variation_id)
                ->where('pivot.variation_value', $variation_value)
                ->sum('pivot.quantity');
        });

        $purchase = Purchase::with('products')->where('warehouse_id', $warehouse_id)->get()->map(function ($p) use ($product_id, $variation_id, $variation_value) {
            return $p->products->where('pivot.product_id', $product_id)
                ->where('pivot.variation_id', $variation_id)
                ->where('pivot.variation_value', $variation_value)
                ->sum('pivot.quantity');
        });
        $purchaseReturn = Purchase::with('returns')->where('warehouse_id', $warehouse_id)->get()->map(function ($p) use ($product_id, $variation_id, $variation_value) {
            return $p->returns->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('variation_value', $variation_value)
                ->sum('return_quantity');
        });

        $adjustmentAdd = Adjustment::where('warehouse_id', $warehouse_id)->get()->map(function ($a) use ($product_id, $variation_id, $variation_value) {
            return $a->products->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('variation_value', $variation_value)
                ->where('type', 'add')->sum('quantity');
        });

        $adjustmentSub = Adjustment::where('warehouse_id', $warehouse_id)->get()->map(function ($a) use ($product_id, $variation_id, $variation_value) {
            return $a->products->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('variation_value', $variation_value)
                ->where('type', 'subtract')->sum('quantity');
        });

        $transferIn = Transfer::where('to_warehouse_id', $warehouse_id)->get()->map(function ($t) use ($product_id, $variation_id, $variation_value) {
            return $t->products->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('variation_value', $variation_value)
                ->sum('quantity');
        });

        $transferOut = Transfer::where('from_warehouse_id', $warehouse_id)->get()->map(function ($t) use ($product_id, $variation_id, $variation_value) {
            return $t->products->where('product_id', $product_id)
                ->where('variation_id', $variation_id)
                ->where('variation_value', $variation_value)
                ->sum('quantity');
        });

        $adjustmentAdd = $adjustmentAdd->sum();
        $adjustmentSub = $adjustmentSub->sum();
        $transferIn = $transferIn->sum();
        $transferOut = $transferOut->sum();
        $sale = $sale->sum();
        $saleReturn = $saleReturn->sum();
        $purchase = $purchase->sum();
        $purchaseReturn = $purchaseReturn->sum();
        return $adjustmentAdd - $adjustmentSub + $purchase - $purchaseReturn + $transferIn - $transferOut - $sale + $saleReturn;

    }
}

/**
 * Show amount with currency & precision
 * @param $amount
 * @param $currency
 * @param $precision
 * @return string
 */
if (!function_exists('showAmount')) {
    function showAmount($amount, $currency = '$', $precision = 2): string
    {
        $currency_symbol = $currency ?? settings('currency_symbol');
        $currency = settings('currency');
        $currency_format = settings('currency_format');

        if ($currency_format == 'symbol') {
            return $currency_symbol . number_format($amount, $precision) ?? 0;
        }

        if ($currency_format == 'text') {
            return number_format($amount, $precision) . ' ' . $currency ?? 0;
        }

        if ($currency_format == 'text_symbol') {
            return $currency_symbol . number_format($amount, $precision) . ' ' . $currency ?? 0;
        }
        return 0;
    }
}

/**
 * Default Currency
 * @return string
 */
if (!function_exists('defaultCurrency')) {
    function defaultCurrency()
    {
        return settings('currency_symbol') ?? '$';
    }
}

/**
 * Default Account
 * @return int
 */
if (!function_exists('defaultAccount')) {
    function defaultAccount()
    {
        return 1;
    }
}

/**
 * Array to Comma Separated String
 * @param $array
 * @return string
 */
if (!function_exists('arrayToString')) {
    function arrayToString($array): string
    {
        return implode(',', $array);
    }
}

/**
 * Array Badge Show
 * @param $array
 * @return string
 */
if (!function_exists('arrayBadge')) {
    function arrayBadge($array): string
    {
        $html = '';
        foreach ($array as $item) {
            $html .= '<span class="badge bg-outline-primary me-1">' . $item . '</span>';
        }
        return $html;
    }
}

/**
 * Get Settings
 * @param $key
 * @return mixed
 */
if (!function_exists('settings')) {
    function settings($key)
    {
        return \App\Models\Setting::get($key);
    }
}

/**
 * Timezone
 * @return array
 */
if (!function_exists('timezone')) {
    function timezone()
    {
        $jsonFile = file_get_contents(base_path('assets/json/timezone.json'));
        $timezone = json_decode($jsonFile, true);
        return $timezone;
    }
}

/**
 * @descripion Replace Shortcodes
 * @param $template
 * @param $data
 * @return array|mixed|string|string[]
 */
if (!function_exists('replaceShortcodes')) {
    function replaceShortcodes($template, $data): mixed
    {
        foreach ($data as $key => $value) {
            $template = str_replace('[[' . $key . ']]', $value, $template);
        }
        return $template;
    }
}

/**
 * Tax amount Calculate
 * @param $amount
 * @param $tax
 * @return float
 */
if (!function_exists('taxAmount')) {
    function taxAmount($amount, $tax): float
    {
        return $amount * $tax / 100;
    }
}

/**
 * Paper Sizes
 * @param $size
 * @return array
 */
if (!function_exists('paperSizes')) {
    function paperSizes($size = 40): array
    {
        $paperSizes = [
            '40' => [
                'label_width_mm' => '1.799in', // 1.799 inches * 25.4
                'label_height_mm' => '1.003in', // 1.003 inches * 25.4
                'sheet_width_mm' => '8.25in',
                'sheet_height_mm' => '11.6in',
            ],
            '30' => [
                'label_width_mm' => '2.625in', // 2.625 inches * 25.4
                'label_height_mm' => '1in', // 1 inch * 25.4
                'sheet_width_mm' => '8.45in', // Replace with actual size
                'sheet_height_mm' => '10.3in', // Replace with actual size
            ],
            '24' => [
                'label_width_mm' => '2.48in', // 2.48 inches * 25.4
                'label_height_mm' => '1.334in', // 1.334 inches * 25.4
                'sheet_width_mm' => '8.25in',
                'sheet_height_mm' => '11.6in',
            ],
            '20' => [
                'label_width_mm' => '4in', // 4 inches * 25.4
                'label_height_mm' => '1in', // 1 inch * 25.4
                'sheet_width_mm' => '8.45in', // Replace with actual size
                'sheet_height_mm' => '10.3in', // Replace with actual size
            ],
            '18' => [
                'label_width_mm' => '2.5in', // 2.5 inches * 25.4
                'label_height_mm' => '1.835in', // 1.835 inches * 25.4
                'sheet_width_mm' => '8.25in',
                'sheet_height_mm' => '11.6in',
            ],
            '14' => [
                'label_width_mm' => '4in', // 4 inches * 25.4
                'label_height_mm' => '1.33in', // 1.33 inches * 25.4
                'sheet_width_mm' => '8.45in', // Replace with actual size
                'sheet_height_mm' => '10.3in', // Replace with actual size
            ],
            '12' => [
                'label_width_mm' => '2.5in', // 2.5 inches * 25.4
                'label_height_mm' => '2.834in', // 2.834 inches * 25.4
                'sheet_width_mm' => '8.25in',
                'sheet_height_mm' => '11.6in',
            ],
            '10' => [
                'label_width_mm' => '4in', // 4 inches * 25.4
                'label_height_mm' => '2in', // 2 inches * 25.4
                'sheet_width_mm' => '8.45in', // Replace with actual size
                'sheet_height_mm' => '10.3in', // Replace with actual size
            ],
        ];

        return $paperSizes[$size];
    }
}

/**
 * Sale Information
 * @param $sale_id
 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
 */
if (!function_exists('sale_info')) {
    function sale_info($sale_id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Sale::with(['products'])->find($sale_id);
    }
}

/**
 * Convert Title Case
 * @param $string
 * @return string
 */
if (!function_exists('titleCase')) {
    function titleCase($string): string
    {
        return ucwords(str_replace('-', ' ', strtolower($string)));
    }
}

/**
 * Get Warehouse name
 * @param $warehouse_id
 * @return string
 */
if (!function_exists('warehouseName')) {
    function warehouseName($warehouse_id): string
    {
        return \App\Models\Warehouse::find($warehouse_id)->name;
    }
}

/**
 * Submenu Active
 * @param array $route
 * @return string
 */
if (!function_exists('submenuActive')) {
    function submenuActive(array $route = []): string
    {
        foreach ($route as $r) {
            if (request()->routeIs($r)) {
                return 'active open';
            }
        }

        return '';
    }
}

/**
 * Menu Active
 * @param $route
 * @return string
 */
if (!function_exists('menuActive')) {
    function menuActive($route): string
    {
        return request()->routeIs($route) ? 'active' : '';
    }
}

/**
 * Account Balance
 * @param $account_id
 * @return float
 */
if (!function_exists('accountBalance')) {
    function accountBalance($account_id): float
    {
        $deposit = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'deposit')->sum('amount');
        $purchase = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'purchase')->sum('amount');
        $sale = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'sale')->sum('amount');
        $purchase_return = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'purchase_return')->sum('amount');
        $sale_return = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'sale_return')->sum('amount');
        $expense = \App\Models\Transaction::where('account_id', $account_id)->where('type', 'expense')->sum('amount');
        $opening_balance = \App\Models\Account::find($account_id)->opening_balance;

        $debit = $deposit + $sale + $purchase_return + $opening_balance;
        $credit = $purchase + $sale_return + $expense;

        return $debit - $credit;
    }
}

/**
 * Variation name by id
 * @param $variation_id
 * @return string
 */
if (!function_exists('variationName')) {
    function variationName($variation_id): string
    {
        return \App\Models\Variation::find($variation_id)->name;
    }
}

/**
 * Check Permission
 * @param array $group
 * @return bool
 */
if (!function_exists('checkPermission')) {
    function checkPermission(array $group): bool
    {
        $permissions = ModelsPermission::whereIn('group_name', $group)->get()->pluck('name')->toArray();
        return auth()->user()->canAny($permissions);
    }
}

/**
 * App Mode
 * @return string
 */
if (!function_exists('appMode')) {
    function appMode(): string
    {
        return env('APP_MODE');
    }
}

/**
 * Product Information
 * @params int $productInfo
 */
if (!function_exists('productInfo')) {
    function productInfo($id)
    {
        return Product::find($id);
    }
}
/**
 * Check Warehouse Admin
 * @params int $checkWarehouseAdmin
 */
if (!function_exists('checkWarehouseAdmin')) {
    function checkWarehouseAdmin()
    {
        $currentUser = auth()->user()->id;
        return \App\Models\WareHouse::where('staff_id', $currentUser)->exists();
    }
}


if (!function_exists('generateQRCode')) {
    function generateQRCode($link)
    {
        return QrCode::size(100)->margin(1)->generate($link);
    }
}
