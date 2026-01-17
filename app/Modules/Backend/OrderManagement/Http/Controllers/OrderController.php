<?php

namespace App\Modules\Backend\OrderManagement\Http\Controllers;

use App\Http\Controllers\Frontend\ShippingAddressController;
use App\Http\Controllers\Frontend\UserBillingInfoController;
use App\Mail\OrderPending;
use App\Models\Backend\CourierApis;
use App\Models\Courierapi;
use App\Models\Customer;
use App\Models\Frontend\CartItem;
use App\Models\Frontend\OrderTimeline;
use App\Models\Frontend\User;
use App\Models\GeneralSetting;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Productstock;
use App\Models\Shipping;
use App\Models\ShippingArea;
use App\Models\SmsGateway;
use App\Modules\Backend\OrderManagement\Entities\OrderStatus;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Throwable;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Seller\Product;
use App\Models\Seller\Category;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ResponseMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\Backend\WebAppearance;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Backend\OrderManagement\Entities\Order;
use App\Modules\Backend\SellerManagement\Entities\Seller;
use App\Modules\Backend\OrderManagement\Entities\OrderDetail;

class OrderController extends Controller
{
    use ResponseMessage;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function search(Request $request)
    {
        $searchValue = $request->input('navbar_search');
        $order_overview = $this->orderOverview();
        return view('ordermanagement::orders.index', compact('order_overview', 'searchValue'));
    }

    public function createCustomOrder()
    {
        $products = \App\Modules\Backend\ProductManagement\Entities\Product::with('images')->select('id','name','discount','unit_price','sale_price','sku','minimum_qty','quantity')->where(['is_active'=>1])->get();
        $shipping_areas = ShippingArea::where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('ordermanagement::orders.create-order',compact('products','shipping_areas'));
    }

