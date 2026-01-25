<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\EcomPixel;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseMessage;


class EcomPixelController extends Controller
{
    use ResponseMessage;
    public function index(Request $request)
    {
        $data =EcomPixel::orderBy('id','DESC')->get();
        return view('backend.pages.pixels.index',compact('data'));
    }
    public function create()
    {
        return view('backend.pages.pixels.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'status' => 'required',
        ]);
        $input = $request->all();
        EcomPixel::create($input);
        return redirect()->route('backend.pixels.index')->with($this->create_success_message);
    }

    public function edit($id)
    {
        $edit_data =EcomPixel::find($id);
        return view('backend.pages.pixels.edit',compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $update_data =EcomPixel::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        return redirect()->route('backend.pixels.index')->with($this->update_success_message);
    }


    public function destroy(Request $request)
    {
        $delete_data =EcomPixel::find($request->hidden_id);
        $delete_data->delete();
        return redirect()->back()->with($this->delete_success_message);
    }
}
