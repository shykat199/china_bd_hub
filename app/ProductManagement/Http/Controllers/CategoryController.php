<?php

namespace App\Modules\Backend\ProductManagement\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseMessage;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Storage;
use App\Modules\Backend\ProductManagement\Entities\Category;

class CategoryController extends Controller
{
    use ResponseMessage;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('productmanagement::categories.index');
    }

    /* Process ajax request */
    public function categoryList(Request $request)
    {
        $draw = (int) $request->get('draw');
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) $request->get('length', 10);

        $searchValue = $request->input('search.value');

        // 🔐 Whitelisted sortable DB columns
        $sortableColumns = [
            'id',
            'name',
            'category_id',
            'order',
            'is_active',
        ];

        // Resolve order column safely
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');
        $columnName = $request->input("columns.$orderColumnIndex.data", 'id');

        if (!$columnName || !Schema::hasColumn('categories', $columnName)) {
            $columnName = 'id';
        }

        if (!in_array($columnName, $sortableColumns)) {
            $columnName = 'id';
        }

        // Base query
        $query = Category::query();

        // Total records (without filter)
        $totalRecords = $query->count();

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('order', 'like', "%{$searchValue}%")
                    ->orWhere('id', 'like', "%{$searchValue}%");
            });
        }

        // Total records with filter
        $totalRecordswithFilter = $query->count();

        // Fetch records
        $records = $query
            ->with('parents')
            ->orderBy($columnName, $orderDirection)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {

            $checked  = $record->is_active ? 'checked' : '';
            $display  = $record->show_in_home ? 'checked' : '';
            $parent   = $record->category_id ? ($record->parents->name ?? '') : 'ROOT';

            $image = '<img src="' . url('uploads/categories/32x32/' . $record->icon) . '" width="60" height="60">';

            $edit_route = auth('seller')->check()
                ? route('seller.categories.edit', $record->id)
                : route('backend.categories.edit', $record->id);

            $delete_route = auth('seller')->check()
                ? route('seller.categories.destroy', $record->id)
                : route('backend.categories.destroy', $record->id);

            $edit_button = '';
            $delete_button = '';

            if (auth()->user()->can('edit_categories') || auth()->user()->hasRole('super-admin')) {
                $edit_button = '<li><a href="' . $edit_route . '" class="p-0 action">Edit</a></li>';
            }

            if (auth()->user()->can('delete_categories') || auth()->user()->hasRole('super-admin')) {
                $delete_button = '<li>
                <form method="POST" action="' . $delete_route . '">
                    ' . csrf_field() . method_field('DELETE') . '
                    <a href="javascript:void(0)" onclick="deleteWithSweetAlert(event,this.parentNode);">Delete</a>
                </form>
            </li>';
            }

            $data_arr[] = [
                'id'           => $record->id,
                'name'         => $record->name,
                'image'        => $image,
                'category_id'  => $parent,
                'show_in_home' => '<input type="checkbox" ' . $display . '>',
                'order'        => $record->order,
                'is_active'    => '<input type="checkbox" ' . $checked . '>',
                'action'       => '<ul>' . $edit_button . $delete_button . '</ul>',
            ];
        }

        // ✅ Correct DataTables response format
        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecordswithFilter,
            'data'            => $data_arr,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category();
        $categories = Category::select('id', 'name')->get();
        return view('productmanagement::categories.create', compact('category', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required |string|max:200|unique:categories,name,NULL,id,deleted_at,NULL',
                'slug' => 'required |string|max:200|unique:categories,slug,NULL,id,deleted_at,NULL',
                'order' => 'nullable|integer',
                'commission_rate' => 'nullable|max:99|min:1',
                'banner' => 'required|mimes:jpeg,png,jpg,gif,svg',
                'icon' => 'required|mimes:jpeg,png,jpg,gif,svg',
                'meta_title' => 'nullable|string',
            ]);
            $data = $request->only(['name', 'category_id', 'order', 'meta_title', 'meta_description', 'commission_rate']);
            $banner = $request->file('banner');
            $icon = $request->file('icon');
            if ($banner) {
                $banner_path = Storage::putFile('categories/200x200', $banner);
                $pattern = "/categories\/200x200\//";
                $banner_path = preg_replace($pattern, '', $banner_path);
            }

            $data['banner'] = $banner_path;
            if ($icon) {
                $icon_path = Storage::putFile('categories/32x32', $icon);
                $pattern = "/categories\/32x32\//";
                $icon_path = preg_replace($pattern, '', $icon_path);
            }
            $data['icon'] = $icon_path;
            $data['is_active'] = 0;
            $data['show_in_home'] = 0;
            Category::create($data + [
                'slug' => Str::slug($request->name)
            ]);

            return response()->json([
                'redirect' => auth('seller')->user() ? route('seller.categories.index') : route('backend.categories.index'),
                'message' => __('Category created successfully.'),
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json([
                'redirect' => auth('seller')->user() ? route('seller.categories.index') : route('backend.categories.index'),
                'message' => __('Something was wrong.'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Backend\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /* change status*/
    public function changeStatus(Request $request)
    {
        $category = Category::find($request->cat_id);
        if ($category) {
            if ($request->field == 'is_active')
                $category->is_active = $request->status;
            if ($request->field == 'show_in_home')
                $category->show_in_home = $request->status;
            $category->save();
            return response()->json($this->update_success_message);
        } else {
            return response()->json($this->not_found_message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Backend\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::select('id', 'name')->where('id', '!=', $category->id)->get();
        return view('productmanagement::categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Backend\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $request->validate([
                'name' => 'required |string|max:200|unique:categories,name,' . $category->id . ',id,deleted_at,NULL',
                'slug' => 'required |string|max:200|unique:categories,slug,' . $category->id. ',id,deleted_at,NULL',
                'order' => 'required',
                'category_id' => 'nullable|integer',
                'banner' => 'nullable|mimes:jpeg,png,jpg,gif,svg',
                'icon' => 'nullable| mimes:jpeg,png,jpg,gif,svg',
                'meta_title' => 'required',
            ]);
            $data = $request->only(['name', 'slug', 'category_id', 'order', 'meta_title', 'meta_description', 'commission_rate']);

            $category->update($data);
            $category->refresh();
            $banner = $request->file('banner');
            $icon = $request->file('icon');
            if ($banner) {
                $banner_path = Storage::putFile('categories/200x200', $banner);
                $pattern = "/categories\/200x200\//";
                $banner_path = preg_replace($pattern, '', $banner_path);
                if (file_exists(storage_path('app/public/categories/200x200/') . $category->banner)) {
                    Storage::delete('categories/200x200/' . $category->banner);
                }
                $category->banner = $banner_path;
                $category->save();
            }
            if ($icon) {
                $icon_path = Storage::putFile('categories/32x32', $icon);
                $pattern = "/categories\/32x32\//";
                $icon_path = preg_replace($pattern, '', $icon_path);
                if (file_exists(storage_path('app/public/categories/32x32/') . $category->icon))
                    Storage::delete('categories/32x32/' . $category->icon);
                $category->icon = $icon_path;
                $category->save();
            }

            return response()->json([
                'redirect' => auth('seller')->user() ? route('seller.categories.index') : route('backend.categories.index'),
                'message' => __('Category updated successfully.'),
            ]);

        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json([
                'redirect' => auth('seller')->user() ? route('seller.categories.index') : route('backend.categories.index'),
                'message' => __('Something was wrong.'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Backend\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            if (file_exists(storage_path('app/public/categories/200x200/') . $category->banner))
                Storage::delete('categories/200x200/' . $category->banner);
            if (file_exists(storage_path('app/public/categories/32x32/') . $category->icon))
                Storage::delete('categories/32x32/' . $category->icon);
            $category->delete();
            if (auth('seller')->user())
                return redirect()->route('seller.categories.index')->with($this->delete_success_message);
            else
                return redirect()->route('backend.categories.index')->with($this->delete_success_message);
        } else {
            return back()->with($this->not_found_message);
        }
    }
}
