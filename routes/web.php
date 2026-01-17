<?php

use App\Models\Frontend\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\NoticeController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\Auth\SellerRegisterController;
use App\Http\Controllers\PopUpController;
use App\Http\Controllers\Backend\CampaignController;
//use App\Http\Controllers\Backend\ShippingArieaController;
use App\Http\Controllers\Backend\GoogleTagManagerController;
use App\Http\Controllers\Backend\EcomPixelController;
use App\Http\Controllers\Backend\PaymentGatewaysController;
use App\Http\Controllers\Backend\SmsGatewaysController;
use App\Http\Controllers\Backend\CourierApisController;

Route::get('/test', function () {
    return menubars();
    // return auth('seller')->user();
    // return Cookie::get('cart');
    // Product::whereIn('id', [356, 355])->decrement('unit_price', 10);
});

Route::fallback(function () {
    abort(404);
});


/*  route */
Route::group(['prefix' => 'admin', 'namespace' => 'Backend', 'as' => 'backend.'], function () {
// Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    Route::group(['middleware' => 'auth:admin'], function () {

        Route::get('account', 'AccountController@show')->name('account');
        Route::put('account/{id}', 'AccountController@update')->name('account.update');
        Route::get('announcement_list', 'AnnouncementsController@announcementsList')->name('announcements.list');
        Route::get('best_selling_product', 'HomeController@bestSellingProducts')->name('best_selling_products');
        Route::get('best_customers', 'HomeController@bestCustomers')->name('best_customers');
        Route::get('new_orders', 'HomeController@newOrders')->name('new_orders');
        Route::get('monthly_sale', 'HomeController@monthlySale')->name('monthly_sale');
        Route::get('best_selling_category', 'HomeController@bestSellingProductCategory');
        Route::get('banner_list', 'BannerController@bannerList')->name('banner.list');
        Route::get('product_review_list', 'ProductReviewController@productReviewList')->name('product_review.list');
        Route::get('role_list', 'RoleController@roleList')->name('role.list');
        Route::get('user_list', 'UserController@userList')->name('user.list');
        Route::get('permission_list', 'PermissionController@permissionList')->name('permission.list');
        Route::get('faq_category_list', 'FaqCategoryController@faqCategoryList')->name('faq_category.list');
        Route::get('faq_subcategory_list', 'FaqSubCategoryController@faqSubCategoryList')->name('faq_subcategory.list');
        Route::get('faq_content_list', 'FaqController@faqContentList')->name('faq_content.list');
        Route::get('currency_list', 'CurrencyController@currencyList')->name('currency.list');

        Route::resource('coupon', 'CouponController')->except('show');
        Route::post('coupon/product-form',[CouponController::class,'product'])->name('coupon.product');
        Route::post('coupon/list',[CouponController::class,'list'])->name('coupon.list');

        Route::get('website_setting/languages',[LanguageController::class,'index'])->name('language.index');
        Route::post('website_setting/language/store',[LanguageController::class,'store'])->name('language.store');
        Route::post('website_setting/language/edit',[LanguageController::class,'edit'])->name('language.edit');
        Route::patch('website_setting/language/{id}/update',[LanguageController::class,'update'])->name('language.update');
        Route::post('website_setting/language/default',[LanguageController::class,'default'])->name('language.default');
        Route::get('website_setting/language/translation/{id}',[LanguageController::class,'translation'])->name('language.translation');
        Route::post('website_setting/language/translate/{id}',[LanguageController::class,'translate'])->name('language.translate');
        Route::delete('website_setting/language/destroy/{id}',[LanguageController::class,'destroy'])->name('language.destroy');

        Route::get('website_setting/notices',[NoticeController::class,'index'])->name('notice.index');
        Route::post('website_setting/notice/store',[NoticeController::class,'store'])->name('notice.store');
        Route::get('website_setting/notice/edit/{id}',[NoticeController::class,'edit'])->name('notice.edit');
        Route::patch('website_setting/notice/{id}/update',[NoticeController::class,'update'])->name('notice.update');
        Route::delete('website_setting/notice/destroy/{id}',[NoticeController::class,'destroy'])->name('notice.destroy');

        Route::get('campaign/manage', [CampaignController::class,'index'])->name('campaign.index');
        Route::get('campaign_list', 'CampaignController@campaignList')->name('campaign.list');
        Route::get('campaign/{id}/show', [CampaignController::class,'show'])->name('campaign.show');
        Route::get('campaign/create', [CampaignController::class,'create'])->name('campaign.create');
        Route::post('campaign/save', [CampaignController::class,'store'])->name('campaign.store');
        Route::get('campaign/{id}/edit', [CampaignController::class,'edit'])->name('campaign.edit');
        Route::post('campaign/update', [CampaignController::class,'update'])->name('campaign.update');
        Route::post('campaign/inactive', [CampaignController::class,'inactive'])->name('campaign.inactive');
        Route::post('campaign/active', [CampaignController::class,'active'])->name('campaign.active');
        Route::delete('campaign/destroy', [CampaignController::class,'destroy'])->name('campaign.destroy');
        Route::get('campaign/image/destroy', [CampaignController::class,'imgdestroy'])->name('campaign.image.destroy');


        Route::get('tag-manager/manage', [GoogleTagManagerController::class,'index'])->name('tagmanagers.index');
        Route::get('tag-manager/{id}/show', [GoogleTagManagerController::class,'show'])->name('tagmanagers.show');
        Route::get('tag-manager/create', [GoogleTagManagerController::class,'create'])->name('tagmanagers.create');
        Route::post('tag-manager/save', [GoogleTagManagerController::class,'store'])->name('tagmanagers.store');
        Route::get('tag-manager/{id}/edit', [GoogleTagManagerController::class,'edit'])->name('tagmanagers.edit');
        Route::post('tag-manager/update', [GoogleTagManagerController::class,'update'])->name('tagmanagers.update');
        Route::post('tag-manager/inactive', [GoogleTagManagerController::class,'inactive'])->name('tagmanagers.inactive');
        Route::post('tag-manager/active', [GoogleTagManagerController::class,'active'])->name('tagmanagers.active');
        Route::post('tag-manager/destroy', [GoogleTagManagerController::class,'destroy'])->name('tagmanagers.destroy');

        Route::get('pixels/manage', [EcomPixelController::class,'index'])->name('pixels.index');
        Route::get('pixels/{id}/show', [EcomPixelController::class,'show'])->name('pixels.show');
        Route::get('pixels/create', [EcomPixelController::class,'create'])->name('pixels.create');
        Route::post('pixels/save', [EcomPixelController::class,'store'])->name('pixels.store');
        Route::get('pixels/{id}/edit', [EcomPixelController::class,'edit'])->name('pixels.edit');
        Route::post('pixels/update', [EcomPixelController::class,'update'])->name('pixels.update');
        Route::post('pixels/inactive', [EcomPixelController::class,'inactive'])->name('pixels.inactive');
        Route::post('pixels/active', [EcomPixelController::class,'active'])->name('pixels.active');
        Route::post('pixels/destroy', [EcomPixelController::class,'destroy'])->name('pixels.destroy');

        // paymentgeteway
        Route::get('paymentgeteway/manage', [PaymentGatewaysController::class,'pay_manage'])->name('paymentgeteway.manage');
        Route::post('paymentgeteway/save', [PaymentGatewaysController::class,'pay_update'])->name('paymentgeteway.update');

        // smsgeteway
        Route::get('smsgeteway/manage', [SmsGatewaysController::class,'sms_manage'])->name('smsgeteway.manage');
        Route::post('smsgeteway/save', [SmsGatewaysController::class,'sms_update'])->name('smsgeteway.update');

        // courierapi
        Route::get('courierapi/manage', [CourierApisController::class,'courier_manage'])->name('courierapi.manage');
        Route::post('courierapi/save', [CourierApisController::class,'courier_update'])->name('courierapi.update');

        Route::group(['middleware' => ['check_permission']], function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::resource('roles', 'RoleController')->except(['show']);
            Route::get('user/changeStatus', 'UserController@changeStatus');
            Route::resource('users', 'UserController')->except(['show']);
            Route::resource('permissions', 'PermissionController')->except(['show']);
            Route::get('role_permissions', 'RoleController@rolePermissions');
            /* contents routes */
            Route::get('banner/changeStatus', 'BannerController@changeStatus');
            Route::resource('banners', 'BannerController')->except('show');
            Route::get('product_review/changeStatus', 'ProductReviewController@changeStatus');
            Route::resource('product_review', 'ProductReviewController')->except('create', 'store');
            /* faq routes */
            Route::get('faq_category/changeStatus', 'FaqCategoryController@changeStatus');
            Route::resource('faq_category', 'FaqCategoryController')->except('show');
            Route::get('faq_subcategory/changeStatus', 'FaqSubCategoryController@changeStatus');
            Route::resource('faq_subcategory', 'FaqSubCategoryController')->except('show');
            Route::get('faq_contents/changeStatus', 'FaqController@changeStatus');
            Route::resource('faq_content', 'FaqController');
            /* report routes */
            Route::get('stock_report_list', 'ReportController@stockReportList')->name('stock_report.list');
            Route::get('stock_report', 'ReportController@stockReport')->name('stock_report');
            Route::get('seller_report', 'ReportController@sellerReport')->name('seller_report');
            Route::get('seller_report_list', 'ReportController@sellerReportList')->name('seller_report.list');
            Route::get('customer_report', 'ReportController@customerReport')->name('customer_report');
            Route::get('customer_report_list', 'ReportController@customerReportList')->name('customer_report.list');
            /* website setting routes */
            Route::get('banner/changeStatus', 'BannerController@changeStatus');
            Route::get('website_setting/header', 'WebsiteSettingController@header')->name('website_setting.header');
            Route::get('website_setting/changeStatus', 'WebsiteSettingController@changeStatus');
            Route::post('website_setting/upload_logo', 'WebsiteSettingController@uploadLogo');
            Route::get('website_setting/page_list', 'WebsiteSettingController@pageList')->name('website_setting.page.list');
            Route::get('website_setting/pages', 'WebsiteSettingController@pages')->name('website_setting.pages');
            Route::get('website_setting/pages/{id}/edit', 'WebsiteSettingController@pageEdit')->name('website_setting.pages.edit');
            Route::put('website_setting/pages/{id}', 'WebsiteSettingController@pageUpdate')->name('website_setting.pages.update');
            Route::get('website_setting/changeMenuStatus', 'WebsiteSettingController@changeMenuStatus');
            Route::get('website_setting/appearance', 'WebsiteSettingController@appearance')->name('website_setting.appearance');
            Route::resource('website_setting/announcements', AnnouncementsController::class, ['names' => 'announcements']);
            Route::get('announcements/changeStatus', 'AnnouncementsController@changeStatus');
            Route::put('website_setting/appearance/{id}', 'WebsiteSettingController@appearanceUpdate')->name('website_setting.appearance.update');

            Route::get('popup_setting', [PopUpController::class, 'create'])->name('website_setting.popup');
            Route::post('popup_setting', [PopUpController::class, 'store'])->name('website_setting.popup.create');
            Route::put('popup_setting/{id}', [PopUpController::class, 'update'])->name('website_setting.popup.update');


            // Route::resource('payment_gateway', PaymentGatewayController::class)->except(['create','store','show','destroy']);
            Route::resource('website_setting/currency', CurrencyController::class, ['names' => 'currency']);
            Route::get('currency/changeStatus', 'CurrencyController@changeStatus');
            Route::prefix('blog')->name('blog.')->group(function () {
                Route::prefix('category')->name('category.')->group(function () {
                    Route::get('/',[BlogCategoryController::class,'index'])->name('index');
                    Route::get('/list',[BlogCategoryController::class,'blogCategoryList'])->name('list');
                    Route::get('/create',[BlogCategoryController::class,'create'])->name('create');
                    Route::post('/store',[BlogCategoryController::class,'store'])->name('store');
                    Route::get('/edit/{id}',[BlogCategoryController::class,'edit'])->name('edit');
                    Route::put('/update/{id}',[BlogCategoryController::class,'update'])->name('update');
                    Route::delete('/destroy/{id}',[BlogCategoryController::class,'destroy'])->name('destroy');
                });
                Route::controller(BlogController::class)->group(function () {
                    Route::get('/','index')->name('index');
                    Route::get('/list','blogList')->name('list');
                    Route::get('/create','create')->name('create');
                    Route::post('/store','store')->name('store');
                    Route::get('/edit/{blog}','edit')->name('edit');
                    Route::put('/update/{blog}','update')->name('update');
                    Route::delete('/destroy/{blog}','destroy')->name('destroy');
                });

            });
            /*shipping Area route..*/
            Route::resource('/shipping_area',ShippingAreaController::class);
        });
    });
});
Route::get('/shipping_area/status',[\App\Http\Controllers\Backend\ShippingAreaController::class,'ShippingAreaStatus'])->name('shipping_area.status');
/* seller route */
Route::group(['prefix' => 'seller', 'namespace' => 'Backend', 'as' => 'seller.'], function () {
    /* seller authentication */
    Route::get('registration',[SellerRegisterController::class,'registration'])->name('registration');
    Route::post('register',[SellerRegisterController::class,'register'])->name('register');
    Route::get('login', 'Auth\SellerLoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\SellerLoginController@login');
    Route::post('logout', 'Auth\SellerLoginController@logout')->name('logout');

// Password Reset Routes...
    Route::get('password/reset', 'Auth\SellerForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\SellerForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\SellerResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\SellerResetPasswordController@reset')->name('password.update');

    Route::group(['middleware' => 'auth:seller'], function () {
        Route::get('account', 'AccountController@show')->name('account');
        Route::put('account/{id}', 'AccountController@update')->name('account.update');
        Route::get('announcement_list', 'AnnouncementsController@announcementsList')->name('announcements.list');
        Route::get('best_selling_product', 'HomeController@bestSellingProducts')->name('best_selling_products');
        Route::get('best_customers', 'HomeController@bestCustomers')->name('best_customers');
        Route::get('new_orders', 'HomeController@newOrders')->name('new_orders');
        Route::get('monthly_sale', 'HomeController@monthlySale')->name('monthly_sale');
        Route::get('best_selling_category', 'HomeController@bestSellingProductCategory');
        Route::get('banner_list', 'BannerController@bannerList')->name('banner.list');
        Route::get('product_review_list', 'ProductReviewController@productReviewList')->name('product_review.list');
        Route::get('earning_list', 'WalletController@earningList')->name('earning.list');
        Route::get('withdraw_earning_list', 'WalletController@withdrawEarningList')->name('withdraw_earning.list');

//        Route::group(['middleware' => ['check_permission']], function () {
            //Route::get('/', 'HomeController@index')->name('home');
            /* contents routes */
            Route::get('banner/changeStatus', 'BannerController@changeStatus');
            Route::resource('banners', 'BannerController')->except('show');
            Route::get('product_review/changeStatus', 'ProductReviewController@changeStatus');
            Route::resource('product_review', 'ProductReviewController')->except('create', 'store');
            /* report routes */
            Route::get('stock_report_list', 'ReportController@stockReportList')->name('stock_report.list');
            Route::get('stock_report', 'ReportController@stockReport')->name('stock_report');
            Route::get('seller_report', 'ReportController@sellerReport')->name('seller_report');
            Route::get('seller_report_list', 'ReportController@sellerReportList')->name('seller_report.list');
            Route::get('customer_report', 'ReportController@customerReport')->name('customer_report');
            Route::get('customer_report_list', 'ReportController@customerReportList')->name('customer_report.list');
            Route::get('earning', 'WalletController@earning')->name('earning');
//        });
    });
});

/**
 * @return void
 */

/* clear all cache */
Route::get('/clear-all', function () {
    Artisan::call('optimize:clear');
    echo Artisan::output();
});

/* migration */
Route::get('/migrate',function(){
    Artisan::call('migrate');
    echo Artisan::output();
});

/* migration rollback */
Route::get('/migrate-rollback',function(){
    Artisan::call('migrate:rollback');
    echo Artisan::output();
});

/* create symbolic link */
Route::get('/symlink', function () {
    Artisan::call('storage:link');
    echo Artisan::output();
});
/* clear view cache */
Route::get('/clear-view-cache', function () {
    Artisan::call('view:clear');
    return 'View Cache Cleared';
});

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/updateapp', function() {
    exec('composer dump-autoload');
});
