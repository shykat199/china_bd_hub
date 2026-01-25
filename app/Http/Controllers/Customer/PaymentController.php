<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\NotifyHelper;
use App\Mail\OrderPending;
use Illuminate\Http\Request;
use App\Models\Frontend\Order;
use App\Models\Frontend\Product;
use App\Models\Frontend\CartItem;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Frontend\OrderDetail;
use Illuminate\Support\Facades\Mail;
use App\Models\Frontend\OrderTimeline;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Frontend\ShippingAddressController;
use App\Http\Controllers\Frontend\UserBillingInfoController;
use App\Models\Productstock;

class PaymentController extends Controller
{
    use NotifyHelper;

    public function __construct()
    {
        //$this->middleware('auth:customer');
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:100',
            'mobile' => 'required|string|max:15',
            'shipping_address' => 'required|string|max:250',
            'billing_address' => 'required|string|max:250',
            'payment_method' => 'required|string|max:250',
            'bank' => 'required_if:payment_method,mobile_banking',
            'paid_amount' => 'required_if:payment_method,mobile_banking',
            'transaction_id' => 'required_if:payment_method,mobile_banking',
        ]);

        $cart = json_decode(Cookie::get('cart'));
        if (!$cart) {
            return response()->json([
                'message' => __('The cart is empty.'),
            ], 404);
        }

        app(ShippingAddressController::class)->store($request);

        app(UserBillingInfoController::class)->store($request);

        $request['payment_by'] = $request->payment_method;
        $this->orderStore($request);

        Cookie::queue(Cookie::forget('cart'));
        Cookie::queue(Cookie::forget('total'));
        Cookie::queue(Cookie::forget('subTotal'));
        Cookie::queue(Cookie::forget('coupon_id'));
        Cookie::queue(Cookie::forget('coupon_infos'));
        Cookie::queue(Cookie::forget('totalShipping'));
        Cookie::queue(Cookie::forget('coupon_discount'));

        return response()->json([
            'message' => __('Order place successfully.'),
            'redirect' => route('customer.order-success')
        ]);
    }

    public function orderStore(Request $request)
    {
        $cart = json_decode(Cookie::get('cart'));

        $name = $request->first_name;

        $order_no = Order::latest()->first()->order_no ?? 1000;
        $order_no = substr($order_no, 3);
        $order_no = 'INV' . ($order_no + 1);
        $subTotal = Cookie::get('subTotal');
        $total_discount = Cookie::get('total_discount') + (Cookie::get('coupon_discount') ?? 0);

        if ($request->get('email') != null) {
            try {
                Mail::to(auth('customer')->user()->email)->send(new OrderPending(['request' => $request, 'name' => $name, 'order_no' => $order_no, 'subTotal' => $subTotal, 'cart' => $cart]));
            } catch (\Swift_TransportException $e) {
                Session::flash('error', $e->getMessage());
            }
        }

        /** store product details in database */
        $data = [
            'order_no' => $order_no,
            'discount' => $total_discount,
            'vat' => Cookie::get('total_vat') ?? 0,
            'coupon_discount' => Cookie::get('coupon_discount'),
            'shipping_cost' => Cookie::get('totalShipping'),
            'total_price' => Cookie::get('subTotal'),
            'coupon_id' => Cookie::get('coupon_id'),
            'shipping_name' => $name,
            'shipping_address_1' => $request->shipping_address,
            'shipping_address_2' => $request->billing_address,
            'shipping_mobile' => $request->mobile,
            'payment_by' => $request->get('payment_method'),
            'user_id' => auth('customer')->id(),
            'user_first_name' => $name,
            'user_address_1' => $request->address_line_one,
            'user_mobile' => auth('customer')->user()->mobile,
            'user_email' => auth('customer')->user()->email,
        ];

        $order = Order::create($data + [
                'payment_status' => $request->get('payment_by') == 'COD' ? 'unpaid' : 'paid',
            'paid_amount' => $request->get('payment_by') == 'COD' ? 0 : $request->paid_amount,
            'meta' => [
                'bank' => $request->bank,
                'transaction_id' => $request->transaction_id,
            ]
        ]);

        session()->put('order_id', $order->id);
        $coupon_id= Cookie::get('coupon_id');
        if (!empty($coupon_id)){
            CouponUsage::create(['user_id'=>$data['user_id'],'coupon_id'=>$data['coupon_id']]);

            $couponIfo = Coupon::where('id',$order->coupon_id)->first();
            if ($couponIfo->type=='product'){
                $couponproductIds = json_decode($couponIfo->details)->product_id;
            }
        }
        foreach ($cart as $item) {
            $product = Product::query()->findOrFail($item->id);

            if ($product->is_manage_stock && $product->quantity < $item->quantity) {
                continue;
            }
            if (!empty($coupon_id) && $couponIfo->type=='product'){
                if (in_array($item->id,$couponproductIds)){
                    $coupon_discount = ($couponIfo->discount_type == 'percent')
                        ? (CartItem::price($item->id,$item->quantity) ) * ($couponIfo->discount / 100)
                        : $couponIfo->discount;

                }else{
                    $coupon_discount =0;
                }

            }

            $data = [
                'seller_id' => $product->seller_id ?? null,
                'user_id' => auth('customer')->id(),
                'order_id' => $order->id,
                'order_stat' => 1,
                'product_id' => $item->id,
                'sale_price' => CartItem::price($item->id),
                'qty' => $item->quantity,
                'color' => $item->color ?? null,
                // 'courier' => $item->courier ?? null,
                'size' => $item->size ?? null,
                'discount' => $product->discount, // Should be changed, have to calculate with the coupon(indvitual product)
                'coupon_discount' => $coupon_discount ?? 0,
                // 'tax' => $item->vat ?? 0,
                'shipping_cost' => CartItem::shipping($item->id),
                'total_shipping_cost' => CartItem::shipping($item->id, $item->quantity),
                'total_price' => CartItem::price($item->id, $item->quantity),
                'grand_total' => CartItem::price($item->id, $item->quantity) + CartItem::shipping($item->id, $item->quantity),
                'inside_shipping_days' => CartItem::estimatedShippingDays($item->id),
            ];

            $details = OrderDetail::create($data);

            $timeline = [
                'order_detail_id' => $details->id,
                'order_stat' => 2,
                'order_stat_desc' => $request->get('order_stat_desc'),
                'order_stat_datetime' => now(),
                'user_id' => auth('customer')->id(),
                'remarks' => '',
                'product_id' => $item->id,
            ];

            // Notification to seller
            // $this->SellerNotification($product->seller_id, $order->id, route('seller.orders.index', ['order' => $order->id]), __('Placed new order.')); // Should be change.

            // Stock Management
            if (isset($item->size_id) || isset($item->color_id)) {
                Productstock::where('product_id', $item->id)->where('size_id', $item->size_id)->where('color_id', $item->color_id)->decrement('quantities', $item->quantity);
            }
            $product->decrement('quantity', $item->quantity);
            OrderTimeline::query()->create($timeline);
        }

        Cookie::queue(Cookie::forget('coupon_discount'));
        Cookie::queue(Cookie::forget('total_vat'));
        Cookie::queue(Cookie::forget('shipping'));
        return $order;
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

        $coupon_infos = Cookie::get('coupon_infos')
            ? json_decode(Cookie::get('coupon_infos'))
            : null;

        if ($coupon_infos) {

            $details = json_decode($coupon_infos->details, true);

            $minBuy       = $details['min_buy'] ?? 0;
            $maxDiscount = $details['max_discount'] ?? null;

            $orderTotal = $order->total_price;
            $discountAmount = 0;

            if ($orderTotal >= $minBuy) {

                if ($coupon_infos->discount_type === 'percent') {
                    $discountAmount = $orderTotal * ($coupon_infos->discount / 100);
                } else {
                    $discountAmount = $coupon_infos->discount;
                }

                $finalTotal = max(0, $orderTotal - $discountAmount);

                $order->update([
                    'discount' => $discountAmount,
                    'total_price'     => $finalTotal,
                ]);
            }
        }

        session()->forget('order_id');
        Cookie::queue(Cookie::forget('coupon_infos'));
        return view('customer.checkout.payment-success', compact('order', 'coupon_infos'));
    }
}
