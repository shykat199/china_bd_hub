<?php

namespace App\Modules\Backend\ProductManagement\Http\Controllers;

use App\Models\Backend\Color;
use App\Models\Backend\Size;
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

    public function unitsColor()
    {
        $unites = DB::table('units')->orderBy('id', 'desc')->paginate(15);
        return view('productmanagement::variants.unites', compact('unites'));
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
    public function unitsStore(Request $request)
    {
        $name = $request->size;
        if ($name) {
            DB::table('units')->insert([
                'name' => $name,
                'status' => 1,
            ]);

            return redirect()->route('backend.variant.units')->with('message', 'Unites Added Successfully');
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
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
            'hex'  => 'required|string|max:20',
        ]);

        // find color
        $color = Color::findOrFail($id);

        // update
        $color->update([
            'name' => $request->name,
            'hex'  => $request->hex,
        ]);

        return redirect()->back()->with('success', 'Color updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return redirect()->back()->with('success', 'Color deleted successfully.');
    }


    public function sizeUpdate(Request $request, $id)
    {
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // find color
        $color = Size::findOrFail($id);

        // update
        $color->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Size updated successfully.');
    }

    public function sizeDestroy($id)
    {
        $color = Size::findOrFail($id);
        $color->delete();

        return redirect()->back()->with('success', 'Size deleted successfully.');
    }

    public function unitsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::table('units')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
            ]);

        return redirect()->back()->with('success', 'Units updated successfully.');
    }

    public function unitsDestroy($id)
    {
        DB::table('units')
            ->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Unites deleted successfully.');
    }

}
