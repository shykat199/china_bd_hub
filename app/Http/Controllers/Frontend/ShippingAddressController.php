<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function store(Request $request)
    {
        $user_id = auth('customer')->id()?? $request->user_id;
        $address = ShippingAddress::where('user_id', $user_id)->latest()->first();
        $data = [
            "user_id" => $user_id,
            "shipping_name" => $request->first_name,
            "address_line_one" => $request->billing_address,
            "address_line_two" => $request->address_line_two,
            "shipping_post" => $request->shipping_post,
            "shipping_town" => $request->shipping_town,
            "shipping_country_id" => $request->shipping_country_id,
            "shipping_note" => $request->shipping_note
        ];
        if(!$address){
            ShippingAddress::query()->create($data);
        } else {
            $address->update($data);
        }
    }
}
