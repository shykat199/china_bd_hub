@extends('backend.layouts.app')
@section('title','Payment Manage')
@section('css')
<style xmlns="http://www.w3.org/1999/html">
  .increment_btn,
  .remove_btn {
    margin-top: -17px;
    margin-bottom: 10px;
  }
</style>

@endsection
@section('content')

<div class="container-fluid">
    <!-- end page title -->

    <div class="content-body">
        <div class="container">
            <div class="content-tab-title d-flex justify-content-between align-items-center">
                <h4>{{ __('Bkash Payment Manager') }}</h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{route('backend.paymentgeteway.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$bkash->id}}">
                        <input type="hidden" name="type" value="bkash">
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">User Name *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ @$bkash->username}}" id="username" required="" />
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="app_key" class="form-label">App Key *</label>
                                <input type="text" class="form-control @error('app_key') is-invalid @enderror" name="app_key" value="{{ @$bkash->app_key }}" id="app_key" required="" />
                                @error('app_key')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="app_secret" class="form-label">App Secret *</label>
                                <input type="text" class="form-control @error('app_secret') is-invalid @enderror" name="app_secret" value="{{ @$bkash->app_secret }}" id="app_secret" required="" />
                                @error('app_secret')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="base_url" class="form-label">Base Url *</label>
                                <input type="text" class="form-control @error('base_url') is-invalid @enderror" name="base_url" value="{{ @$bkash->base_url }}" id="base_url" required="" />
                                @error('base_url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ @$bkash->password }}" id="password" required="" />
                                @error('password')
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
                                    <input type="checkbox" value="1" @if(@$bkash->status==1)checked @endif name="status" />
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

                        <div>
                            <button type="submit" class="btn btn-success text-white" value="Submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>

    <div class="content-body">
        <div class="container">
            <div class="content-tab-title d-flex justify-content-between align-items-center">
                <h4>{{ __('Uddokkata Payment Manager') }}</h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{route('backend.paymentgeteway.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ @$shurjopay->id}}">
                        <input type="hidden" name="type" value="shurjopay">

                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">User Name *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ @$shurjopay->username}}" id="username" required="" />
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="prefix" class="form-label">Prefix *</label>
                                <input type="text" class="form-control @error('prefix') is-invalid @enderror" name="prefix" value="{{ @$shurjopay->prefix}}" id="prefix" required="" />
                                @error('prefix')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="success_url" class="form-label">Success Url *</label>
                                <input type="text" class="form-control @error('success_url') is-invalid @enderror" name="success_url" value="{{ @$shurjopay->success_url}}" id="success_url" required="" />
                                @error('success_url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="return_url" class="form-label">Return Url *</label>
                                <input type="text" class="form-control @error('return_url') is-invalid @enderror" name="return_url" value="{{ @$shurjopay->return_url}}" id="return_url" required="" />
                                @error('return_url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="base_url" class="form-label">Base Url *</label>
                                <input type="text" class="form-control @error('base_url') is-invalid @enderror" name="base_url" value="{{ @$shurjopay->base_url}}" id="base_url" required="" />
                                @error('base_url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-4">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ @$shurjopay->password}}" id="password" required="" />
                                @error('password')
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
                                    <input type="checkbox" value="1" @if(@$shurjopay->status==1)checked @endif name="status" />
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