    public function getProduct($id)
    {
        $product = \App\Modules\Backend\ProductManagement\Entities\Product::with('images')
            ->select(
                'id',
                'name',
                'discount',
                'unit_price',
                'sale_price',
                'sku',
                'minimum_qty',
                'quantity',
            )
            ->where('is_active', 1)
            ->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $product->quantity,
            'sale_price' => $product->sale_price,
            'discount' => $product->discount,
            'images' => $product->images->map(function ($img) {
                return  asset('uploads/products/galleries/'.$img->image);
            }),
        ]);
    }

    public function customOrderStore(Request $request)
    {

        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'customer_number' => 'required',
            'address' => 'required',
        ]);
        DB::beginTransaction();

        try {

            $exits_customer = User::where('mobile', $request->customer_number)->select('mobile', 'id')->first();
            if ($exits_customer) {
                $customer_id = $exits_customer->id;
            } else {
                $password = random_int(111111, 999999);
                $user = new User();
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->mobile = $request->mobile;
                $user->password = bcrypt($password);
                $user->is_approve = 1;
                $user->is_active = 1;
                $user->save();
                $customer_id = $user->id;
            }

            $order_no = Order::all()->last()->order_no ?? 1000;
            $order_no = substr($order_no, 3);

            $order = new Order();
            $order->order_no = 'INV' . ($order_no + 1);
            $order->discount = 0;
            $order->shipping_cost = $request->shipping_fee;
            $order->total_price = $request->grand_total;
            $order->payment_status = 1;
            $order->order_status = 2;
            $order->payment_by = 'COD';
            $order->user_id = $exits_customer ? $customer_id : Auth::id();
            $order->shipping_address_1 = $request->address;
            $order->shipping_address_2 = $request->address;
            $order->shipping_mobile = $request->customer_number;
            $order->shipping_name = $request->first_name . ' ' . $request->last_name;
            $order->save();

            $request['user_id'] = $exits_customer ? $customer_id : Auth::id();

            app(ShippingAddressController::class)->store($request);
            app(UserBillingInfoController::class)->store($request);

            $cart = $request->products;

            foreach ($cart as $productId => $item) {

                $product = \App\Modules\Backend\ProductManagement\Entities\Product::findOrFail($productId);


                $qty = (int)$item['qty'];
                $discount = (float)($item['discount'] ?? 0);

                if ($product->is_manage_stock && $product->quantity < $qty) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with($this->product_insufficient_message)
                        ->withErrors([
                            'stock' => "Insufficient stock for {$product->name}. Available: {$product->quantity}, Requested: {$qty}"
                        ]);
                }

                $coupon_discount = 0;

                $salePrice = CartItem::price($productId);
                $totalPrice = ($salePrice * $qty) - $discount;
                $shippingCost = CartItem::shipping($productId);
                $totalShipping = CartItem::shipping($productId, $qty);
                $grandTotal = $totalPrice + $totalShipping;

                $details = OrderDetail::create([
                    'seller_id' => $product->seller_id ?? null,
                    'user_id' => $request->user_id,
                    'order_id' => $order->id,
                    'order_stat' => 1,
                    'product_id' => $productId,
                    'sale_price' => $salePrice,
                    'qty' => $qty,
                    'discount' => $discount,
                    'coupon_discount' => $coupon_discount,
                    'shipping_cost' => $shippingCost,
                    'total_shipping_cost' => $totalShipping,
                    'total_price' => $totalPrice,
                    'grand_total' => $grandTotal,
//                'inside_shipping_days' => CartItem::estimatedShippingDays($productId),
                ]);

                // Order timeline
                OrderTimeline::create([
                    'order_detail_id' => $details->id,
                    'order_stat' => 2,
                    'order_stat_desc' => $request->get('order_stat_desc'),
                    'order_stat_datetime' => now(),
                    'user_id' => $request->user_id,
                    'remarks' => '',
                    'product_id' => $productId,
                ]);

                // Stock management
                if (isset($item->size_id) || isset($item->color_id)) {
                    Productstock::where('product_id', $productId)
                        ->where('size_id', $item->size_id)
                        ->where('color_id', $item->color_id)
                        ->decrement('quantities', $item->quantity);
                }

                $product->decrement('quantity', $qty);
            }

            DB::commit();

            return redirect()
                ->route('backend.orders.index')
                ->with($this->create_success_message);

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateCustomOrder(Request $request, $orderId)
    {
        $request->validate([
            'customer_number' => 'required',
            'address' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $order = Order::with('details')->where('order_no',$orderId)->first();

            /* ============================
             | UPDATE ORDER BASIC INFO
             ============================ */
            $order->shipping_cost = $request->shipping_fee ?? 0;
            $order->total_price  = $request->grand_total;
            $order->shipping_name = $request->shipping_name;
            $order->shipping_address_1 = $request->address;
            $order->shipping_address_2 = $request->address;
            $order->shipping_mobile = $request->customer_number;
            $order->save();

            $submittedProducts = $request->products ?? [];

            /* ============================
             | HANDLE EXISTING ORDER ITEMS
             ============================ */
            foreach ($order->details as $detail) {

                // If product removed from order
                if (!isset($submittedProducts[$detail->id])) {

                    // Restore stock
                    $detail->product->increment('quantity', $detail->qty);

                    $detail->delete();
                    continue;
                }

                $item = $submittedProducts[$detail->id];

                $newQty = (int)$item['qty'];
                $oldQty = (int)$detail->qty;
                $difference = $newQty - $oldQty;

                $product = $detail->product;

                // If qty increased → check stock
                if ($difference > 0 && $product->quantity < $difference) {
                    throw new \Exception(
                        "Insufficient stock for {$product->name}. Available: {$product->quantity}"
                    );
                }

                // Update stock
                if ($difference > 0) {
                    $product->decrement('quantity', $difference);
                } elseif ($difference < 0) {
                    $product->increment('quantity', abs($difference));
                }

                // Update order detail
                $detail->update([
                    'qty' => $newQty,
                    'discount' => $item['discount'] ?? 0,
                    'total_price' => ($detail->sale_price * $newQty) - ($item['discount'] ?? 0),
                    'grand_total' => (($detail->sale_price * $newQty) - ($item['discount'] ?? 0)) + $detail->total_shipping_cost,
                ]);

                unset($submittedProducts[$detail->id]);
            }

            /* ============================
             | HANDLE NEWLY ADDED PRODUCTS
             ============================ */
            foreach ($submittedProducts as $key => $item) {

                // skip invalid rows
                if (!isset($item['product_id'], $item['qty'])) {
                    continue;
                }

                $productId = (int) $item['product_id'];
                $qty = (int) $item['qty'];
                $discount = (float) ($item['discount'] ?? 0);

                $product = Product::findOrFail($productId);

                if ($product->is_manage_stock && $product->quantity < $qty) {
                    throw new \Exception(
                        "Insufficient stock for {$product->name}. Available: {$product->quantity}"
                    );
                }

                $salePrice = CartItem::price($productId);

                OrderDetail::create([
                    'order_id'    => $order->id,
                    'user_id'    => $order->user_id,
                    'order_stat'    => 1,
                    'product_id'  => $productId,
                    'sale_price'  => $salePrice,
                    'qty'         => $qty,
                    'discount'    => $discount,
                    'total_price' => ($salePrice * $qty) - $discount,
                    'grand_total' => ($salePrice * $qty) - $discount,
                ]);

                $product->decrement('quantity', $qty);
            }

            DB::commit();

            return redirect()
                ->route('backend.orders.index')
                ->with($this->update_success_message);

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function order_assign(Request $request){
        $products = Order::whereIn('id', $request->input('order_ids'))->update(['user_id' => $request->user_id]);
        return response()->json(['status'=>'success','message'=>'Order user id assign']);
    }

    public function order_status(Request $request){

        $orders = Order::whereIn('id', $request->input('order_ids'))->get();

        foreach ($orders as $order) {
            $order->order_status = $request->order_status;
            $order->update();
        }

        if($request->order_status == 9){
            $orders = Order::whereIn('id', $request->input('order_ids'))->get();
            foreach($orders as $order){
                $orders_details = OrderDetail::select('id','order_id','product_id')->where('order_id',$order->id)->get();
                foreach($orders_details as $order_details){
                    $product = \App\Modules\Backend\ProductManagement\Entities\Product::select('id','stock')->find($order_details->product_id);
                    $product->quantity -= $order_details->qty;
                    $product->save();
                }
            }
        }
        return response()->json(['status'=>'success','message'=>'Order status change successfully']);
    }

    public function bulk_destroy(Request $request){
        $orders_id = $request->order_ids;

        foreach($orders_id as $order_id){
            $order = Order::where('id',$order_id)->delete();
            $order_details = OrderDetail::where('order_id',$order_id)->delete();
        }
        return response()->json(['status'=>'success','message'=>'Order delete successfully']);
    }

//    public function order_print(Request $request){
//        $orders = Order::whereIn('id', $request->input('order_ids'))->with('details','newOrderStatus','payment','customer')->get();
//        $view = view('frontend.order-invoice', ['orders' => $orders])->render();
//        return response()->json(['status' => 'success', 'view' => $view]);
//    }

    public function order_print(Request $request)
    {
        $orders = Order::with('details','payment','customer')
            ->whereIn('id', $request->order_ids)
            ->get();

        return view('frontend.order-invoice', compact('orders'));
    }

    public function bulk_courier($slug, Request $request)
    {
        $courier_info = CourierApis::where(['status' => 1, 'type' => $slug])->first();


        if ($courier_info) {
            $orders_ids = $request->order_ids;
            $successOrders = [];
            $failedOrders = [];

            foreach ($orders_ids as $order_id) {
                $order = Order::find($order_id);

                if ($order && $request->status == 9 && $order->order_status != 9) {
                    $consignmentData = [
                        'invoice' => $order->order_no,
                        'recipient_name' => $order->shipping ? $order->shipping->name : ($order->shipping_name ? $order->shipping_name : 'Unknown'),
                        'recipient_phone' => $order->shipping ? $order->shipping->phone : ($order->shipping_name ? $order->shipping_mobile : 'N/A'),
                        'recipient_address' => $order->shipping ? $order->shipping->address : ($order->shipping_name ? $order->shipping_address_1 : 'Unknown'),
                        'cod_amount' => $order->total_price
                    ];

                    $client = new Client();
                    try {
                        $baseUrl = rtrim(
                            $courier_info->url ?? env('STEADFAST_BASE_URL'),
                            '/'
                        );

                        $url = $baseUrl . '/create_order';

                        $response = $client->post($url, [
                            'headers' => [
                                'Api-Key'     => trim($courier_info->api_key),
                                'Secret-Key'  => trim($courier_info->secret_key),
                                'Accept'      => 'application/json',
                                'Content-Type'=> 'application/json',
                            ],
                            'json' => $consignmentData,
                            'timeout' => 30,
                        ]);

                        $responseData = json_decode($response->getBody()->getContents(), true);

                        if ($responseData['status'] == 200) {
                            $order->order_status = 9;
                            $order->save();
                            $successOrders[] = [
                                'order_id' => $order_id,
                                'message' => $responseData['message'] ?? 'Order placed successfully'
                            ];
                        } else {
                            $failedOrders[] = [
                                'order_id' => $order_id,
                                'message' => $responseData['message'] ?? 'Failed to place order'
                            ];
                        }
                    } catch (\Exception $e) {
                        // Add to failed orders if there's an exception
                        dd($e->getMessage());
                        $failedOrders[] = [
                            'order_id' => $order_id,
                            'message' => $e->getMessage()
                        ];
                    }
                }
            }

            // Return summary of success and failure
            return response()->json([
                'status' => 'success',
                'message' => 'Your order place to courier successfully',
                'success' => json_encode($successOrders),
                'failed' => json_encode($failedOrders)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Courier information not found.'
            ]);
        }
    }

    public function index(Request $request)
    {
        $searchValue = '';
        $order_overview = Order::count();
        $record = $this->orderList($request);
        $show_data = $record['record'];
        $totalRecords = $record['totalRecords'];
        return view('ordermanagement::orders.index', compact('order_overview', 'searchValue','show_data','totalRecords'));
    }

    public function orderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $keyword = $request->get("keyword");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $searchValue = $request->get('keyword');


        $query = Order::query()
            ->when($request->order != '', function ($q) use ($request) {
                $q->where('id', '>=', $request->order);
            })
            ->latest();

        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
        if (!empty($searchValue)) {
            $query
                ->where('order_no', 'like', '%' . $searchValue . '%')
                ->orWhere('user_first_name', 'like', '%' . $searchValue . '%')
                ->orWhere('user_last_name', 'like', '%' . $searchValue . '%')
                ->orWhere('shipping_address_1', 'like', '%' . $searchValue . '%')
                ->orWhere('total_price', 'like', '%' . $searchValue . '%')
                ->orWhere('discount', 'like', '%' . $searchValue . '%')
                ->orWhere('payment_by', 'like', '%' . $searchValue . '%');
        }
        $records = $query
            ->with('newOrderStatus','details.orderStatus','customer')
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];
    }

    public function returnedOrder()
    {

    }

    /* Process ajax request */
//    public function orderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query()
//            ->when($request->order != '', function ($q) use ($request) {
//                $q->where('id', '>=', $request->order);
//            })
//            ->latest();
//
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where('order_no', 'like', '%' . $searchValue . '%')
//                ->orWhere('user_first_name', 'like', '%' . $searchValue . '%')
//                ->orWhere('user_last_name', 'like', '%' . $searchValue . '%')
//                ->orWhere('shipping_address_1', 'like', '%' . $searchValue . '%')
//                ->orWhere('total_price', 'like', '%' . $searchValue . '%')
//                ->orWhere('discount', 'like', '%' . $searchValue . '%')
//                ->orWhere('payment_by', 'like', '%' . $searchValue . '%');
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $edit_route = route('backend.order.edit.show', $record->order_no);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =   '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//
//                                <li>
//                                    <a class="p-0 action" href="' . $edit_route . '">
//                                        <button title="Edit">
//                                            <svg viewBox="0 0 11 11" xmlns="http://www.w3.org/2000/svg">
//                                                <path d="M8.72031 5.31576C8.48521 5.31576 8.29519 5.50625 8.29519 5.74089V9.1421C8.29519 9.37634 8.1047 9.56722 7.87007 9.56722H1.91801C1.68331 9.56722 1.49289 9.37634 1.49289 9.1421V3.19C1.49289 2.95575 1.68331 2.76487 1.91801 2.76487H5.3192C5.5543 2.76487 5.74432 2.57438 5.74432 2.33975C5.74432 2.10504 5.5543 1.91455 5.3192 1.91455H1.91801C1.21483 1.91455 0.642578 2.4868 0.642578 3.19V9.1421C0.642578 9.84529 1.21483 10.4175 1.91801 10.4175H7.87007C8.57326 10.4175 9.14551 9.84529 9.14551 9.1421V5.74089C9.14551 5.50579 8.95541 5.31576 8.72031 5.31576Z"/>
//                                                <path d="M4.62759 4.9274C4.59785 4.95714 4.57785 4.99497 4.56936 5.03577L4.26879 6.53916C4.25477 6.60884 4.27688 6.68069 4.32702 6.73129C4.36742 6.77169 4.42184 6.79333 4.47758 6.79333C4.49112 6.79333 4.50521 6.79209 4.51923 6.78913L6.02218 6.48856C6.06383 6.48 6.10167 6.46007 6.13101 6.43025L9.49487 3.06645L7.99192 1.5636L4.62759 4.9274Z"/>
//                                                <path d="M10.5329 0.525254C10.1184 0.110723 9.444 0.110723 9.02982 0.525254L8.44141 1.11362L9.94444 2.61652L10.5329 2.02808C10.7336 1.82786 10.8441 1.56084 10.8441 1.27686C10.8441 0.992876 10.7336 0.725864 10.5329 0.525254Z"/>
//                                            </svg>
//                                        </button>
//                                    </a>
//                                </li>
//                            </ul>';
//
//            $data_arr[] = array(
//                "order_no" => '<a title="' . $record->cancel_note . '" href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . ($request->order_no == $record->id ? ' <i class="fa fa-bell text-danger" Area-hidden="true"></i> ' : ' ') . '</a>',
//
//                "user_last_name" => $record->full_name() ?? '',
//                "shipping_address_1" => $record->shipping_address_1 ?? '',
//                "discount" => $record->discount ?? 0,
//                "total_price" => $record->total_price ?? '',
//                "payment_by" => $record->payment_by ?? '',
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for pending orders */

    public function pendingOrder(Request  $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->pendingOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.pending_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function pendingOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->whereHas('details', function ($query) {
            $query->where('order_stat', 1);
        });
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', auth()->id());
                });
        }
        $query
            ->with('orderStatus', 'country','customer')
            ->withSum('details', 'qty');
        $totalRecords = $query->count();
        if (!empty($searchValue)) {
            $query->where(function ($qry) use ($searchValue) {
                $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                $qry->orWhere('created_at', 'like', '%' . $searchValue . '%');
                $qry->orWhere('id', 'like', '%' . $searchValue . '%');
            });
        }
        $records = $query
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);


        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];

    }

    /* Process ajax request */
