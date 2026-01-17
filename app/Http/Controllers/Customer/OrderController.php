<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Frontend\ShippingAddressController;
use App\Http\Controllers\Frontend\UserBillingInfoController;
use App\Http\Traits\ResponseMessage;
use App\Mail\OrderPending;
use App\Models\Productstock;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\SmsGateway;
use App\Modules\Backend\ProductManagement\Entities\ProductDetail;
use Brian2694\Toastr\Facades\Toastr;
use CartItem;
use Illuminate\Http\Request;
use App\Helpers\NotifyHelper;
use App\Models\Frontend\Page;
use App\Models\Frontend\Size;
use App\Models\Frontend\Color;
use App\Models\Frontend\Order;
use App\Models\Frontend\Seller;
use App\Models\Seller\Category;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Frontend\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Frontend\OrderTimeline;
use App\Models\Frontend\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use WpOrg\Requests\Auth;

class OrderController extends Controller
{
    use NotifyHelper;
    use ResponseMessage;

    public function __construct()
    {
        //$this->middleware('auth:customer');
    }

    /**
     * Display order list
     *
     * @return View
     */
    public function index(): View
    {
        $orders = OrderDetail::query()
            ->where('user_id',auth()->id())
            ->paginate(10);

        $stat = 0;

        return view('customer.pages.order',compact('orders','stat'));
    }
    public function gustOrder($mobile=null) : View
    {
        $user_id= session('user_id') ?? User::query()->where('mobile',$mobile)->first()->id;
        $orders = OrderDetail::query()
            ->where('user_id',$user_id)
            ->paginate(10);

        $stat = 0;
        //return $orders;

        return view('customer.pages.order',compact('orders','stat'));
    }

    public function invoice($id)
    {
        $order = Order::query()->findOrFail($id);

        if(Gate::denies('access',$order)){
            abort(401);
        }

        $customer = $order->customer;

        return view('customer.pages.invoice',compact('order','customer'));
    }

    /**
     * Display an ordered item details
     *
     * @param $id
     * @return View
     */
    public function details($id) : View
    {

        $order = OrderDetail::query()->with('seller:id,company_name')->findOrFail($id);
        /* if(Gate::denies('access',$order)){
            abort(401);
        } */

        return view('customer.pages.order-details',compact('order'));
    }

    /**
     * Display the order cancel page
     *
     * @param $id
     * @return View
     */
    public function orderStatusChange(Request $request)
    {
        $request->validate([
            'status' => 'required|integer',
            'order_id' => 'required|integer',
        ]);

        if ($request->status == 7 || $request->status == 8)
        {
            $status = $request->status;
            $order = OrderDetail::query()->findOrFail($request->order_id);

            if ($order->color) {
                $color = Color::where('name', $order->color)->first()->id;
                Session::put('color_id', $color);
            }

            if ($order->size) {
                $size = Size::where('name', $order->size)->first()->id;
                Session::put('size_id', $size);
            }

            // Notification to seller
            $message = $request->status == 7 ? __('Order has been cancled.') : __('Order has been returned.');
            $this->SellerNotification($order->seller_id, $order->order_id, route('seller.orders.index', ['order' => $order->order_id]), $message);

            if(Gate::denies('access',$order)){
                abort(401);
            }
            $cancellationPolicy = Page::query()->where('menu_id',17)->first();
            return view('customer.pages.order-cancel', compact('order','cancellationPolicy', 'status'));
        } else {
            return back()->with('error', __('Unknown status.'));
        }
    }

    /**
     * Display an order list in customer dashboard
     *
     * @param Request $request
     * @return View
     */
    public function list(Request $request): View
    {
        $user_id= session('user_id') ?? User::query()->where('mobile',request('mobile'))->first()->id;
       // $order = OrderDetail::query()->where('user_id',auth('customer')->id());
        $order = OrderDetail::query()->where('user_id', $user_id);

        if($request->has('stat')){
            $stat = $request->get('stat');
            if($stat > 0){
                $order->where('order_stat',$stat);
            }
        }else{
            $stat = 0;
        }

        if($request->has('search')){
            $search = $request->get('search');
            $order->where('order_no','like','%'.$search.'%');
        }

        $orders = $order->latest()->paginate(10);

        return view('customer.pages._order_list',compact('orders','stat'));
    }

    public function cancel(Request $request, $id)
    {
        $is_confirm = $request->get('confirm');

        if(!$is_confirm){
            return redirect()->back();
        }

        $details = OrderDetail::query()->findOrFail($id);
        $order = Order::query()->findOrFail($details->order_id);

        $product = Product::find($details->product_id);
        if ($product->is_manage_stock) {
            $product->quantity = $product->quantity + $details->qty;
            $product->save();
        }

        if ($details->color || $details->size) {
            $stock = Productstock::where('product_id', $product->id)->where('color_id', session('color_id'))->where('size_id', session('size_id'))->first();
            $stock->quantities = $stock->quantities + $details->qty;
            $stock->save();
        }

        if(Gate::denies('access',$order)){
            abort(401);
        }

        if ($order->payment_status === 'paid')
        {
            $product = Product::findOrFail($request->product_id);
            $cateogry = Category::findOrFail($product->category_id);
            $seller = Seller::findOrFail($product->seller_id);

            $commission = ($details->sale_price / 100) * $cateogry->commission_rate;
            $seller->update([
                'wallet' => $seller->wallet - ($details->sale_price - $commission)
            ]);
        }

        $data = [
            'order_detail_id' => $id,
            'user_id' => auth('customer')->id(),
            'product_id' => $request->product_id,
            'order_stat' => $request->order_stat,
            'order_stat_desc' => $request->order_stat_desc,
            'order_stat_datetime' => now(),
            'remarks' => $request->remarks,
        ];

        OrderTimeline::query()->create($data);

        $details->update(['order_stat' => $request->order_stat]);

        return redirect('order');
    }

