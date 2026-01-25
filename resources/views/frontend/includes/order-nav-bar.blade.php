
<!-- TOP ROW: Add Order + Search -->
<div class="row align-items-center">

    <!-- LEFT: Add New Order Button -->
    <div class="col-md-6">
        <a href="{{route('backend.create-order')}}"
           class="btn btn-success rounded-pill text-white">
            <i class="fa fa-plus me-1"></i> Add New Order
        </a>
    </div>

    <!-- RIGHT: Search -->
    <div class="col-md-6 text-end">
        <form class="custom_form d-inline-block">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Search orders...">
                <button class="btn btn-info rounded-pill ms-2 text-white">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>

</div>

<!-- SECOND ROW: ACTION BUTTONS (BELOW) -->
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">

            <button class="btn btn-success rounded-pill text-white" data-bs-toggle="modal" data-bs-target="#asignUser">
                <i class="fa fa-user-plus me-1"></i> Assign User
            </button>

            <button class="btn btn-primary rounded-pill text-white" data-bs-toggle="modal" data-bs-target="#changeStatus">
                <i class="fa fa-exchange me-1"></i> Change Status
            </button>

            <a class="btn btn-danger rounded-pill text-white order_delete" href="{{route('backend.order-list-bulk_destroy')}}">
                <i class="fa fa-trash me-1"></i> Delete All
            </a>

            <a class="btn btn-info rounded-pill text-white multi_order_print" href="{{route('backend.multi-order-print')}}">
                <i class="fa fa-print me-1"></i> Print
            </a>

            <a class="btn btn-info rounded-pill text-white multi_order_courier" href="{{route('backend.order-bulk_courier', 'steadfast')}}?status=9">
                <i class="fa fa-truck me-1"></i> Steadfast
            </a>

            <button class="btn btn-info rounded-pill text-white" data-bs-toggle="modal" data-bs-target="#pathao">
                <i class="fa fa-motorcycle me-1"></i> Pathao
            </button>

        </div>
    </div>
</div>
@php
    $users = \App\Models\Frontend\User::get();
    $orderstatus = \App\Models\Frontend\OrderStatus::get();
@endphp