//    public function pendingOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 1);
//            });
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', auth()->id());
//                });
//        }
//        // Total records
//        $query
//            ->with('orderStatus', 'country')
//            ->withSum('details', 'qty');
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('created_at', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<div class="btn-group rounded-1">
//                                    <a class="text-light bg-success px-1 order-action" data-id="' . $record->id . '" data-status="2" data-content="You want to confirm this, Are you sure?" href="' . route('backend.change-order-status', $record->id) . '">
//                                        <i class="fa-solid fa-circle-check"></i> Confirmed
//                                    </a>
//                                    </form>
//                                    <a class="text-light bg-danger px-1 order-action" data-id="' . $record->id . '" data-status="7" data-content="You want to confirm this, Are you sure?" href="' . route('backend.change-order-status', $record->id) . '">
//                                        <i class="fa-solid fa-circle-xmark"></i> Cancle
//                                    </a>
//                                </div>';
//
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "user_country" => $record->country->name,
//                "details_sum_qty" => $record->details_sum_qty,
//                "created_at" => $record->created_at ? date("d.m.Y", strtotime($record->created_at)) : '',
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for confirmed orders */

    public function confirmedOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->pendingOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.confirmed_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function confirmedOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query
            ->whereHas('details', function ($query) {
                $query->where('order_stat', 2);
            });

        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
        // Get records, also we have included search filter as well
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('payment_by', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
        }
        $records = $query
            ->with('orderStatus', 'customer','customer')
            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];
    }

    /* Process ajax request */
