@extends('frontend.layouts.front')

@section('title','Profile')

@section('content')

    <section class="maan-user-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-md-5 wow fadeInUp" >
                    <div class="maan-author-profile">
                        <div class="user-info">
                            <div class="maan-user-thumb">
                                <label for="file">
                                    <img src="{{ asset('frontend/img/users') }}/{{ $user->image }}" alt="{{ $user->username }}" id="blah">
                                </label>
                            </div>
                            <form action="{{ route('profile.image',$user->id) }}" method="post" class="text-center" enctype="multipart/form-data">
                                @csrf
                                <input type="file" id="file" class="profile-file" name="image" onchange="readURL(this)" accept="image/png, image/jpg">
                                <button type="submit" class="btn btn-sm btn-outline-success">{{ __('Update Image') }}</button>
                            </form>
                            <div class="user-title">
                                <!-- <a href="#" class="user-name">{{ $user->username }}</a> -->
                                <a href="#" class="phone mt-2">{{ __('Last Login') }}: {{ $user->last_login_datetime ? $user->last_login_datetime->format('Y-m-d') : '' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9 col-md-7 wow fadeInUp" data-wow-delay="0.3s">
                    <form action="{{ route('profile.update', auth('customer')->id()) }}" method="post" class="ajaxform">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="first_name">{{ __('First name') }}</label>
                                    <input type="text" class="d-block w-100" name="first_name" value="{{ $user->first_name }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="last_name">{{ __('Last Name') }}</label>
                                    <input type="text" class="d-block w-100" name="last_name" value="{{ $user->last_name }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="username">{{ __('Username') }}</label>
                                    <input type="text" class="d-block w-100" name="username" value="{{ $user->username }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="address">{{ __('Address') }}</label>
                                    <input type="text" class="d-block w-100" name="address" value="{{ $user->address }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="mobile">{{ __('Contact Number') }}</label>
                                    <input type="text" class="d-block w-100" name="mobile" value="{{ $user->mobile }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="dob">{{ __('Date of Birth') }}</label>
                                    <input type="date" class="d-block w-100" name="dob" value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="old_password">{{ __(' Old Password') }}</label>
                                    <input type="passoword" id="old_password" class="form-control d-block w-100" name="old_password">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="input-group">
                                    <label for="dob">{{ __('New password') }}</label>
                                    <input type="passoword" class="form-control d-block w-100" name="password">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-3">
                                <button class="btn btn-warning submit-btn float-right"> <i class="fa fa-save" aria-hidden="true"></i> {{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop

@section('script')
    <script>
        function readURL(input) {
            "use strict";
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result).width(150).height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        @if(Session::has('success'))
        swal("{{ __('Success!') }}", "{{ Session::get('success') }}", "success");
        @endif
    </script>
@stop