<div class="modal fade" id="asignUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('backend.order-assign')}}" id="order_assign">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Select..</option>
                            @foreach($users as $key=>$value)
                                <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('backend.change-order-list-status')}}" id="order_status_form">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="">Select..</option>
                            @foreach($orderstatus as $key=>$value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pathao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pathao Courier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('backend.bulk-order.pathao')}}" id="order_sendto_pathao" method="post">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="pathaostore" class="form-label">Store</label>
                        <select name="pathaostore" id="pathaostore" class="pathaostore form-control" >
                            <option value="">Select Store...</option>

                        </select>
                        @if ($errors->has('pathaostore'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pathaostore') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="pathaocity" class="form-label">City</label>
                        <select name="pathaocity" id="pathaocity" class="chosen-select pathaocity form-control" style="width:100%" >
                            <option value="">Select City...</option>
                        </select>
                        @if ($errors->has('pathaocity'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pathaocity') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Zone</label>
                        <select name="pathaozone" id="pathaozone" class="pathaozone chosen-select form-control  {{ $errors->has('pathaozone') ? ' is-invalid' : '' }}" value="{{ old('pathaozone') }}"  style="width:100%">
                        </select>
                        @if ($errors->has('pathaozone'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pathaozone') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!-- form group end -->
                    <div class="form-group mt-3">
                        <label for="" class="form-label">Area</label>
                        <select name="pathaoarea" id="pathaoarea" class="pathaoarea chosen-select form-control  {{ $errors->has('pathaoarea') ? ' is-invalid' : '' }}" value="{{ old('pathaoarea') }}"  style="width:100%">
                        </select>
                        @if ($errors->has('pathaoarea'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pathaoarea') }}</strong>
                            </span>
                        @endif
                    </div>
                    <!-- form group end -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="pathao-submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('submit', 'form#order_assign', function(e){
        e.preventDefault();

        var url = $(this).attr('action');
        var method = $(this).attr('method') || 'GET';
        let user_id = $('select#user_id').val();

        var order_ids = $('input.checkbox:checked').map(function(){
            return $(this).val();
        }).get();

        if(order_ids.length === 0){
            swal({
                title: "Error!",
                text: "Please select an order first!",
                icon: "error",
                buttons: false,
            });
            return;
        }

        $.ajax({
            type: method,
            url: url,
            data: { user_id, order_ids },
            success: function(res){
                if(res.status === 'success'){
                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        buttons: false,
                    });

                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);

                } else {
                    swal({
                        title: "Error!",
                        text: "Something went wrong!",
                        icon: "error",
                        buttons: false,
                    });
                }
            },
            error: function () {
                swal({
                    title: "Error!",
                    text: "Request failed!",
                    icon: "error",
                    buttons: false,
                });
            }
        });
    });

    $(document).on('submit', 'form#order_status_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let order_status=$(document).find('select#order_status').val();

        var order = $('input.checkbox:checked').map(function(){
            return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){

            swal({
                title: "Error!",
                text: 'Please Select An Order First !',
                icon: "error",
                buttons: false,
            });

            return ;
        }

        $.ajax({
            type:'GET',
            url:url,
            data:{order_status,order_ids},
            success:function(res){
                if(res.status=='success'){
                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        buttons: false,
                    });
                    window.location.reload();

                }else{

                    swal({
                        title: "Error!",
                        text: 'Failed something wrong',
                        icon: "error",
                        buttons: false,
                    });
                }
            }
        });

    });

    $(document).on('click', '.order_delete', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var order = $('input.checkbox:checked').map(function(){
            return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){

            swal({
                title: "Error!",
                text: 'Please Select An Order First !',
                icon: "error",
                buttons: false,
            });
            return ;
        }

        $.ajax({
            type:'GET',
            url:url,
            data:{order_ids},
            success:function(res){
                if(res.status=='success'){

                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        buttons: false,
                    });
                    window.location.reload();

                }else{

                    swal({
                        title: "Error!",
                        text: 'Failed something wrong',
                        icon: "error",
                        buttons: false,
                    });
                }
            }
        });

    });

    // $(document).on('click', '.multi_order_print', function(e){
    //     e.preventDefault();
    //     var url = $(this).attr('href');
    //     var order = $('input.checkbox:checked').map(function(){
    //         return $(this).val();
    //     });
    //     var order_ids=order.get();
    //
    //     if(order_ids.length ==0){
    //         swal({
    //             title: "Error!",
    //             text: "Please select an order first!",
    //             icon: "error",
    //             buttons: false,
    //         });
    //         return ;
    //     }
    //
    //     $.ajax({
    //         type:'GET',
    //         url,
    //         data:{order_ids},
    //         success:function(res){
    //             if(res.status=='success'){
    //                 console.log(res.items, res.info);
    //                 var myWindow = window.open("", "_blank");
    //                 myWindow.document.write(res.view);
    //             }else{
    //                 swal({
    //                     title: "Error!",
    //                     text: "Failed something wrong!",
    //                     icon: "error",
    //                     buttons: false,
    //                 });
    //             }
    //         }
    //     });
    // });

    $(document).on('click', '.multi_order_print', function (e) {
        e.preventDefault();

        const order_ids = $('input.checkbox:checked')
            .map(function () {
                return this.value;
            }).get();

        if (order_ids.length === 0) {
            swal("Error!", "Please select an order first!", "error");
            return;
        }

        const query = order_ids.map(id => `order_ids[]=${id}`).join('&');
        const url = $(this).attr('href') + '?' + query;

        const printWindow = window.open(url, '_blank');

        printWindow.onload = function () {
            setTimeout(() => {
                printWindow.print();
            }, 300);
        };
    });


    $(document).on('click', '.multi_order_courier', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log(url);
        var order = $('input.checkbox:checked').map(function(){
            return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            swal({
                title: "Error!",
                text: "Please select an order first!",
                icon: "error",
                buttons: false,
            });
            return ;
        }

        $.ajax({
            type:'GET',
            url:url,
            data:{order_ids},
            success:function(res){
                if(res.status=='success'){

                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        buttons: false,
                    });

                    window.location.reload();

                }else{

                    swal({
                        title: "Error!",
                        text: "Failed something wrong!",
                        icon: "error",
                        buttons: false,
                    });
                }
            }
        });

    });