//    public function confirmedOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 2);
//            });
//
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('payment_by', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus', 'customer')
//            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//                             </ul>';
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "created_by" => $record->full_name(),
//                "details_sum_qty" => $record->details_sum_qty,
//                "payment_by" => $record->payment_by,
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for processing orders */

    public function processingOrder(Request  $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->processingOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.processing_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function processingOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->where('order_status', 3);
        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
        // Get records, also we have included search filter as well
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('created_at', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
        }
        $records = $query
            ->with('orderStatus', 'country','customer')
            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];

    }

    /* Process ajax request */
//    public function processingOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 3);
//            });
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('created_at', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus', 'country')
//            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//                             </ul>';
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "user_country" => $record->country->name,
//                "details_sum_qty" => $record->details_sum_qty,
//                "created_at" => $record->created_at ? date("d.m.Y", strtotime($record->created_at)) : '',
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for picked orders */

    public function courierOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->courierOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.courier_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function courierOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->where('order_status', 9);

        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
        // Get records, also we have included search filter as well
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
//            $totalRecordswithFilter = $query->count();
        }
        $records = $query
            ->with('orderStatus', 'country')
            ->withSum('details', 'qty','customer')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];
    }

    public function pickedOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->pickedOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.picked_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function pickedOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->where('order_status', 4);

        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
        // Get records, also we have included search filter as well
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
//            $totalRecordswithFilter = $query->count();
        }
        $records = $query
            ->with('orderStatus', 'country','customer')
            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];
    }

    /* Process ajax request */
//    public function pickedOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 4);
//            });
//
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus', 'country')
//            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//                             </ul>';
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "user_country" => $record->country->name,
//                "details_sum_qty" => $record->details_sum_qty,
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for shipped orders */

    public function shippedOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->shippedOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.shipped_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function shippedOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->where('order_status', 5);
        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        $query
            ->with('orderStatus','customer')
            ->withSum('details', 'qty');

        // Total records
        $totalRecords = $query->count();
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('payment_by', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_address_1', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('total_price', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
        }
        $records = $query
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];
    }

    /* Process ajax request */
//    public function shippedOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 5);
//            });
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        $query
//            ->with('orderStatus')
//            ->withSum('details', 'qty');
//
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('payment_by', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_address_1', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('total_price', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//                             </ul>';
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "details_sum_qty" => $record->details_sum_qty,
//                "payment_by" => $record->payment_by,
//                "user_address_1" => $record->user_address_1,
//                "total_price" => $record->total_price,
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for delivered orders */

    public function deliveredOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->deliveredOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.delivered_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function deliveredOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->where('order_status', 6);
        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query->whereHas('details', function ($query) {
                $query->where('seller_id', 'like', '%' . auth()->id() . '%');
            });
        }
        // Total records
        $totalRecords = $query->count();
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
        }
        $records = $query
            ->with('orderStatus', 'country','customer')
            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];


    }

    /* Process ajax request */
