@extends('backend.layouts.app')

@section('content')


    <div class="container">

        <!-- start page title -->
        <div class="row mt-2">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Order Process [Invoice : #{{$order->order_no}}]</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="background: #ffff">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Product</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->details as $key=>$product)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @foreach ($product->product->images as $image)
                                            <img src="{{ URL::to('uploads/products/galleries/' . $image->image) }}" width="45px" height="45px" alt="product">
                                        @endforeach
                                    </td>
                                    <td>{{$product->product->name}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <form action="{{route('backend.order_change')}}" method="POST" class=row data-parsley-validate="" name="editForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$order->id}}">

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Customer name </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{$order->shipping_name?$order->shipping_name:$order->customer->full_name()}}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Customer Phone </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{$order->shipping_mobile?$order->shipping_mobile:$order->customer->mobile}}" placeholder="Phone Number">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Customer Address 1 </label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{$order->shipping_address_1?$order->shipping_address_1:''}}</textarea>
                                    @error('shipping_address_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Customer Address 2 </label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{$order->shipping_address_2?$order->shipping_address_2:''}}</textarea>
                                    @error('shipping_address_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Order Status</label>
                                    <select class="form-control select2-multiple @error('status') is-invalid @enderror" value="{{ old('status') }}" name="status" data-toggle="select2"  data-placeholder="Choose ..." required>
                                        <optgroup >
                                            <option value="">Select..</option>
                                            @foreach($orderstatus as $value)
                                                <option value="{{$value->id}}" @if($order->order_status == $value->id) selected @endif>{{$value->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <!-- col end -->
                            <div>
                                <button type="submit" class="btn btn-success text-white" value="Submit">Submit</button>
                            </div>

                        </form>

                    </div> <!-- end card-body-->
                </div>
            </div> <!-- end col-->
        </div>
    </div>


@endsection

@section('script')
<script>
    $(".checkall").on('change',function(){
        $(".checkbox").prop('checked',$(this).is(":checked"));
    });
</script>
@endsection