</script>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "{{route('pathao.stores')}}", // API route
            type: "GET",
            dataType: "json",
            success: function (response) {
                let storeSelect = $('#pathaostore');
                storeSelect.html('<option value="">Select Store...</option>');

                if (response?.data?.data?.length) {
                    response.data.data.forEach(store => {
                        storeSelect.append(
                            `<option value="${store.store_id}">${store.store_name}</option>`
                        );
                    });
                }
            },
            error: function () {
                $('#pathaostore').html('<option value="">Failed to load stores</option>');
            }
        });
        $.ajax({
            url: "{{ route('getCities') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {

                let citySelect = $('#pathaocity');
                citySelect.empty();
                citySelect.append('<option value="">Select City...</option>');

                if (response.success && response.data.length > 0) {
                    $.each(response.data, function (index, city) {
                        citySelect.append(
                            `<option value="${city.city_id}">${city.city_name}</option>`
                        );
                    });
                } else {
                    citySelect.append('<option value="">No cities found</option>');
                }
            },
            error: function () {
                $('#pathaocity').html('<option value="">Failed to load cities</option>');
            }
        });

        $('#pathaocity').on('change', function () {

            let cityId = $(this).val();
            let zoneSelect = $('#pathaozone');
            let areaSelect = $('#pathaoarea');

            // Reset dropdowns
            zoneSelect.html('<option value="">Loading zones...</option>');
            areaSelect.html('<option value="">Select Area...</option>');

            if (!cityId) {
                zoneSelect.html('<option value="">Select Zone...</option>');
                return;
            }
            let zoneUrl = "{{ route('get-zones', ':city_id') }}";
            zoneUrl = zoneUrl.replace(':city_id', cityId);

            $.ajax({
                url: zoneUrl,
                type: "GET",
                success: function (response) {

                    zoneSelect.empty();
                    zoneSelect.append('<option value="">Select Zone...</option>');

                    if (response.success && response.data.length > 0) {
                        $.each(response.data, function (i, zone) {
                            zoneSelect.append(
                                `<option value="${zone.zone_id}">${zone.zone_name}</option>`
                            );
                        });
                    } else {
                        zoneSelect.append('<option value="">No zones found</option>');
                    }
                },
                error: function () {
                    zoneSelect.html('<option value="">Failed to load zones</option>');
                }
            });
        });
        $('#pathaozone').on('change', function () {

            let zoneId = $(this).val();
            let areaSelect = $('#pathaoarea');

            areaSelect.html('<option value="">Loading areas...</option>');

            if (!zoneId) {
                areaSelect.html('<option value="">Select Area...</option>');
                return;
            }

            let areaUrl = "{{ route('areas', ':zone_id') }}";
            areaUrl = areaUrl.replace(':zone_id', zoneId);

            $.ajax({
                url: areaUrl,
                type: "GET",
                success: function (response) {

                    areaSelect.empty();
                    areaSelect.append('<option value="">Select Area...</option>');

                    if (response.success && response.data.length > 0) {
                        $.each(response.data, function (i, area) {
                            areaSelect.append(
                                `<option value="${area.area_id}">${area.area_name}</option>`
                            );
                        });
                    } else {
                        areaSelect.append('<option value="">No areas found</option>');
                    }
                },
                error: function () {
                    areaSelect.html('<option value="">Failed to load areas</option>');
                }
            });
        });
    });

    $(document).on('submit', 'form#order_sendto_pathao', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');

        var order = $('input.checkbox:checked').map(function(){
            return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){

            swal({
                title: "Error!",
                text: 'Please Select An Order First !',
                icon: "error",
                buttons: false,
            });

            return ;
        }

        let $btn = $('#pathao-submit-btn');

        $btn.prop('disabled', true).text('Processing...');

        var store_id = $('#pathaostore').val();
        var city_id  = $('#pathaocity').val();
        var zone_id  = $('#pathaozone').val();
        var area_id  = $('#pathaoarea').val();

        $.ajax({
            type:'GET',
            url:url,
            data:{
                order_ids: order_ids,
                store_id: store_id,
                city_id: city_id,
                zone_id: zone_id,
                area_id: area_id
            },
            success:function(res){
                if(res.status=='success'){
                    swal({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        buttons: false,
                    });
                    window.location.reload();

                }else{

                    swal({
                        title: "Error!",
                        text: 'Failed something wrong',
                        icon: "error",
                        buttons: false,
                    });
                }
            }
        });

    });
</script>
