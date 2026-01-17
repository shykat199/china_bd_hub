<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\SmsGateways;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseMessage;


class SmsGatewaysController extends Controller
{
    use ResponseMessage;
    public function sms_manage ()
    {
        $sms = SmsGateways::first();
        return view('backend.pages.apiintegration.sms_manage',compact('sms'));
    }

    public function sms_update(Request $request)
    {
        // Prepare fields
        $input = $request->all();
        $input['status']       = $request->status ? 1 : 0;
        $input['order']        = $request->order ? 1 : 0;
        $input['forget_pass']  = $request->forget_pass ? 1 : 0;
        $input['password_g']   = $request->password_g ? 1 : 0;

        // Update if exists; create if not
        SmsGateways::updateOrCreate(
            ['id' => $request->id],  // Find by ID
            $input                    // Fields to update or create
        );

        return redirect()->back()->with($this->update_success_message);
    }

}
