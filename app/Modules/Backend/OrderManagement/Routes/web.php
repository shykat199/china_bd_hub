<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\PathaoController;

Route::get('/pathao/create-store', [PathaoController::class, 'createStoreFlow']);
Route::get('/pathao/cities', [PathaoController::class, 'getCities'])->name('getCities');
Route::get('/pathao/zones/{city_id}', [PathaoController::class, 'getZones'])->name('get-zones');
Route::get('/pathao/areas/{zone_id}', [PathaoController::class, 'getAreas'])->name('areas');
Route::get('/pathao/stores', [PathaoController::class, 'getStores'])->name('pathao.stores');
Route::get('/pathao/orders/{consignment_id}/info', [PathaoController::class, 'getOrderShortInfo']);



Route::group(['prefix' => 'admin', 'as' => 'backend.'], function () {
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::post('search', 'OrderController@search')->name('search');
        Route::get('create-order', 'OrderController@createCustomOrder')->name('create-order');
        Route::get('get-product/{id}', 'OrderController@getProduct')->name('create-order-getProduct');
        Route::post('store-order', 'OrderController@customOrderStore')->name('store-custom-order');
        Route::post('update-order/{id}', 'OrderController@updateCustomOrder')->name('update-custom-order');
        Route::get('order-assign', 'OrderController@order_assign')->name('order-assign');
        Route::get('change-order-status', 'OrderController@order_status')->name('change-order-list-status');
        Route::get('order-bulk-destroy', 'OrderController@bulk_destroy')->name('order-list-bulk_destroy');
        Route::get('multi-order-print', 'OrderController@order_print')->name('multi-order-print');
        Route::get('/page/{slug}', 'OrderController@page')->name('order-list-page');
        Route::get('bulk-courier/{slug}', 'OrderController@bulk_courier')->name('order-bulk_courier');
        Route::get('order_list', 'OrderController@orderList')->name('orders.list');
        Route::get('cancel_order', 'OrderController@cancelOrder')->name('cancel_order');
        Route::get('change-status', 'OrderController@changeOrderStatus')->name('change-order-status');
        Route::get('pending_order_list', 'OrderController@pendingOrderList')->name('pending_orders.list');
        Route::get('confirmed_order_list', 'OrderController@confirmedOrderList')->name('confirmed_orders.list');
        Route::get('processing_order_list', 'OrderController@processingOrderList')->name('processing_orders.list');
        Route::get('picked_order_list', 'OrderController@pickedOrderList')->name('picked_orders.list');
        Route::get('shipped_order_list', 'OrderController@shippedOrderList')->name('shipped_orders.list');
        Route::get('delivered_order_list', 'OrderController@deliveredOrderList')->name('delivered_orders.list');
        Route::get('cancelled_order_list', 'OrderController@cancelledOrderList')->name('cancelled_orders.list');
        Route::put('orders_details', 'OrderController@updateOrderDetails')->name('order_details.update');
        Route::get('orders_details_seller/{order_id}/{seller_id?}', 'OrderController@orderDetailsSeller')->name('order_details_seller');
        Route::get('order-pathao', 'OrderController@order_pathao')->name('bulk-order.pathao');


        // edit order
        Route::get('/order-edit/{id}', 'OrderController@edit')->name('order.edit.show');
        // update order
        Route::put('/order-update', 'OrderController@update_order')->name('order.update');
        // delete product from order
        Route::delete('/order/product-delete/{id}', 'OrderController@order_product_delete')->name('order.product.delete');
        // update qty product from order
        Route::put('/order/product-qtyUpdate/{id}', 'OrderController@order_product_qtyUpdate')->name('order.product.qtyUpdate');
        // search product to add
        Route::get('/order/add-product/search', 'OrderController@order_product_add');
        // product to add
        Route::get('/order/add-product/{order_id}/{product_id}', 'OrderController@order_add_product')->name('order.product.add');

        Route::group(['middleware' => ['check_permission']], function () {
            Route::resource('orders', 'OrderController')->except('create','store', 'edit');
            Route::get('pending', 'OrderController@pendingOrder')->name('pending_orders');
            Route::get('confirmed', 'OrderController@confirmedOrder')->name('confirmed_orders');
            Route::get('processing', 'OrderController@processingOrder')->name('processing_orders');
            Route::get('picked', 'OrderController@pickedOrder')->name('picked_orders');
            Route::get('shipped', 'OrderController@shippedOrder')->name('shipped_orders');
            Route::get('courier', 'OrderController@courierOrder')->name('courier_orders');
            Route::get('delivered', 'OrderController@deliveredOrder')->name('delivered_orders');
            Route::get('cancelled', 'OrderController@cancelledOrder')->name('cancelled_orders');
            Route::get('returned', 'OrderController@returnedOrder')->name('returned_orders');
            Route::get('process_orders/{id}', 'OrderController@processOrder')->name('process_orders');
            Route::post('orders_change', 'OrderController@orderProcess')->name('order_change');
        });
    });

});

Route::group(['prefix' => 'seller', 'as' => 'seller.'], function () {
    Route::group(['middleware' => 'auth:seller'], function () {
        Route::post('search', 'OrderController@search')->name('search');
        Route::get('order_list', 'OrderController@orderList')->name('orders.list');
        Route::get('cancel_order', 'OrderController@cancelOrder')->name('cancel_order');
        Route::get('pending_order_list', 'OrderController@pendingOrderList')->name('pending_orders.list');
        Route::get('confirmed_order_list', 'OrderController@confirmedOrderList')->name('confirmed_orders.list');
        Route::get('processing_order_list', 'OrderController@processingOrderList')->name('processing_orders.list');
        Route::get('picked_order_list', 'OrderController@pickedOrderList')->name('picked_orders.list');
        Route::get('shipped_order_list', 'OrderController@shippedOrderList')->name('shipped_orders.list');
        Route::get('delivered_order_list', 'OrderController@deliveredOrderList')->name('delivered_orders.list');
        Route::get('cancelled_order_list', 'OrderController@cancelledOrderList')->name('cancelled_orders.list');
        Route::put('orders_details', 'OrderController@updateOrderDetails')->name('order_details.update');

        Route::group(['middleware' => ['check_permission']], function () {
            Route::resource('orders', 'OrderController')->except('create','store', 'edit');
            Route::get('pending_orders', 'OrderController@pendingOrder')->name('pending_orders');
            Route::get('confirmed_orders', 'OrderController@confirmedOrder')->name('confirmed_orders');
            Route::get('processing_orders', 'OrderController@processingOrder')->name('processing_orders');
            Route::get('picked_orders', 'OrderController@pickedOrder')->name('picked_orders');
            Route::get('shipped_orders', 'OrderController@shippedOrder')->name('shipped_orders');
            Route::get('delivered_orders', 'OrderController@deliveredOrder')->name('delivered_orders');
            Route::get('cancelled_orders', 'OrderController@cancelledOrder')->name('cancelled_orders');
        });
    });
});

