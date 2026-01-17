<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Models\Frontend\Seller;
use App\Models\Seller\Category;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class SellerRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *p
     * @var string
     */
    protected $redirectTo = '/seller/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registration()
    {
        $categories = Category::where('category_id',null)->where('is_active',1)->get();
        return view('backend.pages.auth.seller.registration',compact('categories'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => ['required', 'string', 'max:255','unique:sellers'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sellers'],
            'mobile' => ['required','unique:sellers'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return Seller::create([
            'company_name' => $data['company_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 1,
            'slug' => Str::random(),
            'category_id' => $data['category'],
            'tin' => $data['tin'],
            'nid_no' => $data['nid_no'],
            'website' => $data['website'],
        ]);
    }
}