//    public function deliveredOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 6);
//            });
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus', 'country')
//            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//            $action = '';
//            if (auth()->user()->can('delete_orders') || auth()->user()->hasRole('super-admin'))
//                $action =      '<ul>
//                                <li>
//                                     <form user="deleteForm" method="POST"
//                                              action="' . $delete_route . '">
//                                            ' . csrf_field() . method_field("DELETE") . '
//                                            <a class="p-0 action" href="javascript:void(0);"
//                                               onclick="deleteWithSweetAlert(event,parentNode);">
//                                                <button title="Delete">
//                                                    <svg viewBox="0 0 10 11" xmlns="http://www.w3.org/2000/svg">
//                                                        <path d="M8.65184 10.2545C8.63809 10.5288 8.36791 10.7452 8.03934 10.7452H2.31625C1.98768 10.7452 1.7175 10.5288 1.70375 10.2545L1.29504 3.04834H9.06055L8.65184 10.2545ZM3.88317 4.83823C3.88317 4.7234 3.77169 4.63027 3.63416 4.63027H3.23589C3.09844 4.63027 2.98688 4.72338 2.98688 4.83823V8.95529C2.98688 9.07014 3.09835 9.16324 3.23589 9.16324H3.63416C3.77163 9.16324 3.88317 9.07019 3.88317 8.95529V4.83823ZM5.62591 4.83823C5.62591 4.7234 5.51444 4.63027 5.37693 4.63027H4.97866C4.84121 4.63027 4.72968 4.72338 4.72968 4.83823V8.95529C4.72968 9.07014 4.84112 9.16324 4.97866 9.16324H5.37693C5.51441 9.16324 5.62591 9.07019 5.62591 8.95529V4.83823ZM7.36871 4.83823C7.36871 4.7234 7.25724 4.63027 7.11973 4.63027H6.72143C6.58396 4.63027 6.47245 4.72338 6.47245 4.83823V8.95529C6.47245 9.07014 6.58393 9.16324 6.72143 9.16324H7.11973C7.25721 9.16324 7.36871 9.07019 7.36871 8.95529V4.83823Z"/>
//                                                        <path d="M1.0213 1.09395H3.66155V0.677051C3.66155 0.617846 3.71902 0.569824 3.78994 0.569824H6.56248C6.63337 0.569824 6.69083 0.617846 6.69083 0.677051V1.09393H9.33112C9.5436 1.09393 9.71582 1.23779 9.71582 1.41526V2.42468H0.636597V1.41528C0.636597 1.23782 0.808817 1.09395 1.0213 1.09395Z"/>
//                                                    </svg>
//                                                </button>
//                                            </a>
//                                     </form>
//                                </li>
//                             </ul>';
//            $data_arr[] = array(
//                "order_no" => '<a href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "user_country" => $record->country->name,
//                "details_sum_qty" => $record->details_sum_qty,
//                "action" => $action
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /* for cancelled orders */

    public function cancelledOrder(Request $request)
    {
        $segment = strtoupper($request->segment(2));
        $order_status = OrderStatus::where('name',$segment)->withCount('orders')->first();
        $order_overview = $order_status->orders_count;
        $record = $this->cancelledOrderList($request);
        $show_data = $record['record'] ?? [];
        $totalRecords = $record['totalRecords'] ?? 0;
        return view('ordermanagement::orders.cancelled_orders', compact('order_overview','show_data','totalRecords'));
    }

    public function cancelledOrderList($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $request->get('keyword');

        $query = Order::query();
        $query->whereHas('details', function ($query) {
            $query->where('order_stat', 7);
        });

        // specific seller
        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
            $query
                ->whereHas('details', function ($query) {
                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
                });
        }
        // Total records
        $totalRecords = $query->count();
        // Get records, also we have included search filter as well
        if (!empty($searchValue)) {
            $query
                ->where(function ($qry) use ($searchValue) {
                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('user_address_1', 'like', '%' . $searchValue . '%');
                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
                });
        }
        $records = $query
            ->with('orderStatus','customer')
            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
            ->paginate(20);

        return [
            'record' =>$records,
            'totalRecords' =>$totalRecords,
        ];

    }

    /* Process ajax request */
