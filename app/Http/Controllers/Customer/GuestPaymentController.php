<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\NotifyHelper;
use App\Mail\OrderPending;
use App\Models\Backend\Admin;
use App\Modules\Backend\CustomerManagement\Entities\Customer;
use App\Modules\Backend\SellerManagement\Entities\Seller;
use Illuminate\Http\Request;
use App\Models\Frontend\Order;
use App\Models\Frontend\Product;
use App\Models\Frontend\CartItem;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Frontend\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\OrderTimeline;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Frontend\ShippingAddressController;
use App\Http\Controllers\Frontend\UserBillingInfoController;
use App\Models\Frontend\Coupon;
use App\Models\Frontend\CouponUsage;
use App\Models\Frontend\User;
use App\Models\Productstock;

class GuestPaymentController extends Controller
{
    use NotifyHelper;

    public function __construct()
    {
        //$this->middleware('auth:customer');
    }

//    public function index(Request $request)
//    {
//        $this->validate($request, [
//            'first_name' => 'required|max:100',
//            'mobile' => 'required|string|max:15',
//            'billing_address' => 'required|string|max:250',
//        ]);
//
//        $cart = json_decode(Cookie::get('cart'));
//        if (!$cart) {
//            return response()->json([
//                'message' => __('The cart is empty.'),
//            ], 404);
//        }
//        $user = User::updateOrCreate([
//            'mobile' => $request->mobile
//        ],
//        [
//            'first_name' => $request->first_name,
//            'address' => $request->billing_address,
//            'username' => $request->mobile,
//        ]);
//        $user_id = User::where('mobile', $request->mobile)->value('id');
//        $request['user_id'] = $user_id;
//
//        app(ShippingAddressController::class)->store($request);
//
//        app(UserBillingInfoController::class)->store($request);
//
//        $request['payment_by'] = 'COD';
//        $this->orderStore($request);
//
//        Cookie::queue(Cookie::forget('cart'));
//        Cookie::queue(Cookie::forget('total'));
//        Cookie::queue(Cookie::forget('subTotal'));
//        Cookie::queue(Cookie::forget('coupon_id'));
//        Cookie::queue(Cookie::forget('coupon_infos'));
//        Cookie::queue(Cookie::forget('totalShipping'));
//        Cookie::queue(Cookie::forget('coupon_discount'));
//
//        session()->put('user_id', $user_id);
//        session()->put('customer_mobile', $request->mobile);
//
//        return response()->json([
//            'message' => __('Order place successfully.'),
//            'redirect' => route('customer.order-success')
//        ]);
//    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:100',
            'mobile' => 'required|string|max:15',
            'billing_address' => 'required|string|max:250',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'message' => __('The cart is empty.'),
            ], 404);
        }
        $adminAuthUser = auth()->guard('admin')->user();
        $customerAuthUser = auth()->guard('customer')->user();
        $sellerAuthUser = auth()->guard('seller')->user();

        if ($adminAuthUser) {
            $user = \App\Models\Backend\Admin::where('id', $adminAuthUser->id)->first();
        } elseif ($customerAuthUser) {
            $user = \App\Models\Frontend\User::where('id', $customerAuthUser->id)->first();
        } elseif ($sellerAuthUser) {
            $user = \App\Models\Frontend\Seller::where('id', $sellerAuthUser->id)->first();
        } else {
            $user = User::updateOrCreate(
                ['mobile' => $request->mobile],
                [
                    'first_name' => $request->first_name,
                    'address' => $request->billing_address,
                    'username' => $request->mobile,
                ]
            );
        }

        $user_id = $user->id;
        $request['user_id'] = $user_id;

        // Store shipping & billing info
        app(ShippingAddressController::class)->store($request);
        app(UserBillingInfoController::class)->store($request);

        // Payment method
        $request['payment_by'] = 'COD';

        // Store order
        $this->orderStore($request);

        /* ===========================
           CLEAR SESSION CART DATA
        =========================== */
        session()->forget([
            'cart',
            'total',
            'subTotal',
            'coupon_id',
            'coupon_infos',
            'totalShipping',
            'coupon_discount',
        ]);

        // Save user info in session
        session()->put('user_id', $user_id);
        session()->put('customer_mobile', $request->mobile);

        return response()->json([
            'message' => __('Order place successfully.'),
            'redirect' => route('customer.order-success')
        ]);
    }

