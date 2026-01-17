<?php

namespace App\Modules\Backend\ProductManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;

class VariantsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function color()
    {
        $colors = DB::table('colors')->orderBy('id', 'desc')->paginate(15);
        return view('productmanagement::variants.colors', compact('colors'));
    }
    public function size()
    {
        $sizes = DB::table('sizes')->orderBy('id', 'desc')->paginate(15);
        return view('productmanagement::variants.sizes', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('productmanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $color = $request->color;
        $hex = $request->hex;
        $size = $request->size;
        if ($color || $hex) {
            $request->validate([
                'color' => 'required|unique:colors,name',
                'hex' => 'required|unique:colors,hex'
            ]);
            DB::table('colors')->insert([
                'name' => $color,
                'hex' => $hex,
                'display_in_search' => 1,
                'is_active' => 1
            ]);

            return redirect()->route('backend.variant.color')->with('message', 'Color Added Successfully');
        }

        if($size){
            $request->validate([
                'size' => 'required|unique:sizes,name',
            ]);
            DB::table('sizes')->insert([
                'name' => $size,
                'display_in_search' => 0,
                'is_active' => 1
            ]);

            return redirect()->route('backend.variant.size')->with('message', 'Size Added Successfully');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('productmanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('productmanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
