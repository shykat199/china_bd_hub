<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\PaymentGateways;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseMessage;

class PaymentGatewaysController extends Controller
{
    use ResponseMessage;
    public function pay_manage ()
    {
        $bkash = PaymentGateways::where('type','=','bkash')->first();
        $shurjopay = PaymentGateways::where('type','=','shurjopay')->first();
        return view('backend.pages.apiintegration.pay_manage',compact('bkash','shurjopay'));
    }

    public function pay_update(Request $request)
    {
        $input = $request->all();

        $input['status'] = $request->status ? 1 : 0;

        PaymentGateways::updateOrCreate(
            ['id' => $request->id],  // Condition to find record
            $input                   // Data to update or insert
        );

        return redirect()->back()->with($this->update_success_message);
    }

}
