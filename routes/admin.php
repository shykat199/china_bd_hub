<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    abort(404);
});


Route::group(['prefix' => 'admin', 'namespace' => 'Backend', 'middleware' => 'auth:admin', 'as' => 'backend.'], function () {
    Route::group(['middleware' => ['check_permission']], function () {
        Route::get('withdraws/approved', 'WithdrawController@approved')->name('withdraws.approved');
        Route::get('withdraws/reject', 'WithdrawController@reject')->name('withdraws.reject');
        Route::resource('withdraws', WithdrawController::class)->only('index', 'show', 'destroy');
        Route::get('withdraws-datas', 'WithdrawController@withdrawDatas')->name('withdraws.data');

        Route::resource('commissions', CommissionController::class)->only('index', 'update');
        Route::resource('stocks', StockController::class)->only('index', 'show');

        // Website settings
        Route::resource('contact-infos', ContactInfoController::class)->except('create', 'show', 'edit');

        //Notifications manager
        Route::prefix('notifications')->controller(NotifyController::class)->name('notifications.')->group(function () {
            Route::get('/','mtIndex')->name('index');
            Route::get('/{id}','mtView')->name('mtView');
            Route::get('view/all/','mtReadAll')->name('mtReadAll');
        });
    });
});

Route::post('/products/bulk-delete', [\App\Modules\Backend\PromotionManagement\Http\Controllers\PromotionalProductController::class, 'bulkDelete'])
    ->name('products.bulkDelete');

Route::post('/categories/bulk-delete', [\App\Modules\Backend\PromotionManagement\Http\Controllers\PromotionalProductController::class, 'bulkDeleteCategory'])
    ->name('backend.categories.bulk-delete');

Route::post('/brands/bulk-delete', [\App\Modules\Backend\PromotionManagement\Http\Controllers\PromotionalProductController::class, 'bulkDeleteBrands'])
    ->name('backend.brands.bulk-delete');

Route::post('email_subscriber/bulk-delete', [\App\Modules\Backend\CustomerManagement\Http\Controllers\EmailSubscriberController::class, 'bulkDelete'])
    ->name('backend.subscriberBulkDelete');

Route::post('email_subscriber/bulk-delete', [\App\Modules\Backend\CustomerManagement\Http\Controllers\EmailSubscriberController::class, 'bulkDelete'])
    ->name('backend.subscriberBulkDelete');

Route::post('suspend-user/bulk-delete', [\App\Modules\Backend\CustomerManagement\Http\Controllers\CustomerController::class, 'bulkDelete'])
    ->name('backend.suspendUserBulkDelete');

Route::post('bulk-delete-stock', [\App\Http\Controllers\Backend\StockController::class, 'bulkDelete'])
    ->name('backend.stockBulkDelete');
