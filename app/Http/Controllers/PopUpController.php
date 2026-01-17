<?php

namespace App\Http\Controllers;

use App\Models\PopUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PopUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pop_up = PopUp::find(1);
        return view('backend.pages.website_setting.pop_up.create_popup', compact('pop_up'));
    }

  
    public function store(Request $request)
    {
       $validated_data = $request->validate([
        'description' => 'required',
        'image' => 'required|image|max:2024'
       ]);

       $validated_data['is_active'] = 0;

       try {
       
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = time() . $image->getClientOriginalName();
            $path =  'frontend/img/';

            $deleted_image_path = public_path(). $path . $file_name;

            if(File::exists($deleted_image_path)){
                File::delete($deleted_image_path);                   
            }

            $image->move(public_path() . '/' . $path,  $file_name);
        
            PopUp::create([
                'image' => $file_name,
                'description' => $request->description,
                'is_active' => isset($request->status2)?1:0
            ]);
        } else {
            PopUp::create([
                'description' => $request->description,
                'is_active' => isset($request->status2)?1:0
            ]);
        }

        return redirect()->back()->with([
            'message' => "Popup status changed Successfully",
            'alert-type' => 'success'
        ]);
        
       } catch (\Throwable $th) {
        // dd($th->getMessage());
        return back()->with([
            'alert-type' => 'error',
            'message' => $th->getMessage()
        ]);
       }
    }

    public function update(Request $request, string $id)
    {

        // dd($request->status2);
     
        try {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file_name = time() . $image->getClientOriginalName();
                $path =  'frontend/img/';

                $deleted_image_path = public_path(). $path . $file_name;

                if(File::exists($deleted_image_path)){
                    File::delete($deleted_image_path);                   
                }

                $image->move(public_path() . '/' . $path,  $file_name);
            
                if ($request->status) {
                    PopUp::where('id', $id)->update([
                        'image' => $file_name,
                    ]);
                } else{
                    PopUp::where('id', $id)->update([
                        'image' => $file_name,
                    ]);
                }
            } else {
                PopUp::where('id', $id)->update([
                    'description' => $request->description
                ]);

                if(!isset($request->status2)){
                    PopUp::where('id', $id)->update([
                        'is_active' => 0
                    ]); 
                }

                if($request->status2 == 'on'){
                    PopUp::where('id', $id)->update([
                        'is_active' => 1
                    ]);
                }
            }

            return redirect()->back()->with([
                'message' => "Popup status changed Successfully",
                'alert-type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                'alert-type' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
