<div class="sidebar-widget">
    <h6>{{ __('Brand') }}</h6>
    <div class="widget-valu brand-widget">
        <ul>
            @foreach($brands as $brand)
                @php
                    $brandProductCount = \App\Modules\Backend\ProductManagement\Entities\Product::where('quantity', ">", 0)
                    ->where('is_manage_stock', 1)
                    ->where('is_active',1)
                    ->where('brand_id', $brand->id)
                    ->count();
                @endphp
                <li>
                    <input type="checkbox" id="{{ $brand->slug }}" class="brand-check" value="{{ $brand->id }}">
                    <label for="{{ $brand->slug }}">{{ $brand->name }} ({{$brandProductCount}})</label>
                </li>
            @endforeach
        </ul>
    </div>
</div>
