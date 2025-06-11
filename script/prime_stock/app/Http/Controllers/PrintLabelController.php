<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class PrintLabelController extends Controller
{
    public function index()
    {
        $warehouses = WareHouse::all();
        return view('print-label.index', compact('warehouses'));
    }

    public function print(Request $request)
    {
        $product_id = $request->product_id;
        $mainQuantity = $request->quantity;
        $paper_size = $request->paper_size;
        $productCode = Product::find($product_id)->code;
        $productName = Product::find($product_id)->name;
        $productPrice = showAmount(Product::find($product_id)->sale_price);
        $labelWidth = paperSizes($paper_size)['label_width_mm'];
        $labelHeight = paperSizes($paper_size)['label_height_mm'];
        $sheetWidth = paperSizes($paper_size)['sheet_width_mm'];
        $sheetHeight = paperSizes($paper_size)['sheet_height_mm'];
        $totalPage = (int)ceil($mainQuantity / $paper_size);
        if($totalPage > 1){
            $quantityPerPage = $paper_size;
        }else{
            $quantityPerPage = $mainQuantity;
        }

        return view('print-label.print', compact('productCode', 'quantityPerPage', 'paper_size', 'productName', 'productPrice', 'labelWidth', 'labelHeight', 'sheetWidth', 'sheetHeight', 'totalPage', 'mainQuantity'));
    }
}