//    public function orderStore(Request $request)
//    {
//        $cart = json_decode(Cookie::get('cart'));
//
//        $name = $request->first_name;
//
//        $order_no = Order::latest()->first()->order_no ?? 1000;
//        $order_no = substr($order_no, 3);
//        $order_no = 'INV' . ($order_no + 1);
//        $subTotal = Cookie::get('subTotal');
//        $total_discount = Cookie::get('total_discount') + (Cookie::get('coupon_discount') ?? 0);
//
//        if ($request->get('email') != null) {
//            try {
//                Mail::to(auth('customer')->user()->email)->send(new OrderPending(['request' => $request, 'name' => $name, 'order_no' => $order_no, 'subTotal' => $subTotal, 'cart' => $cart]));
//            } catch (\Swift_TransportException $e) {
//                Session::flash('error', $e->getMessage());
//            }
//        }
//
//        /** store product details in database */
//        $data = [
//            'order_no' => $order_no,
//            'discount' => $total_discount,
//            'vat' => Cookie::get('total_vat') ?? 0,
//            'coupon_discount' => Cookie::get('coupon_discount'),
//            'shipping_cost' => Cookie::get('totalShipping'),
//            'total_price' => Cookie::get('subTotal'),
//            'coupon_id' => Cookie::get('coupon_id'),
//            'shipping_name' => $name,
//            'shipping_address_1' => $request->billing_address,
//            'shipping_address_2' => $request->billing_address,
//            'shipping_mobile' => $request->mobile,
//            'payment_by' => $request->payment_by,
//            'user_id' => $request->user_id,
//            'user_first_name' => $name,
//            'user_address_1' => $request->address_line_one,
//            'user_mobile' => $request->mobile,
//            //'user_email' => auth('customer')->user()->email,
//        ];
//
//        $order = Order::create($data + [
//                'payment_status' => $request->get('payment_by') == 'COD' ? 'unpaid' : 'paid',
//            'paid_amount' => $request->get('payment_by') == 'COD' ? 0 : $request->paid_amount,
//            'meta' => [
//                'bank' => $request->bank,
//                'transaction_id' => $request->transaction_id,
//            ]
//        ]);
//
//        session()->put('order_id', $order->id);
//        $coupon_id= Cookie::get('coupon_id');
//        if (!empty($coupon_id)){
//            CouponUsage::create(['user_id'=>$data['user_id'],'coupon_id'=>$data['coupon_id']]);
//
//            $couponIfo = Coupon::where('id',$order->coupon_id)->first();
//            if ($couponIfo->type=='product'){
//                $couponproductIds = json_decode($couponIfo->details)->product_id;
//            }
//        }
//        foreach ($cart as $item) {
//            $product = Product::query()->findOrFail($item->id);
//
//            if ($product->is_manage_stock && $product->quantity < $item->quantity) {
//                continue;
//            }
//            if (!empty($coupon_id) && $couponIfo->type=='product'){
//                if (in_array($item->id,$couponproductIds)){
//                    $coupon_discount = ($couponIfo->discount_type == 'percent')
//                        ? (CartItem::price($item->id,$item->quantity) ) * ($couponIfo->discount / 100)
//                        : $couponIfo->discount;
//
//                }else{
//                    $coupon_discount =0;
//                }
//
//            }
//
//            $data = [
//                'seller_id' => $product->seller_id ?? null,
//                'user_id' => $request->user_id,
//                'order_id' => $order->id,
//                'order_stat' => 1,
//                'product_id' => $item->id,
//                'sale_price' => CartItem::price($item->id),
//                'qty' => $item->quantity,
//                'color' => $item->color ?? null,
//                // 'courier' => $item->courier ?? null,
//                'size' => $item->size ?? null,
//                'discount' => $product->discount, // Should be changed, have to calculate with the coupon(indvitual product)
//                'coupon_discount' => $coupon_discount ?? 0,
//                // 'tax' => $item->vat ?? 0,
//                'shipping_cost' => CartItem::shipping($item->id),
//                'total_shipping_cost' => CartItem::shipping($item->id, $item->quantity),
//                'total_price' => CartItem::price($item->id, $item->quantity),
//                'grand_total' => CartItem::price($item->id, $item->quantity) + CartItem::shipping($item->id, $item->quantity),
//                'inside_shipping_days' => CartItem::estimatedShippingDays($item->id),
//            ];
//
//            $details = OrderDetail::create($data);
//
//            $timeline = [
//                'order_detail_id' => $details->id,
//                'order_stat' => 2,
//                'order_stat_desc' => $request->get('order_stat_desc'),
//                'order_stat_datetime' => now(),
//                'user_id' => $request->user_id,
//                'remarks' => '',
//                'product_id' => $item->id,
//            ];
//
//            // Notification to seller
//            // $this->SellerNotification($product->seller_id, $order->id, route('seller.orders.index', ['order' => $order->id]), __('Placed new order.')); // Should be change.
//
//            // Stock Management
//            if (isset($item->size_id) || isset($item->color_id)) {
//                Productstock::where('product_id', $item->id)->where('size_id', $item->size_id)->where('color_id', $item->color_id)->decrement('quantities', $item->quantity);
//            }
//            $product->decrement('quantity', $item->quantity);
//            OrderTimeline::query()->create($timeline);
//        }
//
//        Cookie::queue(Cookie::forget('coupon_discount'));
//        Cookie::queue(Cookie::forget('total_vat'));
//        Cookie::queue(Cookie::forget('shipping'));
//        return $order;
//    }

    public function orderStore(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $cart = json_decode(json_encode(session()->get('cart', [])));

            $name = $request->first_name;

            // Generate order number
            $order_no = Order::latest()->first()->order_no ?? 1000;
            $order_no = substr($order_no, 3);
            $order_no = 'INV' . ($order_no + 1);

            // Totals from SESSION
            $couponInfo = session('coupon_infos');
            $subTotal = session('subTotal', 0);
            $total_discount = session('total_discount', 0) + session('coupon_discount', 0);
            $total_vat = session('total_vat', 0);

            if ($couponInfo){
                $discount = $couponInfo['discount'];
                if ($couponInfo['type'] == 'cart') {

                    if ($couponInfo['discount_type'] == 'percent') {
                        $discount =  ($subTotal * ($discount / 100));
                    }
                }
                else {
                    $cart = json_decode(Cookie::get('cart'), true);
                    $couponProductId = json_decode($couponInfo['details'])->product_id;

                    $eligible = false;
                    $product = null;
                    foreach ($couponProductId as $id) {
                        $exists = array_search($id, array_column($cart, 'id'));
                        if ($exists !== false) {
                            $eligible = true;
                            $product = Product::query()->findOrFail($id);
                        }
                    }

                    if ($eligible) {
                        $price = $product->sale_price;

                        if ($couponInfo['discount_type'] == 'percent') {
                            $discount = $price * ($discount / 100);
                        }
                    } else {
                        $discount = 0;
                    }
                }
            }

            $subTotal = $subTotal - ($discount ?? 0);

            $totalShipping = $request->shipping_cost;

            // Email (optional)
            if ($request->get('email') != null) {
                try {
                    Mail::to(auth('customer')->user()->email)->send(
                        new OrderPending([
                            'request' => $request,
                            'name' => $name,
                            'order_no' => $order_no,
                            'subTotal' => $subTotal,
                            'cart' => $cart
                        ])
                    );
                } catch (\Swift_TransportException $e) {
                    Session::flash('error', $e->getMessage());
                }
            }

            /* ===========================
               CREATE ORDER
            =========================== */
            $data = [
                'order_no' => $order_no,
                'discount' => $total_discount,
                'vat' => $total_vat,
                'coupon_discount' => session('coupon_discount'),
                'shipping_cost' => $totalShipping,
                'total_price' => $subTotal + ($totalShipping ?? 0),
                'coupon_id' => session('coupon_id'),
                'shipping_name' => $name,
                'shipping_address_1' => $request->billing_address,
                'shipping_address_2' => $request->billing_address,
                'shipping_mobile' => $request->mobile,
                'payment_by' => $request->payment_by ?? 'COD',
                'user_id' => $request->user_id ?? 0,
                'user_first_name' => $name,
                'paid_amount' => $subTotal,
                'user_address_1' => $request->address_line_one,
                'user_mobile' => $request->mobile,
            ];

            $order = Order::create($data + [
                    'payment_status' => $request->payment_by === 'COD' ? 'unpaid' : 'paid',
                    'paid_amount' => $request->payment_by === 'COD' ? 0 : $request->paid_amount,
                    'meta' => [
                        'bank' => $request->bank,
                        'transaction_id' => $request->transaction_id,
                    ]
                ]);

            session()->put('order_id', $order->id);

            /* ===========================
               COUPON USAGE
            =========================== */
            $coupon_id = session('coupon_id');

            if (!empty($coupon_id)) {
                CouponUsage::create([
                    'user_id' => $data['user_id'],
                    'coupon_id' => $coupon_id
                ]);

                $couponInfo = Coupon::find($coupon_id);

                if ($couponInfo && $couponInfo->type === 'product') {
                    $couponProductIds = json_decode($couponInfo->details)->product_id ?? [];
                }
            }

            /* ===========================
               ORDER ITEMS
            =========================== */
            foreach ($cart as $item) {
                $product = Product::findOrFail($item->id);

                if ($product->is_manage_stock && $product->quantity < $item->quantity) {
                    continue;
                }

                // Coupon discount per product
                $coupon_discount = 0;
                if (!empty($coupon_id) && isset($couponInfo) && $couponInfo->type === 'product') {
                    if (in_array($item->id, $couponProductIds ?? [])) {
                        $coupon_discount = $couponInfo->discount_type === 'percent'
                            ? CartItem::price($item->id, $item->quantity) * ($couponInfo->discount / 100)
                            : $couponInfo->discount;
                    }
                }

                $details = OrderDetail::create([
                    'seller_id' => $product->seller_id ?? null,
                    'user_id' => $request->user_id ?? 0,
                    'order_id' => $order->id,
                    'order_stat' => 1,
                    'product_id' => $item->id,
                    'sale_price' => CartItem::price($item->id),
                    'qty' => $item->quantity,
                    'color' => $item->color ?? null,
                    'size' => $item->size ?? null,
                    'discount' => $product->discount,
                    'coupon_discount' => $coupon_discount,
                    'shipping_cost' => CartItem::shipping($item->id),
                    'total_shipping_cost' => CartItem::shipping($item->id, $item->quantity),
                    'total_price' => CartItem::price($item->id, $item->quantity),
                    'grand_total' => CartItem::price($item->id, $item->quantity)
                        + CartItem::shipping($item->id, $item->quantity),
                    'inside_shipping_days' => CartItem::estimatedShippingDays($item->id),
                ]);

                // Order timeline
                OrderTimeline::create([
                    'order_detail_id' => $details->id,
                    'order_stat' => 2,
                    'order_stat_desc' => $request->get('order_stat_desc'),
                    'order_stat_datetime' => now(),
                    'user_id' => $request->user_id ?? 0,
                    'remarks' => '',
                    'product_id' => $item->id,
                ]);

                // Stock management
                if (isset($item->size_id) || isset($item->color_id)) {
                    Productstock::where('product_id', $item->id)
                        ->where('size_id', $item->size_id)
                        ->where('color_id', $item->color_id)
                        ->decrement('quantities', $item->quantity);
                }

                $product->decrement('quantity', $item->quantity);
            }

            /* ===========================
               CLEAR SESSION COUPON DATA
            =========================== */
            session()->forget([
                'coupon_discount',
                'total_vat',
                'shipping',
            ]);

            return $order;
        });
    }

    /**
     * Display success message
     *
     * @return View
     */
    public function paymentSuccess(): View
    {
        $msg = trans('Thank you for your payment');
        return view('customer.checkout.payment-success', compact('msg'));
    }

    public function orderSuccess()
    {
        $order = Order::with('items')->findOrFail(session('order_id'));
        session()->forget('order_id');
        return view('customer.checkout.payment-success', compact('order'));
    }
}
