<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseMessage;
use App\Models\ShippingArea;
use Illuminate\Http\Request;

class ShippingAreaController extends Controller
{
    use ResponseMessage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping_areas = ShippingArea::orderByDesc('id')->paginate(10);
        return  view('backend.pages.shipping_area.pages.index',compact('shipping_areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shipping_area = new  ShippingArea();
        return view('backend.pages.shipping_area.pages.create',compact('shipping_area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|unique:shipping_areas,name,',
            'charge'=>'required|numeric',
        ]);

        $data = $request->only('name','charge','status');
        $data['display_in_search']=1;
        ShippingArea::create($data);
        return redirect()->route('backend.shipping_area.index')->with($this->create_success_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipping_area = ShippingArea::findOrFail($id);
        return view('backend.pages.shipping_area.pages.edit',compact('shipping_area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string|unique:shipping_areas,name,'.$id,
            'charge'=>'required|numeric',
        ]);

        $data = $request->only('name','charge','status');
        $data['display_in_search']=1;
        $shipping_area = ShippingArea::findOrFail($id);
        $shipping_area->update($data);
        return redirect()->route('backend.shipping_area.index')->with($this->update_success_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping_area = ShippingArea::findOrFail($id);
        $shipping_area->delete();
        return redirect()->route('backend.shipping_area.index')->with($this->delete_success_message);
    }
    public function ShippingAreaStatus(Request $request)
    {
        if ($request->ajax()){
            $shipping_area = ShippingArea::where('id',$request->id)->first();
            if ($shipping_area){
                $shipping_area->update(['status'=>$request->status]);

                return response()->json($this->update_success_message);
            } else {
                return response()->json($this->not_found_message);
            }
        }else{
            return response()->json("message","Sorry !! Bad Request.");
        }

    }
}
