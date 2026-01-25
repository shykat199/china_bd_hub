@extends('backend.layouts.app')
@section('title','Create Campaign')
@section('content')

    <div class="content-body">
        <div class="container">
            <div class="content-tab-title d-flex justify-content-between align-items-center">
                <h4>{{ __('Landing Page Create') }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('backend.campaign.index') }}" class="btn btn-primary text-white">
                        Manage Pages
                    </a>
                </div>
            </div>
        </div>
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-category" role="tabpanel" Area-labelledby="add-category-tab">
                <div class="container">
                    <form action="{{ route('backend.campaign.store') }}" method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data" name="createForm">
                        @csrf
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Landing Page Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" required="">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="banner" class="form-label">Banner Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('banner') is-invalid @enderror" name="banner" id="banner" required>
                                @error('banner')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="banner_title" class="form-label">Banner Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('banner_title') is-invalid @enderror" name="banner_title" value="{{ old('banner_title') }}" id="banner_title" required="">
                                @error('banner_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="video" class="form-label">Youtube Video ID</label>
                                <input type="text" class="form-control @error('video') is-invalid @enderror" name="video" value=""  id="video">
                                @error('video')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="product_id" class="form-label">Products <span class="text-danger">*</span></label>
                                <select class="select2 form-control @error('product_id') is-invalid @enderror"
                                        name="product_id[]"
                                        multiple="multiple"
                                        data-placeholder="Choose ..."
                                        required>
                                    @foreach($products as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- col end -->

                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="image_one" class="form-label">Image One <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('image_one') is-invalid @enderror" name="image_one" id="image_one" required="">
                                @error('image_one')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="image_two" class="form-label">Image Two</label>
                                <input type="file" class="form-control @error('image_two') is-invalid @enderror" name="image_two" id="image_two">
                                @error('image_two')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="image_three" class="form-label">Image Three</label>
                                <input type="file" class="form-control @error('image_three') is-invalid @enderror" name="image_three" id="image_three">
                                @error('image_three')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->

                        <div class="col-sm-6 mb-3">
                            <label for="image">Review Image <span class="text-danger">*</span></label>
                            <div class="input-group control-group increment">
                                <input type="file" name="image[]" class="form-control @error('image') is-invalid @enderror" required />
                                <div class="input-group-btn">
                                    <button class="btn btn-success btn-increment" type="button"><i class="fa fa-plus text-white"></i></button>
                                </div>
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="clone hide" style="display: none;">
                                <div class="control-group input-group">
                                    <input type="file" name="image[]" class="form-control" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-danger" type="button"><i class="fa fa-trash text-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col end -->

                        <div class="col-sm-6 mb-3">
                            <div class="form-group mb-3">
                                <label for="review" class="form-label">Review <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('review') is-invalid @enderror" name="review" value="{{ old('review') }}" id="review" required="">
                                @error('review')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                <textarea name="short_description" rows="6" class="summernote form-control @error('short_description') is-invalid @enderror" required="">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->

                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" rows="6" class="summernote form-control @error('description') is-invalid @enderror" required="">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->


                        <!-- col end -->

                        <button type="submit" class="btn btn-success text-white" value="Create Campaign">Create Campaign</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
@endsection

@push('custom-script')
    <script src="{{asset('backend/js/vendor/summernote-lite.min.js')}}"></script>
    <script src="{{asset('backend/js/select2.min.js')}}"></script>
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
            $('.select2').select2();
        });
    </script>
@endpush
