@extends('backend.layouts.app')
@section('title','Tag Manager Create')

@section('content')
    <div class="container-fluid">
        <!-- end page title -->

        <div class="content-body">
            <div class="container">
                <div class="content-tab-title d-flex justify-content-between align-items-center">
                    <h4>{{ __('Tag Manager Create') }}</h4>
                    <div class="page-title-right">
                        <a href="{{ route('backend.tagmanagers.index') }}" class="btn btn-primary text-white">
                            Manage Pages
                        </a>
                    </div>
                </div>
            </div>
            <!-- Tab Content Start -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="add-category" role="tabpanel"
                     Area-labelledby="add-category-tab">
                    <div class="container">
                        <form action="{{route('backend.tagmanagers.store')}}" method="POST" class=row
                              data-parsley-validate="" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="code" class="form-label">Tag Manager ID *</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           name="code" value="{{ old('code') }}" id="code" required="">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-6 mb-3">
                                <div class="form-group">
                                    <label for="status" class="d-block">Status</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="status" checked>
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
                            <button type="submit" class="btn btn-success text-white" value="Submit">Create GMT</button>

                        </form>
                    </div>
                </div>
            </div>
            <!-- Tab Content End -->
        </div>
    </div>
@endsection
