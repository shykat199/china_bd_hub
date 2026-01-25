@extends('backend.layouts.app')
@section('title','SMS Manage')
@section('css')

@endsection
@section('content')

<div class="container-fluid">
    <!-- end page title -->

    <div class="content-body">
        <div class="container">
            <div class="content-tab-title d-flex justify-content-between align-items-center">
                <h4>{{ __('SMS Gateway') }}</h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{route('backend.smsgeteway.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$sms->id}}">


                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="api_key" class="form-label">API Key *</label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" name="api_key" value="{{ @$sms->api_key }}" id="api_key" required="" />
                                @error('api_key')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->


                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="serderid" class="form-label">Senderid *</label>
                                <input type="text" class="form-control @error('serderid') is-invalid @enderror" name="serderid" value="{{ @$sms->serderid }}" id="serderid" required="" />
                                @error('serderid')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block">Status</label>
                                <label class="switch">
                                    <input type="checkbox" value="1" @if(@$sms->status==1)checked @endif name="status" />
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="order" class="d-block">Order confirm </label>
                                <label class="switch">
                                    <input type="checkbox" value="1" @if(@$sms->order==1)checked @endif name="order" />
                                    <span class="slider round"></span>
                                </label>
                                @error('order')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="forget_pass" class="d-block">Forgot password </label>
                                <label class="switch">
                                    <input type="checkbox" value="1" @if(@$sms->forget_pass==1)checked @endif name="forget_pass" />
                                    <span class="slider round"></span>
                                </label>
                                @error('forget_pass')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="password_g" class="d-block">Password Generator </label>
                                <label class="switch">
                                    <input type="checkbox" value="1" @if(@$sms->password_g==1)checked @endif name="password_g" />
                                    <span class="slider round"></span>
                                </label>
                                @error('password_g')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->

                        <div>
                            <button type="submit" class="btn btn-success text-white" value="Submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
</div>
@endsection
