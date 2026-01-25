<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\CourierApis;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseMessage;

class CourierApisController extends Controller
{
    use ResponseMessage;
    public function courier_manage ()
    {
        $steadfast = CourierApis::where('type','=','steadfast')->first();
        $pathao = CourierApis::where('type','=','pathao')->first();
        return view('backend.pages.apiintegration.courier_manage',compact('steadfast','pathao'));
    }

    public function courier_update(Request $request)
    {
        // Prepare input
        $input = $request->except('id'); // avoid overwriting id
        $input['status'] = $request->status ? 1 : 0;

        // Update if found, otherwise create new
        CourierApis::updateOrCreate(
            ['id' => $request->id], // condition
            $input                  // data
        );

        return redirect()->back()->with($this->update_success_message);
    }

}
