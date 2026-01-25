<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\CartItem;
use App\Models\Frontend\Product;
use App\Models\Productstock;
use App\Models\ShippingArea;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
//    public function addToCart(Request $request)
//    {
//        $quantity = $request->get('qty');
//        $product = Product::with('details')->findOrFail($request->get('id'));
//
//        if ($product->is_manage_stock && $request->get('qty') > $product->quantity) {
//            return response()->json(__('Out of stock product. Available quantity is '.$product->quantity));
//        }
//
//        if ($request->color || $request->size) {
//            $product_stock = Productstock::where('product_id', $product->id)->where('color_id', $request->get('color_id'))->where('size_id', $request->get('size_id'))->first();
//            if (!$product_stock && !isset($product_stock->quantities)) {
//                return response()->json(__('This varint is out of stock. Please select another variant.'));
//            }
//        }
//
//        $cart = json_decode(Cookie::get('cart', ''), true);
//        $uniqid = $request->color || $request->size ? $request->color.$request->size.$request->get('id') : $request->get('id');
//
//        $cart_collection = collect($cart);
//        $is_common_seller = $cart_collection->contains('seller_id', $product->seller_id);
//        $shipping_areas = ShippingArea::where('status', 1)->orderBy('id', 'asc')->first();
//
//        $totalShipping = Cookie::get('totalShipping') ?? 0;
//        if (!$is_common_seller) {
//            //$totalShipping += $request->shipping_area == 'inside' ? $product->shipping_cost : $product->outside_shipping_cost;
//            $totalShipping = $shipping_areas->charge;
//        }
//
//        $quantity = is_numeric($quantity) ? $quantity : 1;
//        if (isset($cart[$uniqid]['id']) == $uniqid) {
//            $total_price = CartItem::price($product->id, $cart[$uniqid]['quantity'] + $quantity);
//            $cart[$uniqid] = [
//                'id' => $product->id,
//                'discount' => $product->discount,
//                'vat' => ($total_price / 100) * $product->details->vat,
//                'quantity' => $cart[$uniqid]['quantity'] + $quantity,
//                'currency_id' => userCurrency('id'),
//                'courier' => $request->get('courier'),
//                'color' => $request->get('color'),
//                'color_id' => $request->get('color_id'),
//                'size' => $request->get('size'),
//                'size_id' => $request->get('size_id'),
//                'seller_id' => $product->seller_id,
//                'shipping_area' => $request->shipping_area,
//                'total' => CartItem::shipping($product->id, $cart[$uniqid]['quantity'] + $quantity) + $total_price,
//                'product_stock'=> $product->quantity
//            ];
//        } else {
//            $total_price = CartItem::price($product->id, $quantity);
//            $cart[$uniqid] = [
//                'id' => $product->id,
//                'quantity' => $quantity,
//                'vat' => ($total_price / 100) * $product->details->vat,
//                'discount' => $product->discount,
//                'currency_id' => userCurrency('id'),
//                'courier' => $request->get('courier'),
//                'color' => $request->get('color'),
//                'color_id' => $request->get('color_id'),
//                'size' => $request->get('size'),
//                'size_id' => $request->get('size_id'),
//                'seller_id' => $product->seller_id,
//
//                'shipping_area' => $request->shipping_area,
//                'total' => CartItem::shipping($product->id, $quantity) + $total_price,
//                'product_stock'=> $product->quantity
//            ];
//        }
//
//        Cookie::queue(Cookie::make('cart', json_encode($cart), 120));
//
//        $subTotal = 0;
//        $total_vat = 0;
//        $total_discount = 0;
//        foreach ($cart as $item) {
//            $total_vat += $item['vat'] ?? 0;
//            $total_discount += $item['discount'];
//            $subTotal += CartItem::price($item['id'], $item['quantity']);
//        }
//
//        $total = $subTotal + $totalShipping;
//
//        Cookie::queue(Cookie::make('total', $total));
//        Cookie::queue(Cookie::make('subTotal', $subTotal));
//        Cookie::queue(Cookie::make('total_vat', $total_vat));
//        Cookie::queue(Cookie::make('total_discount', $total_discount));
//        Cookie::queue(Cookie::make('totalShipping', $totalShipping));
//
//        $count = count($cart); //count cart items
//
//        return response(['status' => 'success', 'count' => $count, 'name' => $product->name]);
//    }

    public function addToCart(Request $request)
    {
        $quantity = $request->get('qty');
        $product = Product::with('details')->findOrFail($request->get('id'));

        if ($product->is_manage_stock && $request->get('qty') > $product->quantity) {
            return response()->json(__('Out of stock product. Available quantity is '.$product->quantity));
        }

        if ($request->color || $request->size) {
            $product_stock = Productstock::where('product_id', $product->id)
                ->where('color_id', $request->get('color_id'))
                ->where('size_id', $request->get('size_id'))
                ->first();

            if (!$product_stock && !isset($product_stock->quantities)) {
                return response()->json(__('This variant is out of stock. Please select another variant.'));
            }
        }

        /* ===========================
           SESSION CART (instead of cookie)
        =========================== */
        $cart = session()->get('cart', []);

        $uniqid = ($request->color || $request->size)
            ? $request->color.$request->size.$request->get('id')
            : $request->get('id');

        $cart_collection = collect($cart);
        $is_common_seller = $cart_collection->contains('seller_id', $product->seller_id);

        $shipping_areas = ShippingArea::where('status', 1)->orderBy('id', 'asc')->first();

        $totalShipping = session()->get('totalShipping', 0);
        if (!$is_common_seller) {
            $totalShipping = $shipping_areas->charge;
        }

        $quantity = is_numeric($quantity) ? $quantity : 1;

        if (isset($cart[$uniqid])) {
            $total_price = CartItem::price($product->id, $cart[$uniqid]['quantity'] + $quantity);

            $cart[$uniqid] = [
                'id' => $product->id,
                'discount' => $product->discount,
                'vat' => ($total_price / 100) * $product->details->vat,
                'quantity' => $cart[$uniqid]['quantity'] + $quantity,
                'currency_id' => userCurrency('id'),
                'courier' => $request->get('courier'),
                'color' => $request->get('color'),
                'color_id' => $request->get('color_id'),
                'size' => $request->get('size'),
                'size_id' => $request->get('size_id'),
                'seller_id' => $product->seller_id,
                'shipping_area' => $request->shipping_area,
                'total' => CartItem::shipping($product->id, $cart[$uniqid]['quantity'] + $quantity) + $total_price,
                'product_stock' => $product->quantity
            ];
        } else {
            $total_price = CartItem::price($product->id, $quantity);

            $cart[$uniqid] = [
                'id' => $product->id,
                'quantity' => $quantity,
                'vat' => ($total_price / 100) * $product->details->vat,
                'discount' => $product->discount,
                'currency_id' => userCurrency('id'),
                'courier' => $request->get('courier'),
                'color' => $request->get('color'),
                'color_id' => $request->get('color_id'),
                'size' => $request->get('size'),
                'size_id' => $request->get('size_id'),
                'seller_id' => $product->seller_id,
                'shipping_area' => $request->shipping_area,
                'total' => CartItem::shipping($product->id, $quantity) + $total_price,
                'product_stock' => $product->quantity
            ];
        }

        /* ===========================
           SAVE TO SESSION
        =========================== */
        session()->put('cart', $cart);

        $subTotal = 0;
        $total_vat = 0;
        $total_discount = 0;

        foreach ($cart as $item) {
            $total_vat += $item['vat'] ?? 0;
            $total_discount += $item['discount'];
            $subTotal += CartItem::price($item['id'], $item['quantity']);
        }

        $total = $subTotal + $totalShipping;

        session()->put('total', $total);
        session()->put('subTotal', $subTotal);
        session()->put('total_vat', $total_vat);
        session()->put('total_discount', $total_discount);
        session()->put('totalShipping', $totalShipping);

        $count = count($cart);

        return response([
            'status' => 'success',
            'count' => $count,
            'name' => $product->name
        ]);
    }
