<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\GoogleTagManager;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseMessage;


class GoogleTagManagerController extends Controller
{
    use ResponseMessage;

    public function index(Request $request)
    {
        $data = GoogleTagManager::orderBy('id','DESC')->get();
        return view('backend.pages.tagmanager.index',compact('data'));
    }

    public function create()
    {
        return view('backend.pages.tagmanager.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'status' => 'required',
        ]);
        $input = $request->all();
        GoogleTagManager::create($input);
        return redirect()->route('backend.tagmanagers.index')->with($this->create_success_message);
    }

    public function edit($id)
    {
        $edit_data = GoogleTagManager::find($id);
        return view('backend.pages.tagmanager.edit',compact('edit_data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $update_data = GoogleTagManager::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        return redirect()->route('backend.tagmanagers.index')->with($this->update_success_message);
    }

    public function inactive(Request $request)
    {
        $inactive = GoogleTagManager::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        return redirect()->back()->with($this->update_success_message);
    }

    public function active(Request $request)
    {
        $active = GoogleTagManager::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        return redirect()->back()->with($this->update_success_message);
    }

    public function destroy(Request $request)
    {
        $delete_data = GoogleTagManager::find($request->hidden_id);
        $delete_data->delete();
        return redirect()->back()->with($this->delete_success_message);
    }
}
