@extends('backend.layouts.app')
@section('title','Picked Orders - ')
@push('css')
    @include('backend.includes.datatable_css')
    <style>
        .card{
            background: #ffff;
        }
        .custom-btn-list button {
            background: transparent;
            border: 0;
        }

        .custom-btn-list button i, .custom-btn-list a i {
            color: #444;
            font-size: 16px;
        }

        .button-list.custom-btn-list a, .button-list.custom-btn-list button {
            margin: 3px 5px;
            padding: 0;
        }

        .action2-btn {
            margin: 0;
            padding: 0;
            margin-bottom: 20px;
        }

        .action2-btn li {
            display: inline-block;
            list-style: none;
            margin: 2px 0;
        }
    </style>
@endpush
@section('content')
    <div class="content-body">
{{--    @include('ordermanagement::orders.order_overview')--}}
    <!-- Tab Content Start -->
{{--        <div class="tab-content order-content" id="nav-tabContent">--}}
{{--            <div class="tab-pane fade show active" id="picked" role="tabpanel" Area-labelledby="picked-tab">--}}
{{--                <div class="container">--}}
{{--                    <div class="content-table">--}}
{{--                        <table id="mDataTable" class="table p-table">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th scope="col">{{ __('Invoice#') }}</th>--}}
{{--                                <th scope="col">{{ __('Name') }}</th>--}}
{{--                                <th scope="col">{{ __('Country') }}</th>--}}
{{--                                <th scope="col">{{ __('Items') }}</th>--}}
{{--                                <th scope="col">{{ __('Action') }}</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}

{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
        <!-- Tab Content End -->

        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Picked Orders ({{$order_overview??0}})</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row order_page">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('frontend.includes.order-nav-bar')

                        <div class="table-responsive ">
                            <table id="datatable-buttons" class="table table-striped   w-100">
                                <thead>
                                <tr>
                                    <th style="width:2%"><div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value=""></label>
                                            <th style="width:2%">SL</th>
                                        </div></th>
                                    <th style="width:8%">Action</th>
                                    <th style="width:8%">Invoice</th>
                                    <th style="width:10%">Date</th>
                                    <th style="width:10%">Name</th>
                                    <th style="width:10%">Phone</th>
                                    <th style="width:10%">Check</th>
                                    <th style="width:10%">Amount</th>
                                    {{--                                    <th style="width:10%">Status</th>--}}
                                </tr>
                                </thead>


                                <tbody>
                                @foreach($show_data as $key=>$value)

                                    <tr>
                                        <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <div class="button-list custom-btn-list">
                                                <a href="" title="Invoice"><i class="fa fa-eye"></i></a>
                                                <a href="" title="Process"><i class="fa-solid fa-gear"></i></a>
                                                <a href="" title="Edit"><i class="fa fa-pencil-square"></i></a>
                                                <form method="post" action="" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="id">
                                                    <button type="submit" title="Delete" class="delete-confirm"><i class="fa fa-trash"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                        <td>{{$value->order_no}}</td>
                                        <td>{{date('d-m-Y', strtotime($value->updated_at))}}<br> {{date('h:i:s a', strtotime($value->updated_at))}}</td>
                                        <td>
                                            <strong>{{$value->cuatomer?$value->cuatomer->first_name ." ".$value->cuatomer->last_name :$value->shipping_name}}</strong> <br/>
                                            <strong>{{$value->shipping_address_1?$value->shipping_address_1:''}}</strong>
                                            <p>{{$value->shipping_post?$value->shipping_post:''}}</p>
                                            <p>{{$value->shipping_town?$value->shipping_town:''}}</p>
                                        </td>
                                        <td>{{$value->shipping_mobile?$value->shipping_mobile:''}}</td>
                                        <td> <a target="_blank" style="text-decoration: underline" href="https://greenviewit.com/check-fraud-customer" >Fraud Customer Check</a></td>
                                        <td>৳{{$value->total_price}}</td>
                                        {{--                                        <td>{{$value->details && !empty($value->details[0]) && $value->details[0]->orderStatus?$value->details[0]->orderStatus->name:'N/A'}}</td>--}}

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-paginate">
                            {{$show_data->links('pagination::bootstrap-4')}}
                        </div>
                    </div> <!-- end card body-->

                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection

@push('js')
    @include('backend.includes.datatable_js')
    <script>
        $(".checkall").on('change',function(){
            $(".checkbox").prop('checked',$(this).is(":checked"));
        });
{{--        $(function() {--}}
{{--            "use strict";--}}

{{--            $(document).ready(function(){--}}
{{--                // DataTable--}}
{{--                var table = $('#mDataTable');--}}
{{--                table.DataTable({--}}
{{--                    ajax: "@auth('admin'){{route('backend.picked_orders.list')}}@elseauth('seller'){{route('seller.picked_orders.list')}}@endauth",--}}
{{--                    columns: [--}}
{{--                        { data: 'order_no' },--}}
{{--                        { data: 'user_last_name' },--}}
{{--                        { data: 'user_country'},--}}
{{--                        { data: 'details_sum_qty' },--}}
{{--                        { data: 'action',searchable:false,sortable:false },--}}
{{--                    ]--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
    </script>
@endpush
