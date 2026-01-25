<div class="content-tab-title">
    <h4>{{__('Shepping Area')}}</h4>
</div>
<!-- Tab Manu Start  -->
<div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">

    <button class="nav-link @if(Request::is('admin/shipping_area'))active @endif" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance"
            type="button" role="tab" Area-controls="appearance" Area-selected="false"
            @if(url()->full()!=route('backend.shipping_area.index')) onclick="location.href='{{route('backend.shipping_area.index')}}'" @endif
    >{{__('Shipping Area')}}
    </button>
    <button class="nav-link @if(Request::is('admin/shipping_area/create'))active @endif" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance"
            type="button" role="tab" Area-controls="appearance" Area-selected="false"
            @if(url()->full()!=route('backend.shipping_area.create')) onclick="location.href='{{route('backend.shipping_area.create')}}'" @endif
    >{{__('Add Shipping Area')}}
    </button>

</div>