//    public function updateCart(Request $request)
//    {
//        $key = $request->get('key');
//        $id = $request->get('id');
//        $product = Product::with('details')->findOrFail($id);
//        $cart = json_decode(Cookie::get('cart'), true);
//        $total_price = CartItem::price($id, $request->get('qty'));
//
//        if ($product->is_manage_stock && $request->get('qty') > $product->quantity) {
//            return response()->json($request->get('qty'). __(' quantities is not available.'));
//        }
//
//        $cart[$key] = [
//            'id' => $id,
//            'quantity' => $request->get('qty'),
//            'vat' => ($total_price / 100) * $product->details->vat,
//            'currency_id' => userCurrency('id'),
//            'discount' => $product->discount,
//            'size' => $cart[$key]['size'] ?? NULL,
//            'color' => $cart[$key]['color'] ?? NULL,
//            'size_id' => $cart[$key]['size_id'] ?? NULL,
//            'courier' => $cart[$key]['courier'] ?? NULL,
//            'color_id' => $cart[$key]['color_id'] ?? NULL,
//            'total' => CartItem::shipping($id, $request->get('qty')) + $total_price,
//            'product_stock'=> $product->quantity
//        ];
//
//        Cookie::queue(Cookie::make('cart', json_encode($cart), 120));
//
//        $subTotal = 0;
//        $totalShipping = Cookie::get('totalShipping') ?? 0;
//        foreach ($cart as $item) {
//            $subTotal += CartItem::price($item['id'], $item['quantity']);
//        }
//
//        $productTotal = CartItem::price($id, $request->get('qty'));
//        $total = $subTotal + $totalShipping;
//
//        Cookie::queue(Cookie::make('subTotal', $subTotal, 120));
//        Cookie::queue(Cookie::make('totalShipping', $totalShipping, 120));
//        Cookie::queue(Cookie::make('total', $total, 120));
//
//        return response([
//            'status' => 'success',
//            'sub_total' => currency($subTotal, 2),
//            'productTotal' => currency($productTotal, 2),
//            'grand_total' => currency($subTotal + $totalShipping, 2),
//        ]);
//    }

    public function updateCart(Request $request)
    {
        $key = $request->get('key');
        $id  = $request->get('id');

        $product = Product::with('details')->findOrFail($id);

        $cart = session()->get('cart', []);

        $total_price = CartItem::price($id, $request->get('qty'));

        if ($product->is_manage_stock && $request->get('qty') > $product->quantity) {
            return response()->json($request->get('qty') . __(' quantities is not available.'));
        }

        if (!isset($cart[$key])) {
            return response()->json(__('Cart item not found.'));
        }

        $cart[$key] = [
            'id' => $id,
            'quantity' => $request->get('qty'),
            'vat' => ($total_price / 100) * $product->details->vat,
            'currency_id' => userCurrency('id'),
            'discount' => $product->discount,
            'size' => $cart[$key]['size'] ?? null,
            'color' => $cart[$key]['color'] ?? null,
            'size_id' => $cart[$key]['size_id'] ?? null,
            'courier' => $cart[$key]['courier'] ?? null,
            'color_id' => $cart[$key]['color_id'] ?? null,
            'seller_id' => $cart[$key]['seller_id'] ?? null,
            'shipping_area' => $cart[$key]['shipping_area'] ?? null,
            'total' => CartItem::shipping($id, $request->get('qty')) + $total_price,
            'product_stock' => $product->quantity
        ];

        /* ===========================
           SAVE SESSION CART
        =========================== */
        session()->put('cart', $cart);

        /* ===========================
           RECALCULATE TOTALS
        =========================== */
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += CartItem::price($item['id'], $item['quantity']);
        }

        $totalShipping = session()->get('totalShipping', 0);
        $total = $subTotal + $totalShipping;

        session()->put('subTotal', $subTotal);
        session()->put('totalShipping', $totalShipping);
        session()->put('total', $total);

        $productTotal = CartItem::price($id, $request->get('qty'));

        return response([
            'status' => 'success',
            'sub_total' => currency($subTotal, 2),
            'productTotal' => currency($productTotal, 2),
            'grand_total' => currency($total, 2),
        ]);
    }

    public function updateShipping(Request $request)
    {
        session()->put('totalShipping', $request->shipping_cost);

        return response()->json([
            'success' => true,
            'totalShipping' => session('totalShipping')
        ]);
    }

    /**
     * Remove item form cart by ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     */
