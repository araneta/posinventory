<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DashboardController extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index()
    {
        $widget = session()->get('widget');
        $total = [
            'total_customer' => Customer::count(),
            'total_supplier' => Supplier::count(),
            'total_product' => Product::where('status', 1)->count(),
            'total_category' => Category::where('status', 1)->count(),
        ];
        $sale = [
            'total' => Sale::count(),
            'total_amount' => Sale::sum('received_amount'),
            'total_return' => Sale::where('return_product','!=', 0)->count(),
            'total_return_amount' => Sale::where('return_product','!=', 0)->sum('paying_amount'),
        ];

        $purchase = [
            'total' => Purchase::count(),
            'total_amount' => Purchase::sum('paying_amount'),
            'total_return' => Purchase::where('return_product','!=', 0)->count(),
            'total_return_amount' => Purchase::where('return_product','!=', 0)->sum('received_amount'),
        ];
        $topSellingProducts = DB::table('product_sale')
            ->select('product_id','variation_id', 'variation_value', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id','variation_id','variation_value')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate Stock Level Alert
        $stockLevelAlert = [];
        $warehouses = WareHouse::all();

        foreach ($warehouses as $warehouse) {
            $products = Product::where('status', 1)->get();
            
            foreach ($products as $product) {
                if ($product->product_type == 'standard') {
                    $stock = stock_quantity($product->id, $warehouse->id);
                    if ($stock <= $product->alert_quantity) {
                        $stockLevelAlert[] = [
                            'product' => $product->name,
                            'warehouse' => $warehouse->name,
                            'alert' => $product->alert_quantity,
                            'stock' => $stock,
                            'unit' => $product->unit->name,
                        ];
                    }
                } elseif ($product->product_type == 'variation') {
                    foreach ($product->variants as $variant) {
                        $stock = variant_stock_quantity($product->id, $warehouse->id, $variant->pivot->variation_id, $variant->pivot->value);
                        if ($stock <= $variant->pivot->alert_quantity) {
                            $stockLevelAlert[] = [
                                'product' => $product->name,
                                'variants' => variationName($variant->pivot->variation_id) . ': ' . $variant->pivot->value,
                                'warehouse' => $warehouse->name,
                                'alert' => $variant->pivot->alert_quantity,
                                'stock' => $stock,
                                'unit' => $product->unit->name,
                            ];
                        }
                    }
                }
            }
        }

        // Sort the stock level alert by stock quantity in ascending order
        usort($stockLevelAlert, function($a, $b) {
            return $a['stock'] <=> $b['stock'];
        });

        // Limit to top 5 items with lowest stock
        $stockLevelAlert = array_slice($stockLevelAlert, 0, 5);

        
        // Get all dates for the current month (e.g., 01 September, 02 September, etc.)
        $thisMonthDate = [];
        for ($i = 1; $i <= date('t'); $i++) {
            $thisMonthDate[] = date('d M', strtotime(date('Y-m') . '-' . $i));
        }

        $purchaseDataForEveryDay = [];
        $saleDataForEveryDay = [];
        foreach ($thisMonthDate as $date) {
            $purchaseDataForEveryDay[$date] = Purchase::whereDate('date', date('Y-m-d', strtotime($date)))->sum('paying_amount');
            $saleDataForEveryDay[$date] = Sale::whereDate('date', date('Y-m-d', strtotime($date)))->sum('received_amount');
        }
        return view('dashboard', compact('widget', 'total', 'sale', 'purchase', 'topSellingProducts', 'thisMonthDate', 'purchaseDataForEveryDay', 'saleDataForEveryDay', 'stockLevelAlert'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function widgetUpdate(Request $request)
    {
        unset($request['_token']);
        $widgetArray = [];
        foreach ($request->all() as $key => $widget) {
            $widgetArray[$key] = $widget;
        }

        //session set this array
        session()->put('widget', $widgetArray);
        return redirect()->back()->with('success', __('Widget updated successfully'));
    }

    /*
     * Translation
     * */
    public function languageUpdate(Request $request)
    {
        App::setLocale($request->name);
        session()->put('locale', $request->name);

        return redirect()->back();
    }
}
