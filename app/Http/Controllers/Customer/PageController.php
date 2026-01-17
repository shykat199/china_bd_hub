<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Backend\Country;
use App\Models\Frontend\FAQ;
use App\Models\Frontend\ShippingAddress;
use App\Models\Frontend\UserBilling;
use App\Models\ShippingArea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PageController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:customer');
    }

    /**
     * Display checkout page
     *
     * @return View
     */
    public function checkout()
    {
        $cart = Cookie::get('cart');
        $carts = json_decode($cart);

        if (!$carts) return back()->with('error', __('Your cart is empty!'));

        $billing = UserBilling::query()
            ->where('user_id', auth('customer')->id())
            ->where('is_active', 1)
            ->first();

        $shipping = ShippingAddress::query()
            ->where('user_id', auth('customer')->id())
            ->latest()
            ->first();

        return view('customer.checkout.checkout',compact('carts', 'billing', 'shipping'));
    }

//    public function checkoutGuest()
//    {
//        $cart = Cookie::get('cart');
//        $carts = json_decode($cart);
//        $products = \Illuminate\Support\Facades\DB::table('products')->get();
//
//        if (!$carts) return back()->with('error', __('Your cart is empty!'));
//
//        $billing = UserBilling::query()
//            ->where('user_id', auth('customer')->id())
//            ->where('is_active', 1)
//            ->first();
//
//        $shipping = ShippingAddress::query()
//            ->where('user_id', auth('customer')->id())
//            ->latest()
//            ->first();
//            $shipping_areas = ShippingArea::where('status', 1)->orderBy('id', 'asc')->get();
//
//        return view('customer.checkout.checkout_guest',compact('carts', 'billing', 'shipping','shipping_areas', 'products'));
//    }

    public function checkoutGuest()
    {
        // 🔁 Get cart from SESSION
        $carts = session()->get('cart', []);

        if (empty($carts)) {
            return back()->with('error', __('Your cart is empty!'));
        }

        $products = \Illuminate\Support\Facades\DB::table('products')->get();

        $billing = UserBilling::query()
            ->where('user_id', auth('customer')->id())
            ->where('is_active', 1)
            ->first();

        $shipping = ShippingAddress::query()
            ->where('user_id', auth('customer')->id())
            ->latest()
            ->first();

        $shipping_areas = ShippingArea::where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        $carts = json_decode(json_encode(session()->get('cart', [])));

        return view(
            'customer.checkout.checkout_guest',
            compact('carts', 'billing', 'shipping', 'shipping_areas', 'products')
        );
    }

    /**
     * Display Announcement Page
     *
     * @return View
     */
    public function announcement(): View
    {
        return view('customer.pages.announcement');
    }

    /**
     * Display FAQ Page
     *
     * @return View
     */
    public function faq(): View
    {
        $faqs = FAQ::query()->where('is_active',1)->paginate(10);
        return view('customer.pages.faq',compact('faqs'));
    }


    // search-product to add in checkout page
    public function search_product(Request $request)
    {
        $keyword = $request->name;
        $products =  \Illuminate\Support\Facades\DB::table('products')
        ->where('name', 'like', '%' . $keyword . '%')
        ->get();

        return response()->json($products);
    }

}
