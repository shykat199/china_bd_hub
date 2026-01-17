@extends('backend.layouts.app')
@section('title','Courier Manage')
@section('css')

@endsection
@section('content')

<div class="container-fluid">
    <!-- end page title -->

    <div class="content-body">
        <div class="container">
            <div class="content-tab-title d-flex justify-content-between align-items-center">
                <h4>{{ __('Steadfast Courier') }}</h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{route('backend.courierapi.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$steadfast->id}}">
                        <input type="hidden" name="type" value="steadfast">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="api_key" class="form-label">API key *</label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" name="api_key" value="{{ @$steadfast->api_key}}" id="api_key" required="" />
                                @error('api_key')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="secret_key" class="form-label">Secret key *</label>
                                <input type="text" class="form-control @error('secret_key') is-invalid @enderror" name="secret_key" value="{{ @$steadfast->secret_key }}" id="secret_key" required="" />
                                @error('secret_key')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="url" class="form-label">Base Url</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ @$steadfast->url }}" id="secret_key" required="" />
                                @error('url')
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
                                    <input type="checkbox" value="1" @if(@$steadfast->status==1)checked @endif name="status" />
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
                <h4>{{ __('Pathao Courier') }}</h4>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{route('backend.courierapi.update')}}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ @$pathao->id}}">
                        <input type="hidden" name="type" value="pathao">

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="url" class="form-label">Base URL *</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ @$pathao->url}}" id="url" required="" />
                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="token" class="form-label">Client Secret *</label>
                                <input type="text" class="form-control @error('token') is-invalid @enderror" name="token" value="{{ @$pathao->token}}" id="token" required="" />
                                @error('token')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="api_key" class="form-label">Client Id *</label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" name="api_key" value="{{ @$pathao->api_key}}" id="token" required="" />
                                @error('api_key')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="api_key" class="form-label">Store Id *</label>
                                <input type="text" class="form-control @error('store_id') is-invalid @enderror" name="store_id" value="{{ @$pathao->store_id}}" id="token" required="" />
                                @error('store_id')
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
                                    <input type="checkbox" value="1" @if(@$pathao->status==1)checked @endif name="status" />
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

@endsection @section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<!-- Plugins js -->
<script src="{{asset('public/backEnd/')}}/assets/libs//summernote/summernote-lite.min.js"></script>
<script>
  $(".summernote").summernote({
    placeholder: "Enter Your Text Here",
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $(".btn-increment").click(function () {
      var html = $(".clone").html();
      $(".increment").after(html);
    });
    $("body").on("click", ".btn-danger", function () {
      $(this).parents(".control-group").remove();
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $(".increment_btn").click(function () {
      var html = $(".clone_price").html();
      $(".increment_price").after(html);
    });
    $("body").on("click", ".remove_btn", function () {
      $(this).parents(".increment_control").remove();
    });

    $(".select2").select2();
  });
</script>
@endsection
