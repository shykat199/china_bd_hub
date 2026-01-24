<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Productstock;
use App\Modules\Backend\ProductManagement\Entities\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request  $request)
    {
        $allowedLimits = [10, 25, 50, 100];

        // Works everywhere
        $limit = (int) $request->get('limit', 10);
        $limit = in_array($limit, $allowedLimits, true) ? $limit : 10;

        $products = Product::where('is_manage_stock', 1)->latest()->paginate($limit);
        return view('backend.pages.stocks.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('productstock.size', 'productstock.color')->findOrFail($id);
        return view('backend.pages.stocks.show', compact('product'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        Productstock::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true]);
    }
}
