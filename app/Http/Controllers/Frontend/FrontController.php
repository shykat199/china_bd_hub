<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Backend\Campaign;
use App\Models\Backend\CampaignProduct;
use App\Models\PopUp;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use App\Models\Frontend\Menu;
use App\Models\Frontend\Size;
use App\Models\Frontend\Brand;
use App\Models\Frontend\Color;
use App\Models\Frontend\Banner;
use App\Models\Frontend\Notice;
use App\Models\Frontend\Seller;
use App\Models\Frontend\Message;
use App\Models\Frontend\Product;
use App\Models\Backend\Wholesale;
use App\Models\Frontend\Category;
use App\Models\Frontend\Currency;
use App\Models\Frontend\Language;
use App\Models\Frontend\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Frontend\ProductReview;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\Frontend\EmailSubscriber;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class FrontController extends Controller
{
    /**
     * Display landing page
     *
     * @return View
     */
    public function index(): View
    {
        $brands = Brand::query()
            ->active()
            ->orderBy('order')
            ->take(20)
            ->get();

        /** Category collection section */
        $categories = $this->categories();

        /** show product list by category */
        $shopCategories = Category::query()
            ->with('products.images', 'products.details')
            ->where('is_active', 1)
            ->where('show_in_home', 1)
            ->orderByRaw('cat_order = 0, cat_order ASC')
            ->get();

        /** Promotion Position One */
        $bannerAds = Promotion::query()
            ->with('product.images', 'product.reviews')
            ->eligible()
            ->where('position', 1)
            ->orderByDesc('id')
            ->take(4)
            ->get();

        /** Promotion Position Two */
        // $discounts = Promotion::query()
        //     ->eligible()
        //     ->where('position', 2)
        //     ->orderByDesc('id')
        //     ->take(3)
        //     ->get();

        /** Promotion Position Three */
        $adPoster = Promotion::query()
            ->eligible()
            ->where('position', 3)
            ->orderByDesc('id')
            ->first();

        /** Promotion Position Four */
        $offer = Promotion::query()
            ->eligible()
            ->where('position', 4)
            ->orderByDesc('id')
            ->first();

        /** promotional slider contents queries */
        $banners = Banner::query()
            ->with('category')
            ->where('is_active', 1)
            ->where('publish_stat', 1)
            ->where(function ($q) {
                $q->where('expire_at', '>', now())->orWhere('expire_at', null);
            })
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        /** "Deal of the day" product's query start */
        $allProducts = Product::query()
            ->with('images', 'details', 'reviews')
            ->where('is_active', 1)
            ->orderByRaw('quantity = 0')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $newArrivals = Product::query()
            ->with('images', 'details', 'reviews')
            ->where('is_active', 1)
            ->orderByRaw('quantity = 0')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $bestSellers = Product::query()
            ->with('images', 'details', 'reviews')
            ->where('is_active', 1)
            ->orderByRaw('quantity = 0')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $featureProducts = Product::query()
            ->with('images', 'details', 'reviews')
            ->whereHas('details', function ($q) {
                $q->where('is_featured', 1);
            })
            ->orderByRaw('quantity = 0')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $trends = Product::query()
            ->with('images', 'details', 'reviews')
            ->orderByRaw('quantity = 0')
            ->orderByDesc('id')
            ->take(6)
            ->get();
        /** Deal of the day product's query end */

        $notice = Notice::query()
            ->where('published_at', '<', now())
            ->where('is_active', 1)
            ->latest()
            ->first();

        $pop_up = PopUp::find(1);

        return view('frontend.index', compact('categories', 'shopCategories', 'allProducts', 'newArrivals', 'bestSellers', 'featureProducts', 'trends', 'brands', 'banners', 'adPoster', 'bannerAds', 'offer', 'notice', 'pop_up'));
    }

    public function shop(Request $request)
    {
        // dd($request->all());
        $products = Product::query()
            ->where('is_active', 1)
            ->where('is_manage_stock', 1)
            ->where('name', 'like', "%{$request->q}%")
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END') // stock grouping
            ->orderBy('created_at', 'DESC')
            ->paginate(100);

        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            //->take(7)
            ->get();

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $sizes = Size::query()->where('is_active', 1)->get();

        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();

        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);
        $btn_status = DB::table('bn_cart_button')->find(1);

        $populars = Product::query()->inRandomOrder()->take(4)->get();

        return view('frontend.pages.shop', compact('products', 'categories', 'sizes', 'colors', 'prices', 'populars', 'brands', 'sellers', 'btn_status'));
    }

    public function bannerProduct($id)
    {
        $banner = Banner::query()->findOrFail($id);

        if (!Cookie::has('total_click-' . $id)) {
            $banner->increment('total_click');
            Cookie::queue(Cookie::forever('total_click-' . $id, 'clicked'));
        }

        if ($banner->product_id == 1) {
            return $this->product($banner->product->slug);
        }

        if ($banner->brand_id == 1) {
            return $this->brand($banner->brand->slug);
        }

        if ($banner->category_id == 1) {
            return $this->category($banner->category->slug);
        }
    }

    /**
     * Display individual brand page
     *
     * @param $slug
     * @return View
     */
    public function brand($slug): View
    {
        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            ->take(7)
            ->get();

        $brand = Brand::query()
            ->where('slug', $slug)
            ->firstOr(function () {
                abort(404);
            });

        $title = $brand->name;

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $sizes = Size::query()->where('is_active', 1)->get();

        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();

        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        $products = Product::query()->active()->where('brand_id', $brand->id)->paginate(50);

        $populars = Product::query()->inRandomOrder()->take(4)->get();

        return view('frontend.pages.shop', compact('title', 'brand', 'categories', 'brands', 'sellers', 'sizes', 'colors', 'prices', 'populars', 'products'));
    }

    public function category($slug)
    {
        $categories = $this->categories();

        $category = Category::query()
            ->where('slug', $slug)
            ->firstOr(function () {
                abort(404);
            });

        $title = $category->name;

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            //->take(7)
            ->get();

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $populars = Product::query()
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
            ->orderBy('created_at', 'DESC')
            ->take(4)->get();

        $cats[] = $category->id;
        foreach ($category->subCategories as $category) {
            $cats[] = $category->id;
            foreach ($category->subCategories as $category) {
                $cats[] = $category->id;
            }
        }

        $products = Product::query()
            ->whereIn('category_id', $cats)
            ->where('is_active', 1)
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
            ->orderBy('created_at', 'DESC')
            ->paginate(100);

        $sizes = Size::query()->where('is_active', 1)->get();

        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();

        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        return view('frontend.pages.shop', compact('title', 'category', 'categories', 'sizes', 'colors', 'prices', 'populars', 'products', 'brands', 'sellers'));
    }

    public function product($slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->with(['category.subCategory','images', 'reviews', 'details', 'seller', 'productstock.color', 'productstock.size',
                'productstock', 'video'])
            ->firstOrFail();
        // $variant_images = $product->productstock;
        // dd($variant_images);
        $product->increment('total_viewed');

        $pendingReview = ProductReview::query()
            ->where('publish_stat', NULL)
            ->where('product_id', $product->id)
            ->where('user_id', auth('customer')->id())
            ->exists();

        $similarProducts = Product::query()->with('images', 'reviews')->inRandomOrder()->take(6)->get();
        $wholesales = Wholesale::where('product_id', $product->id)->where('status', 1)->get();
        return view('frontend.pages.product-details-2', compact('product', 'similarProducts', 'pendingReview', 'wholesales'));
    }

    /**
     * Display shopping cart page
     *
     * @return View
     */
    // public function cart(): View
    // {
    //     $cart = Cookie::get('cart');
    //     $carts = json_decode($cart);

    //     return view('frontend.pages.cart', compact('carts'));
    // }

    /**
     * Display cancel message
     *
     * @return View
     */
    public function paymentCancel(): View
    {
        $msg = trans('Alas! Unable to process payment.');
        return view('frontend.pages.payment-cancel', compact('msg'));
    }

    public function page($url)
    {
        $menu = Menu::query()->where('url', 'like', '%' . $url . '%')->first();

        $page = $menu->page;

        if (!$page) {
            return view('frontend.errors.404');
        }

        return view('frontend.pages.blank', compact('page'));
    }

    public function dealOfTheWeek(Request $request)
    {
        $tab = $request->get('tab');

        if ($tab == 'trends') {
            $products = Product::query()->inRandomOrder()->take(6)->get();
        } elseif ($tab == 'new-arrivals') {
            $products = Product::query()
                ->where('is_active', 1)
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        } elseif ($tab == 'best-seller') {
            $products = Product::query()
                ->where('is_active', 1)
                ->inRandomOrder()
                ->take(6)
                ->get();
        } elseif ($tab == 'our-featured') {
            $products = Product::query()
                ->whereHas('details', function ($q) {
                    $q->where('is_featured', 1);
                })
                ->inRandomOrder()
                ->take(6)
                ->get();
        } else {
            $products = Product::query()
                ->where('is_active', 1)
                ->inRandomOrder()
                ->take(6)
                ->get();
        }

        return view('frontend.pages._ajax-deal-of-the-week-products', compact('products'));
    }

    public function bestSelling()
    {
        $title = 'Best Selling';

        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            ->take(7)
            ->get();

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $populars = Product::query()->inRandomOrder()->take(4)->get();

        $products = Product::query()
            ->active()
            ->whereHas('details', function ($q) {
                $q->where('is_best_sell', 1);
            })
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END') // stock grouping
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        $sizes = Size::query()->where('is_active', 1)->get();
        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();
        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        return view('frontend.pages.shop', compact('products', 'title', 'categories', 'sizes', 'colors', 'prices', 'brands', 'sellers', 'populars'));
    }

    public function newArrivals()
    {
        $title = 'New Arrivals';

        $products = Product::query()
            ->active()
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END') // stock grouping
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            ->take(7)
            ->get();

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $populars = Product::query()->inRandomOrder()->take(4)->get();

        $sizes = Size::query()->where('is_active', 1)->get();
        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();
        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        return view('frontend.pages.shop', compact('products', 'categories', 'sizes', 'colors', 'prices', 'brands', 'sellers', 'populars', 'title'));
    }

    public function trends()
    {
        $title = 'Trending';

        $products = Product::query()
            ->active()
            ->orderByDesc('total_viewed')
            ->paginate(50);

        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            ->take(7)
            ->get();

        $sellers = Seller::query()
            ->where('is_active', 1)
            ->where('is_suspended', 0)
            ->has('products')
            ->get();

        $populars = Product::query()->inRandomOrder()->take(4)->get();

        $sizes = Size::query()->where('is_active', 1)->get();
        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();
        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        return view('frontend.pages.shop', compact('products', 'categories', 'sizes', 'colors', 'prices', 'brands', 'sellers', 'populars', 'title'));
    }

    public function brands()
    {
        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            ->get();

        return view('frontend.pages.all-brands', compact('brands'));
    }

    public function aboutUs()
    {
        $page = (object)config('constants.about-us');
        return view('frontend.pages.blank', compact('page'));
    }

    public function customerService()
    {
        $page = (object)config('constants.customer-service');
        return view('frontend.pages.blank', compact('page'));
    }

    public function orderReturns()
    {
        $page = (object)config('constants.order-returns');
        return view('frontend.pages.blank', compact('page'));
    }

    public function privacyPolicy()
    {
        $page = (object)config('constants.privacy-policy');
        return view('frontend.pages.blank', compact('page'));
    }

    public function shippingPolicy()
    {
        $page = (object)config('constants.shipping-policy');
        return view('frontend.pages.blank', compact('page'));
    }

    public function sitemap()
    {
        $page = (object)config('constants.sitemap');
        return view('frontend.pages.blank', compact('page'));
    }

    public function support()
    {
        $page = (object)config('constants.support');
        return view('frontend.pages.blank', compact('page'));
    }

    public function helpline()
    {
        $page = (object)config('constants.helpline');
        return view('frontend.pages.blank', compact('page'));
    }

    public function affiliates()
    {
        $page = (object)config('constants.affiliates');
        return view('frontend.pages.blank', compact('page'));
    }

    public function liveSupport()
    {
        $page = (object)config('constants.live-support');
        return view('frontend.pages.blank', compact('page'));
    }

    public function customerCare()
    {
        $page = (object)config('constants.customer-care');
        return view('frontend.pages.blank', compact('page'));
    }

    /**
     * Resend email verification mail
     *
     * @return RedirectResponse
     */
    public function resend(): RedirectResponse
    {
        auth('customer')->user()->sendEmailVerificationNotification();
        return redirect()->back();
    }

    public function changeCurrency(Request $request)
    {
        $currency = Currency::query()->findOrFail($request->get('id'));

        $data = [
            'id' => $request->get('id'),
            'symbol' => $currency->symbol,
            'name' => $currency->name,
            'cc' => $currency->cc,
            'exchange_rate' => $currency->exchange_rate,
        ];

        Cookie::queue(Cookie::make('currency', json_encode($data)));

        return response($data);
    }

    public function changeLanguage(Request $request)
    {
        $language = Language::query()->findOrFail($request->get('id'));

        $data = [
            'id' => $request->get('id'),
            'name' => $language->name,
            'alias' => $language->alias,
            'direction' => $language->direction,
        ];

        Cookie::queue(Cookie::make('language', json_encode($data)));
        session()->put('locale', $language->alias);

        return response($data);
    }

    public function ajaxFilter(Request $request)
    {
        $p = Product::query()->where('quantity', ">", 0)->where('is_manage_stock', 1)
            ->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
            ->orderBy('created_at', 'DESC');

        if ($request->has('category')) {
            $category = Category::query()
                ->where('id', $request->get('category')) // Find by ID
                ->firstOrFail();

            $cats[] = $category->id;
            foreach ($category->subCategories as $category) {
                $cats[] = $category->id;
                foreach ($category->subCategories as $category) {
                    $cats[] = $category->id;
                }
            }

            $p->whereIn('category_id', $cats);
        }

        if ($request->has('slug') && $request->get('slug') != null) {
            $p->where('name', 'like', '%' . $request->get('slug') . '%')
                ->orWhere('tags', 'like', '%' . $request->get('slug') . '%');
        }

        $p = Product::query()->whereHas('productstock', function ($q) use ($request) {

                if ($request->filled('color')) {
                    $q->whereIn('color_id', $request->color);
                }

                if ($request->filled('size')) {
                    $q->whereIn('size_id', $request->size);
                }
            });

        if ($request->has('brand')) {
            $brand = $request->get('brand');
            $p->whereIn('brand_id', $brand);
        }

        if ($request->has('seller')) {
            $seller = $request->get('seller');
            $p->whereIn('seller_id', $seller);
        }

        if ($request->has('min') && $request->has('max')) {
            $min = $request->get('min');
            $max = $request->get('max');
            if ($min >= 0 && $max > 0) {
                $p->whereBetween('sale_price', [$min, $max]);
            }
        }

        if ($request->has('sorting')) {
            $sortBy = $request->get('sorting');
            if ($sortBy == "price") {
                $p->orderBy('sale_price');
            } elseif ($sortBy == "popularity") {
                $p->orderByDesc('total_viewed');
            } else {
                $p->orderBy('id');
            }
        }

        if ($request->has('page')) {
            $page = $request->get('page');
            $p->skip($page * 12);
        }

        $products = $p->where('is_active', 1)->paginate(50);

        return view('frontend.pages._ajax-product', compact('products'));
    }

    public function suggest(Request $request)
    {
        $products = Product::query()
            ->where('name', 'like', '%' . $request->get('query') . '%')
            ->inRandomOrder()
            ->take(4)
            ->get();

        $pro = [];

        foreach ($products as $product) {
            $pro[] = [
                'name' => $product->name,
                'image' => asset('uploads/products/galleries') . '/' . $product->images->first()->image,
                'link' => route('product', $product->slug)
            ];
        }

        $data['suggests'] = ['_' => $pro];

        return response(json_encode($data));
    }
    public function suggestNew(Request $request)
    {
        $products = Product::query()
            ->with('images')
            ->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('tags', 'like', '%' . $request->search . '%')
            ->inRandomOrder()
            //->take(4)
            ->get();

        $pro = [];

        foreach ($products as $product) {
            $pro[] = [
                'name' => $product->name,
                'image' => asset('uploads/products/galleries') . '/' . $product->images->first()->image,
                'link' => route('product', $product->slug)
            ];
        }

        $data['suggests'] = ['_' => $pro];

        //return response(json_encode($data));
        return $products;
    }

    /**
     * Store email subscriber to database
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email|unique:email_subscribers',
        ]);

        $email = $request->get('email');

        EmailSubscriber::query()->create(['email' => $email]);

        Session::flash('success', 'You are listed in our daily newsletter');

        return redirect()->back();
    }

    /**
     * A collection of active categories
     *
     * @return Collection
     */
    public function categories(): Collection
    {
        return Category::query()
            ->where('is_active', 1)
            ->orderByRaw('cat_order = 0, cat_order ASC')
            ->where('category_id', null)
            ->take(12)
            ->get();
    }

    public function sendToSeller(Request $request)
    {
        $request['sender'] = 'customer';
        $m = Message::query()->create($request->all());
        return response([$m]);
    }


    // for new arrival
    public function new_arrival()
    {
        $newArrivals = Product::query()
            ->with('images', 'details', 'reviews')
            ->where('is_active', 1)
            ->where('quantity', ">", 0)
            ->where('is_manage_stock', 1)
            ->orderByDesc('created_at')
            ->limit(24)
            ->get();
        //dd($newArrivals->total());
        $categories = $this->categories();

        $brands = Brand::query()
            ->where('is_active', 1)
            ->orderBy('order')
            ->has('products')
            //->take(7)
            ->get();

        $prices = collect(['min' => 0, 'max' => 5000, 'values' => [75, 1000]]);

        $colors = Color::query()
            ->where('is_active', 1)
            ->where('display_in_search', 1)
            ->get();

        $sizes = Size::query()->where('is_active', 1)->get();

        $populars = Product::query()->inRandomOrder()->take(4)->get();
        // dd($newArrivals);

        return view('frontend.pages.new_arrival', compact('newArrivals', 'categories', 'brands', 'prices', 'colors', 'sizes', 'populars'));
    }

    public function campaign($slug)
    {
        $campaign_data = Campaign::where('slug', $slug)->with(['images','products.productstock.color','products.productstock.size'])->first();

//        $campaingProducts = CampaignProduct::query()->where('campaign_id', $campaign_data->id)->get();

//        $products = \App\Modules\Backend\ProductManagement\Entities\Product::whereIn('id', function($query) use ($campaign_data) {
//            $query->select('product_id')
//                ->from('campaign_products')
//                ->where('campaign_id', $campaign_data->id);
//        })->orWhere('id', $campaign_data->product_id)
//            ->with(['image','productstock'])
//            ->get();
//        dd($products);

        $products = $campaign_data->products ?? [];


//        Cart::instance('shopping')->destroy();
//        $cart_count = Cart::instance('shopping')->count();
//        $product = $products->first();
//        if ($cart_count == 0) {
//            Cart::instance('shopping')->add([
//                'id' => $product->id,
//                'name' => $product->name,
//                'qty' => 1,
//                'price' => $product->new_price,
//                'options' => [
//                    'slug' => $product->slug,
//                    'image' => $product->image->image,
//                    'old_price' => $product->old_price,
//                    'purchase_price' => $product->purchase_price,
//                ],
//            ]);
//        }
        //return $products;
//        $shippingcharge = ShippingCharge::where('status', 1)->get();
//        $select_charge = ShippingCharge::where('status', 1)->first();
//        Session::put('shipping', $select_charge->amount);
        return view('frontend.campaign', compact('campaign_data', 'products'));
    }

    public function shipping_charge(Request $request)
    {
        dd($request->all());

//        $shipping = ShippingCharge::where(['id' => $request->id])->first();
//        Session::put('shipping', $shipping->amount);
//        return view('frontEnd.layouts.ajax.cart');
    }

    public function cart_remove(Request $request)
    {
        dd($request->all());
//        $remove = Cart::instance('shopping')->update($request->id, 0);
//        $data = Cart::instance('shopping')->content();
//        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }

    public function cart_increment(Request $request)
    {
        dd($request->all());
//        $item = Cart::instance('shopping')->get($request->id);
//        $qty = $item->qty + 1;
//        $increment = Cart::instance('shopping')->update($request->id, $qty);
//        $data = Cart::instance('shopping')->content();
//        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }

    public function cart_decrement(Request $request)
    {
        dd($request->all());
//        $item = Cart::instance('shopping')->get($request->id);
//        $qty = $item->qty - 1;
//        $decrement = Cart::instance('shopping')->update($request->id, $qty);
//        $data = Cart::instance('shopping')->content();
//        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }

    public function cart_update(Request $request)
    {
        dd($request->all());
        // Get the row ID of the cart item
//        $rowId = $request->id;
//        // Fetch the current cart item using the row ID
//        $cartItem = Cart::instance('shopping')->get($rowId);
//        if ($cartItem) {
//            // Update the options for the cart item
//            Cart::instance('shopping')->update($rowId, [
//                'options' => [
//                    'product_size' => $request->product_size ?: $cartItem->options->product_size, // Use new size or keep existing
//                    'product_color' => $request->product_color ?: $cartItem->options->product_color, // Use new color or keep existing
//                    'slug' => $cartItem->options->slug, // Keep existing slug
//                    'image' => $cartItem->options->image, // Keep existing image
//                    'old_price' => $cartItem->options->old_price, // Keep existing old price
//                    'purchase_price' => $cartItem->options->purchase_price, // Keep existing purchase price
//                    'pro_unit' => $cartItem->options->pro_unit, // Keep existing pro unit
//                ],
//            ]);
//        }
//
//        $data = Cart::instance('shopping')->content();
//        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function changeProduct(Request $request)
    {

        dd($request->all());


        // Get the selected product
//        $productId = $request->input('id');
//        $product = \App\Models\Product::find($productId); // Fetch the product by ID



//        if ($product) {
//            // Clear existing items in the cart if necessary
//            Cart::instance('shopping')->destroy(); // Or adjust this logic as needed
//
//            // Add the selected product to the cart
//            Cart::instance('shopping')->add([
//                'id' => $product->id,
//                'name' => $product->name,
//                'qty' => 1, // Adjust quantity as needed
//                'price' => $product->new_price,
//                'options' => [
//                    'slug' => $product->slug,
//                    'image' => $product->image->image,
//                    'old_price' => $product->old_price,
//                    'purchase_price' => $product->purchase_price,
//                ],
//            ]);
//            $data = Cart::instance('shopping')->content();
//            return view('frontEnd.layouts.ajax.cart', compact('data'));
//
//        }
//
//        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }

}