    public function order_save(Request $request){
        $this->validate($request, [
            'name' => 'required|max:100',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:250',
        ]);

        // Create or update user
        $user = User::updateOrCreate(
            ['mobile' => $request->phone],
            [
                'first_name' => $request->name,
                'address' => $request->address,
                'username' => $request->phone,
            ]
        );

        $user_id = $user->id;
        $request['user_id'] = $user_id;

        // Store shipping & billing info
//        app(ShippingAddressController::class)->store($request);
//        app(UserBillingInfoController::class)->store($request);

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

        return redirect()->back()->with($this->product_order_place_message);

    }

    public function orderStore(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // 🔁 Get cart from SESSION
            $cart = json_decode(json_encode(session()->get('cart', [])));

            $name = $request->name;

            // Generate order number
            $order_no = \App\Modules\Backend\OrderManagement\Entities\Order::latest()->first()->order_no ?? 1000;
            $order_no = substr($order_no, 3);
            $order_no = 'INV' . ($order_no + 1);

            // Totals from SESSION
            $subTotal = $request->subtotal ;
            $total_discount = session('total_discount', 0) + session('coupon_discount', 0);
            $total_vat = session('total_vat', 0);
            $totalShipping = $request->shipping_cost;

            if ($totalShipping) {
                $subTotal += $totalShipping;
            }

            if ($total_discount) {
                $subTotal -= $totalShipping;
            }

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
                }
                catch (\Swift_TransportException $e) {
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
                'total_price' => $subTotal,
                'coupon_id' => session('coupon_id'),
                'shipping_name' => $name,
                'shipping_address_1' => $request->address,
                'shipping_address_2' => $request->address,
                'shipping_mobile' => $request->phone,
                'payment_by' => $request->payment_by ?? 'COD',
                'user_id' => $request->user_id ?? 0,
                'user_first_name' => $name,
                'paid_amount' => 0,
                'user_address_1' => $request->address,
                'user_mobile' => $request->phone,
            ];

            $orderData = $data + [
                    'payment_status' => $request->payment_by === 'COD' ? 'unpaid' : 'paid',
                    'paid_amount' => $request->payment_by === 'COD' ? 0 : $request->paid_amount,
                    'meta' => [
                        'bank' => $request->bank,
                        'transaction_id' => $request->transaction_id,
                    ]
                ];

            $order = \App\Modules\Backend\OrderManagement\Entities\Order::create($orderData);

            /* ===========================
               ORDER ITEMS
            =========================== */
            $product = DB::table('products')->where('id', $request->pId)->first();
            $productDetails = DB::table('product_details')->where('product_id', $request->pId)->first();

            if ($product->is_manage_stock && $product->quantity < $request->quantity) {
                return redirect()->back()->with($this->product_insufficient_message);
            }

            $orderDetails = [
                'seller_id' => $product->seller_id ?? null,
                'user_id' => $request->user_id ?? 0,
                'order_id' => $order->id,
                'order_stat' => 1,
                'product_id' => $product->id,
                'sale_price' => $product->sale_price,
                'qty' => $request->quantity,
                'color' => $request->color ?? null,
                'size' => $request->size ?? null,
                'discount' => $product->discount,
                'coupon_discount' => 0,
                'shipping_cost' => $request->shipping_cost,
                'total_shipping_cost' =>!empty($productDetails) && $productDetails->is_free_shipping == 0 ? ($product->shipping_cost * $request->quantity)  : 0,
                'total_price' => $request->subtotal,
                'grand_total' => $request->totalAmount,
                'inside_shipping_days' => $productDetails->inside_shipping_days ?? '3 to 7 days',
            ];

            $details = OrderDetail::create($orderDetails);

            // Order timeline
            OrderTimeline::create([
                'order_detail_id' => $details->id,
                'order_stat' => 2,
                'order_stat_desc' => $request->get('order_stat_desc'),
                'order_stat_datetime' => now(),
                'user_id' => $request->user_id ?? 0,
                'remarks' => '',
                'product_id' => $product->id,
            ]);

            // Stock management
            if (isset($item->size_id) || isset($item->color_id)) {
                DB::table('productstocks')->where('product_id', $item->id)
                    ->where('size_id', $item->size_id)
                    ->where('color_id', $item->color_id)
                    ->decrement('quantities', $item->quantity);
            }


            DB::table('products')->where('id', $request->pId)->update(
                ['quantity' => $product->quantity - (int)$request->quantity]);


            return $order;
        });
    }
}