//    public function removeFromCart(Request $request)
//    {
//        $key = $request->get('key');
//        $id = $request->get('id');
//
//        $cart = json_decode(Cookie::get('cart'), true);
//        $remove_item = $cart[$key];
//        unset($cart[$key]);
//        if (empty($cart)) {
//            Cookie::queue(Cookie::forget('cart'));
//        } else {
//            Cookie::queue(Cookie::make('cart', json_encode($cart), 120));
//        }
//
//        $count = count($cart);
//        $product = Product::query()->findOrFail($id);
//
//        $cart_collection = collect($cart);
//        $is_common_seller = $cart_collection->contains('seller_id', $product->seller_id);
//
//        $totalShipping = Cookie::get('totalShipping') ?? 0;
//        if (!$is_common_seller) {
//            $totalShipping -= $remove_item['shipping_area'] == 'inside' ? $product->shipping_cost : $product->outside_shipping_cost;
//        }
//
//        $subTotal = 0;
//        foreach ($cart as $item) {
//            $subTotal += CartItem::price($item['id'], $item['quantity']);
//        }
//
//        Cookie::queue(Cookie::make('total', $subTotal, 120));
//        Cookie::queue(Cookie::make('subTotal', $subTotal, 120));
//        Cookie::queue(Cookie::make('totalShipping', $totalShipping));
//
//        return response([
//            'status' => 'success',
//            'count' => $count,
//            'sub_total' => currency($subTotal, 2),
//            'grand_total' => currency($subTotal, 2),
//            'totalShipping' => currency($totalShipping, 2),
//        ]);
//    }

    public function removeFromCart(Request $request)
    {
        $key = $request->get('key');
        $id  = $request->get('id');

        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return response()->json(__('Cart item not found.'));
        }

        $remove_item = $cart[$key];
        unset($cart[$key]);

        // Save or forget cart session
        if (empty($cart)) {
            session()->forget('cart');
        } else {
            session()->put('cart', $cart);
        }

        $count = count($cart);
        $product = Product::findOrFail($id);

        // Check seller for shipping recalculation
        $cart_collection = collect($cart);
        $is_common_seller = $cart_collection->contains('seller_id', $product->seller_id);

        $totalShipping = session()->get('totalShipping', 0);

        if (!$is_common_seller) {
            // keep your original logic
            if (isset($remove_item['shipping_area']) && $remove_item['shipping_area'] === 'inside') {
                $totalShipping -= $product->shipping_cost;
            } else {
                $totalShipping -= $product->outside_shipping_cost;
            }
        }

        // Recalculate subtotal
        $subTotal = 0;
        foreach ($cart as $item) {
            $subTotal += CartItem::price($item['id'], $item['quantity']);
        }

        // Save totals to session
        session()->put('subTotal', $subTotal);
        session()->put('totalShipping', $totalShipping);
        session()->put('total', $subTotal + $totalShipping);

        return response([
            'status' => 'success',
            'count' => $count,
            'sub_total' => currency($subTotal, 2),
            'grand_total' => currency($subTotal + $totalShipping, 2),
            'totalShipping' => currency($totalShipping, 2),
        ]);
    }
}
