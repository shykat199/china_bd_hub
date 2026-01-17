<?php

use App\Http\Controllers\Frontend\Auth\VerificationController;
use App\Http\Controllers\Frontend\FrontController;
use App\Http\Controllers\Frontend\MessageController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SellerController;
use App\Http\Controllers\Frontend\BlogController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| In this file we will register all routes for frontend_old. These routes are
| loaded by RouteServiceProvider from Provider folder. These routes are member
| "front" middleware group which is created by author of this project.
|
*/

Route::fallback(function () {
    abort(404);
});

Route::get('/',[FrontController::class,'index']);

/** Pages Routes */
Route::get('brand/{slug}',[FrontController::class,'brand'])->name('frontend.brand');
Route::get('category/{slug}',[FrontController::class,'category'])->name('category');
Route::get('p/{slug}',[FrontController::class,'product'])->name('product');
// Route::get('cart',[FrontController::class,'cart'])->name('cart');

Route::get('page/{url}',[FrontController::class,'page']);
Route::get('best-selling',[FrontController::class,'bestSelling'])->name('frontend.best-selling');
Route::get('new-arrivals',[FrontController::class,'newArrivals'])->name('frontend.new-arrivals');
Route::get('trends',[FrontController::class,'trends'])->name('frontend.trends');
Route::get('brands',[FrontController::class,'brands'])->name('frontend.brands');
Route::get('shop',[FrontController::class,'shop'])->name('frontend.shop');
Route::get('new-shop',[FrontController::class,'newShop'])->name('frontend.new-shop');
Route::get('suggest',[FrontController::class,'suggest'])->name('frontend.suggest');
Route::get('suggest-new',[FrontController::class,'suggestNew'])->name('frontend.suggest-new');

Route::get('seller-product/{slug}',[SellerController::class,'product'])->name('seller.product');
Route::get('seller-profile/{slug}',[SellerController::class,'index'])->name('seller.profile');

Route::get('announcement',[PageController::class,'announcement'])->name('customer.announcement');
Route::get('faq',[PageController::class,'faq'])->name('customer.faq');

Route::get('banner-product/{id}',[FrontController::class,'bannerProduct'])->name('frontend.banner-product');

Route::post('subscribe',[FrontController::class,'subscribe'])->name('subscribe');

/** ajax routes */
Route::post('/ajax-filter',[FrontController::class,'ajaxFilter'])->name('frontend.ajax-filter');
Route::post('change-currency',[FrontController::class,'changeCurrency'])->name('frontend.change-currency');
Route::post('change-language',[FrontController::class,'changeLanguage'])->name('frontend.change-language');
/** ajax routes */

/** Chat routs start */
Route::post('send-to-seller',[FrontController::class,'sendToSeller'])->name('frontend.send-to-seller');
Route::post('message/load',[MessageController::class,'load'])->name('frontend.message.load');
/** Chat routs end */

Route::post('deal-of-the-week',[FrontController::class,'dealOfTheWeek'])->name('deal-of-the-week');

/** blog routs start */
Route::get('/blog',[BlogController::class,'index'])->name('frontend.blog');
Route::get('/blog-details/{slug}',[BlogController::class,'blogDetails'])->name('frontend.blog.details');
Route::post('/blog/comment',[BlogController::class,'blogComment'])->name('frontend.blog.comment');
/** blog routs end */

Route::get('/campaign/{slug}', [FrontController::class, 'campaign'])->name('campaign');
Route::get('/shipping-charge', [FrontController::class, 'shipping_charge'])->name('shipping.charge');
Route::get('cart/remove', [FrontController::class, 'cart_remove'])->name('cart.remove');
Route::get('cart/increment', [FrontController::class, 'cart_increment'])->name('cart.increment');
Route::get('cart/decrement', [FrontController::class, 'cart_decrement'])->name('cart.decrement');
Route::get('cart/update', [FrontController::class, 'cart_update'])->name('cart.update');
Route::get('/cart/change-product', [FrontController::class, 'changeProduct'])->name('cart.changeProduct');



Route::fallback(function(){
    /** Display custom 404 page from custom location */
    return view('frontend.errors.404');
});

/** Utility Routes */
Route::get('/clear-all',function(){
    Artisan::call('optimize:clear');
    return Artisan::output();
});

Route::get('/clear-view',function(){
    Artisan::call('view:clear');
    return Artisan::output();
});

Route::get('/clear-currency',function(){
    Cookie::queue(Cookie::forget('currency'));
});

Route::get('/clear-cart',function(){
    Cookie::queue(Cookie::forget('cart'));
});

Route::get('/clear-shipping',function(){
    Cookie::queue(Cookie::forget('shipping'));
});

Route::get('/clear-subtotal',function(){
    Cookie::queue(Cookie::forget('subTotal'));
});

Route::get('email/verify-notice',[VerificationController::class,'show'])->name('customer.verification.notice');

Route::get('email/verify/resend',[FrontController::class,'resend'])->name('verification.email.resend');

Route::get('migrate',function(){
    Artisan::call('migrate');
});

Route::get('symbolic-link',function(){
    Artisan::call('storage:link');
});

/**
 * <p>This command will drop all of your tables
 * and data. Dropped tables will never be recovered.</p>
 * <p>We have created this route for project development
 * and test purpose. Do not use this route in real project.</p>
*/
Route::get('reset-project',function(){
   Artisan::call('migrate:fresh --seed');
});

// added for new arrivals
Route::get('/new-arrivals', [FrontController::class, 'new_arrival'])->name('frontend.new.arrival');