//    public function cancelledOrderList(Request $request)
//    {
//        $draw = $request->get('draw');
//        $start = $request->get("start");
//        $rowperpage = $request->get("length"); // total number of rows per page
//
//        $columnIndex_arr = $request->get('order');
//        $columnName_arr = $request->get('columns');
//        $order_arr = $request->get('order');
//        $search_arr = $request->get('search');
//
//        $columnIndex = $columnIndex_arr[0]['column']; // Column index
//        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
//        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
//        $searchValue = $search_arr['value']; // Search value
//
//        $query = Order::query();
//        $query
//            ->whereHas('details', function ($query) {
//                $query->where('order_stat', 7);
//            });
//
//        // specific seller
//        if (auth('seller')->user() && auth('seller')->user()->getRoleNames()->first() == 'Seller') {
//            $query
//                ->whereHas('details', function ($query) {
//                    $query->where('seller_id', 'like', '%' . auth()->id() . '%');
//                });
//        }
//        // Total records
//        $totalRecords = $query->count();
//        $totalRecordswithFilter = $totalRecords;
//        // Get records, also we have included search filter as well
//        if (!empty($searchValue)) {
//            $query
//                ->where(function ($qry) use ($searchValue) {
//                    $qry->orWhere('order_no', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_first_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_last_name', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('user_address_1', 'like', '%' . $searchValue . '%');
//                    $qry->orWhere('id', 'like', '%' . $searchValue . '%');
//                });
//            $totalRecordswithFilter = $query->count();
//        }
//        $records = $query
//            ->with('orderStatus')
//            ->withSum('details', 'qty')
//            ->orderBy($columnName, $columnSortOrder)
//            ->skip($start)
//            ->take($rowperpage)
//            ->get();
//
//        $data_arr = array();
//
//        foreach ($records as $record) {
//            $show_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.show', $record->id);
//            $delete_route = auth('seller')->user() ? route('seller.orders.show', $record->id) : route('backend.orders.destroy', $record->id);
//
//            $data_arr[] = array(
//                "order_no" => '<a title="' . $record->cancel_note . '" href="' . $show_route . '"><span class="text-primary">' . $record->order_no . '</span>' .
//                    date("d M Y", strtotime($record->created_at)) .
//                    ' at ' . date("h:i A", strtotime($record->created_at)) . '</a>',
//
//                "user_last_name" => $record->full_name(),
//                "details_sum_qty" => $record->details_sum_qty,
//                "user_address_1" => $record->user_address_1,
//
//            );
//        }
//
//        $response = array(
//            "draw" => intval($draw),
//            "iTotalRecords" => $totalRecords,
//            "iTotalDisplayRecords" => $totalRecordswithFilter,
//            "aaData" => $data_arr,
//        );
//
//        return json_encode($response);
//    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $order = Order::with('details.seller', 'orderStatus', 'customer', 'country')->findOrFail($id);
        $website = WebAppearance::findOrFail(1);
        return view('ordermanagement::orders.show', compact('order', 'website'));
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::findOrFail($request->id);
        if ($order) {
            $order->update([
                'cancel_stat' => 1,
                'order_stat' => 7,
                'cancel_datetime' => now(),
                'cancel_note' => $request->note,
                'cancel_by_user' => Auth::id()
            ]);

            return response()->json($this->update_success_message);
        } else {
            return response()->json($this->not_found_message);
        }
    }

    /* update order status */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order) {
            $data = $request->only(['order_stat']);
            if ($data['order_stat'] == 2)
                $data['confirmed_stat'] = 1;
            $data['confirmed_datetime'] = now();
            if ($data['order_stat'] == 3) {
                $data['processing_stat'] = 1;
                $data['processing_datetime'] = now();
            }
            if ($data['order_stat'] == 4) {
                $data['picked_stat'] = 1;
                $data['picked_datetime'] = now();
            }
            if ($data['order_stat'] == 5) {
                $data['shipped_stat'] = 1;
                $data['shipped_datetime'] = now();
            }
            if ($data['order_stat'] == 6) {
                $data['delivered_stat'] = 1;
                $data['delivered_datetime'] = now();
            }

            $order->update($data);
            return redirect()->back()->with($this->update_success_message);
        } else {
            return redirect()->back()->with($this->update_fail_message);
        }
    }

    /* update order status */
    public function updateOrderDetails(Request $request)
    {
        $request->validate([
            'order_stat' => 'required|integer|max:10',
            'order_stat_desc' => 'nullable|string|max:1000',
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'orders_details_id' => 'required|integer|exists:order_details,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order_details = OrderDetail::findOrFail($request->orders_details_id);

        if ($order->payment_status === 'unpaid' && $request->order_stat == 6) {
            $commission = $order_details->sale_price;
            $seller = Seller::find($order_details->seller_id);
            $product = Product::findOrFail($order_details->product_id);
            $cateogry = Category::findOrFail($product->category_id);

            if ($seller) {
                $commission = ($order_details->sale_price / 100) * $cateogry->commission_rate;

                Transaction::create([
                    'user_id' => $seller->id,
                    'amount' => ($order_details->sale_price - $commission),
                    'reason' => 'Sale',
                    'profit_for' => 'seller',
                ]);

                $seller->update([
                    'wallet' => $seller->wallet + ($order_details->sale_price - $commission)
                ]);
            }

            Transaction::create([
                'amount' => $commission,
                'reason' => 'Sale',
                'profit_for' => 'admin',
            ]);

            $order->payment_status = 'paid';
            $order->save();
        }

        $order_details->timelines()->create([
            'user_id' => $order_details->user_id,
            'order_stat' => $request->order_stat,
            'product_id' => $request->product_id,
            'order_stat_desc' => $request->order_stat_desc,
            'order_detail_id' => $request->order_detail_id,
            'order_stat_datetime' => $request->order_stat_datetime,
        ]);

        $order_details->order_stat = $request->order_stat;
        $order_details->save();

        return response()->json([
            'redirect' => url()->previous(),
            'message' => __('Status has been changed successfully.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $order = Order::with('details')->find($id);
            if ($order) {
                if ($order->details()->exists())
                    $order->details()->delete();

                $order->delete();
                if (auth('seller')->user())
                    return redirect()->route('seller.orders.index')->with($this->delete_success_message);
                else
                    return redirect()->route('backend.orders.index')->with($this->delete_success_message);
            } else {
                return back()->with($this->not_found_message);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return back()->with([
                'alert-type' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }

    /* order overview collection */

    public function orderOverview()
    {
        $query = OrderDetail::query();

        // specific seller
        if (auth('seller')->user()) {
            $query->where('seller_id', 'like', '%' . auth()->id() . '%');
        }
        $orders = $query
            ->selectRaw('order_stat, count(*) as total')
            ->groupBy('order_stat')
            ->get();
        $order_overview = [];
        foreach ($orders as $key => $order) {
            $order_overview[$order->order_stat] = $order->total;
        }
        $order_overview['total_order'] = array_sum($order_overview);
        return $order_overview;
    }

    public function changeOrderStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:orders,id',
            'status' => 'required|integer',
        ]);

        $order = Order::findOrFail($request->id);
        $order->details()->update([
            'order_stat' => $request->status,
        ]);

        return response()->json([
            'message' => 'Status updated successfully.',
            'redirect' => url()->previous()
        ]);
    }

    public function orderDetailsSeller($order_id = null, $seller_id = null)
    {
        //$orderDetails = OrderDetail::with('details.seller', 'orderStatus', 'customer', 'country')->findOrFail($id);
        $orderDetails = OrderDetail::with('order')->where('order_id', $order_id)->where('seller_id', $seller_id)->get();
        $website = WebAppearance::findOrFail(1);
        return view('ordermanagement::orders.seller_invoice', compact('orderDetails', 'website'));
    }



    // edit order
    public function edit(string $order_no)
    {
        $products = \App\Modules\Backend\ProductManagement\Entities\Product::with(['images'])->select('id','name','discount','unit_price','sale_price','sku','minimum_qty','quantity')->where(['is_active'=>1])->get();
        $order = Order::with(['details.product.images','customer'])->where('order_no', $order_no)->get()->first();
        $countries = DB::table('countries')->get();
        $shipping_areas = ShippingArea::where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        // foreach($order->details as $key => $product){
        //     dump($product->product->images);
        // }
        // exit;
        return view('ordermanagement::orders.edit_order', compact('order', 'countries','products', 'shipping_areas'));
    }


    // update order
    public function update_order(Request $request)
    {
        // dd($request->all());
        $validated_data = $request->validate([
            'discount' => 'integer',
            'shipping_cost' => 'integer',
            'total_price' => 'integer',
            'exchange_rate' => 'nullable|numeric',
            'paid_amount' => 'numeric',
            'payment_status' => 'string',
            'shipping_name' => 'string',
            'shipping_address_1' => 'string',
            'shipping_address_2' => 'string',
            'shipping_mobile' => 'string',
            'shipping_email' => 'nullable|email',
            'shipping_post' => 'nullable|string',
            'shipping_town' => 'nullable|string',
            'shipping_note' => 'nullable|string',
            'payment_by' => 'required',
            'vat' => 'numeric',
            'paid_amount' => 'required',
            'total_price' => 'required',
            'shipping_address_1' => 'required',
        ]);
        try {
            Order::where('id', $request->order_id)->update($request->except('order_id', '_token', '_method'));

            return redirect()->back()->with('message', 'Order updated successfully');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            Log::error($th->getMessage());
            return back()->with([
                'alert-type' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }


    // delete product from order
    public function order_product_delete(string $id)
    {
        try {
            $order_details = OrderDetail::where('id', $id)->first();
            // OrderDetail::where('id', $id)->delete();
            $order_details->delete();
            $this->UpdateTotal($order_details->order_id);

            return back()->with('message', 'Product from order successfully');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return back()->with([
                'alert-type' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }


    // update product from order
    public function order_product_qtyUpdate(Request $request, string $id)
    {
        // dd($request->all());
        try {

            OrderDetail::where('id', $id)->update(['qty'=> $request->qty]);
            $order_id = OrderDetail::where('id', $id)->first();

            $this->UpdateTotal($order_id->order_id);

            return back()->with('message', 'Product quantity updated successfully');

        } catch (\Throwable $th) {
            // dd($th->getMessage());
            Log::error($th->getMessage());
            return back()->with([
                'alert-type' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }


    // search product to add
    public function order_product_add(Request $request)
    {
        $keyword = $request->name;
        $products = DB::table('products')->where('name', 'like', '%' . $keyword . '%')->get()->toArray();

        return response()->json($products);
    }


    // add product to order

    public function order_add_product(string $order_id, string $product_id)
    {

        try {
            $order_details = OrderDetail::where('order_id', $order_id)->get()->first()->toArray();
            $product = DB::table('products')->find($product_id);
            $order_details["product_id"] = $product->id;
            $order_details["sale_price"] = $product->sale_price;
            $order_details["qty"] = 1;
            $order_details["total_shipping_cost"] = $order_details["qty"] * $order_details["shipping_cost"];
            $order_details["total_price"] = $order_details["qty"] * $order_details["sale_price"];
            $order_details["grand_total"] = $order_details["total_price"] + $order_details["total_shipping_cost"];

            // dd($order_details, $product->sale_price);

            OrderDetail::create($order_details);
            $this->UpdateTotal($order_id);

            return back()->with('message', 'Product added successfully');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            Log::error($th->getMessage());
            return back()->with([
                'alert-type' => 'error',
                'message' => $th->getMessage()
            ]);
        }

    }


    private function UpdateTotal($order_id)
    {
        $getDetails = OrderDetail::where('order_id', $order_id)->get();
        $total = 0;
        foreach ($getDetails as  $v) {
            $total += $v->sale_price * $v->qty;
        }
        Order::where('id', $order_id)->update(['total_price' => $total]);
    }

    public function processOrder($id)
    {
        $order = Order::where(['order_no'=>$id])->with(['details.product.images','orderStatus','customer','details.orderStatus'])->first();
        $orderstatus = OrderStatus::get();
        return view('ordermanagement::orders.process_order', compact('order', 'orderstatus'));
    }

    public function orderProcess(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::withSum('details', 'qty')->with('details')->findOrFail($request->id);

            $previousStatus = $order->order_status;

            // Update order status
            $order->order_status = $request->status;
            $order->save();

            // Update shipping cost
            $shippingFee = 50;
            $order->total_price += ($shippingFee - $order->shipping_cost);
            $order->shipping_cost = $shippingFee;
            $order->save();

            // Create courier order only once
            if ($request->status == 9 && $previousStatus != 9) {

//                ALTER TABLE `courier_apis` ADD `store_id` VARCHAR(255) NULL AFTER `token`;

                $pathaoCourier = CourierApis::where([
                    'status' => 1,
                    'type'   => 'pathao'
                ])->first();

                if ($pathaoCourier) {
                    $consignmentData = [
                        'store_id' => $pathaoCourier->store_id ?? env('PATHAO_STORE_ID'),
                        'merchant_order_id' => $order->order_no ?? 'ORDER-' . time(),
                        'invoice' => $order->order_no,
                        'recipient_name' => $order->shipping_address_1 ?? 'InboxHat',
                        'recipient_phone' => $order->shipping_mobile ?? '01750578495',
                        'recipient_address' => 'Call on the mentioned mobile number ' . $order->shipping_mobile,
                        'delivery_type' => 48,
                        'item_type' => 2,
                        'item_quantity' => (int) ($order->details_sum_qty ?? 1),
                        'item_weight' => 0.5,
                        'amount_to_collect' => $order->total_price,
                    ];

                    $this->createOrder($consignmentData,$pathaoCourier);
                } else {
                    $steadfastOrderData = [
                        'invoice' => $order->order_no ?? 'ORDER-' . time(),
                        'recipient_name' => $order->shipping_name ?? 'Unknown',
                        'recipient_phone' => $order->shipping_mobile ?? '01750578495',
                        'recipient_address' => $order->shipping_address_1 ?? 'Unknown',
                        'cod_amount' => $order->total_price,
                    ];

                   $this->createSteadfastOrder($steadfastOrderData);
                }
            }

            // Reduce stock when status = 6
            if ($request->status == 6) {
                foreach ($order->details as $detail) {
                    $product = \App\Modules\Backend\ProductManagement\Entities\Product::find($detail->product_id);
                    if ($product) {
                        $product->stock = max(0, $product->stock - $detail->qty);
                        $product->save();
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('backend.orders.index')
                ->with('message', 'Order status changed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('message', $e->getMessage());
        }
    }


    public function createOrder($orderData,$pathaoCourier)
    {
        try {
            $baseUrl = $pathaoCourier->url;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $baseUrl."/aladdin/api/v1/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($orderData),
                CURLOPT_HTTPHEADER => [
                    "cache-control: no-cache",
                    "content-type: application/json",
                    'Authorization: Bearer ' . $this->accessPathaoInfo()['access_token'],
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return ['error' => true, 'message' => $err];
            }

            $result = json_decode($response, true);

            if (isset($result['code']) && $result['code'] != 200) {
                return ['error' => true, 'response' => $result];
            }

            return ['error' => false, 'response' => $result];

        } catch (\Exception $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

    public function accessPathaoInfo()
    {
        $courier_info = CourierApis::where(['status' => 1, 'type' => 'pathao'])->first();
        if ($courier_info){
            $curl = curl_init();

            $token_postdata = [
                'client_id'     => $courier_info->api_key,
                'client_secret' => $courier_info->token,
                'username'      => 'aalmahbub89@gmail.com',
                'password'      => 'M@hbub1576',
                'grant_type'    => 'password',
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => $courier_info->url."/aladdin/api/v1/issue-token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($token_postdata),
                CURLOPT_HTTPHEADER => [
                    "cache-control: no-cache",
                    "content-type: application/json",
                ],
            ]);

            $token_response = curl_exec($curl);

            if (curl_errno($curl)) {
                echo 'Curl error: ' . curl_error($curl);
            }

            curl_close($curl);

            return json_decode($token_response, true);
        }

        return null;
    }

    public function createSteadfastOrder(array $order)
    {
        $steadfast = CourierApis::where([
            'status' => 1,
            'type'   => 'steadfast'
        ])->first();

        if (!$steadfast) {
            throw new \RuntimeException('Steadfast courier not configured');
        }

        $payload = [
            'invoice' => $order['invoice'],
            'recipient_name' => $order['recipient_name'],
            'recipient_phone' => $order['recipient_phone'],
            'recipient_address' => $order['recipient_address'],
            'cod_amount' => $order['cod_amount'],
            'note' => $order['note'] ?? 'Handle with care',
            'item_description' => $order['item_description'] ?? 'Ecommerce order',
            'delivery_type' => 0,
        ];

        $response = Http::withHeaders([
            'Api-Key' => $steadfast->api_key,
            'Secret-Key' => $steadfast->secret_key,
            'Content-Type' => 'application/json',
        ])->post(
            'https://portal.packzy.com/api/v1/create_order',
            $payload
        );

        return $response->json();
    }


    public function order_pathao(Request $request)
    {
        $orders_id = $request->order_ids;
        $results = [];

        foreach ($orders_id as $order_id) {

            $order = Order::find($order_id);
            $pathao_info = CourierApis::where(['status' => 1, 'type' => 'pathao'])->first();

            if (!$order || !$pathao_info) {
                continue;
            }

            $tokenData = $this->accessPathaoInfo();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get Pathao access token'
                ], 401);
            }

            $payload = [
                'store_id'            => $request->store_id,
                'merchant_order_id'   => $order->invoice_id,
                'recipient_name'      => optional($order->shipping)->name ?? $order->shipping_name,
                'recipient_phone'     => optional($order->shipping)->phone ?? $order->shipping_mobile,
                'recipient_address'   => optional($order->shipping)->address ?? $order->shipping_address_1,

                // ✅ Correct Pathao keys
                'city_id'             => $request->city_id,
                'zone_id'             => $request->zone_id,
                'area_id'             => $request->area_id,

                'delivery_type'       => 48,
                'item_type'           => 2,
                'item_quantity'       => 1,
                'item_weight'         => '0.5',
                'amount_to_collect'   => round($order->amount),
                'item_description'    => 'Special note- product must be check after delivery',
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $pathao_info->url . "/aladdin/api/v1/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer {$accessToken}"
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $responseData = json_decode($response, true);

            if (isset($responseData['data']['consignment_id'])) {
                $results[] = [
                    'order_id' => $order->id,
                    'tracking_id' => $responseData['data']['consignment_id'],
                    'status' => 'success'
                ];
            } else {
                $results[] = [
                    'order_id' => $order->id,
                    'status' => 'failed',
                    'message' => $responseData['message'] ?? 'Pathao order failed'
                ];
            }

            $order->update([
                'order_status' => 9
            ]);
        }

        return response()->json([
            'status' => 'success',
            'results' => $results
        ]);
    }


}
