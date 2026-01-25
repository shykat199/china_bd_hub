<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="keywords" content="{{ config('app.name')}}">
    <meta name="description" content="{{ config('app.name')}} ">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'Mybazar')}} {{__('Login')}}</title>

    <!-- All Device Favicon -->
    <link rel="icon" href="@if(config('app.favicon')){{asset(config('app.favicon'))}}@endif">

    @include('backend.includes.layout_css')
</head>

<body>

<div class="mybazar-login-section">
    <div class="mybazar-login-wrapper">
        <div class="login-wrapper">
            <div class="login-header">
                <img src="@if(config('app.logo')){{asset(config('app.logo'))}}@endif" alt="logo">
            </div>
            <div class="login-body signup-form-body">
                <h2> {{ __('My Bazar  Sign Up Panel') }}</h2>

                <form action="{{ route('seller.register') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 my-2">
                            <label for="shop">{{ __('Shop Name') }} </label> <span class="text-danger">*</span>
                            <input id="shop" name="company_name" type="text" placeholder="Enter Your Shop Name" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" value="{{ old('company_name') }}" >
                            @error('company_name')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="bs-ctg">{{ __('Business Category') }}</label> <span class="text-danger">*</span>
                            <select name="category" required class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}">
                                <option value="" selected>{{ __('Select') }}</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}"@if (old('category')==$category->id) selected

                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="phone">{{ __('Mobile') }}</label> <span class="text-danger">*</span>
                            <input id="phone" name="mobile" type="number" placeholder="Phone" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }} " value="{{old('mobile')}}">
                            @error('mobile')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="tin">{{ __('TIN/Trade licence') }}</label>
                            <input id="tin" name="tin" type="number" placeholder="TIN/Trade licence" class="form-control" value="{{old('tin')}}">
                            @error('tin')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="email">{{ __('User Email') }} </label> <span class="text-danger">*</span>
                            <input id="email" name="email" type="email" placeholder="User Email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"  value="{{old('email')}}">
                            @error('email')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="nid">{{ __('NID/Passport') }}</label>
                            <input id="nid" name="nid_no" type="text" placeholder="NID/Passport" class="form-control"  value="{{old('nid_no')}}">
                            @error('nid_no')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="pass">{{ __('Password') }} </label><span class="text-danger">*</span>
                            <input id="pass" name="password" type="password" placeholder="Password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                            @error('password')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="pass">{{ __('Confirm Password') }} </label><span class="text-danger">*</span>
                            <input id="pass" name="password_confirmation" type="password" placeholder="Password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                            @error('password_confirmation')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="web">{{ __('Website') }}</label>
                            <input id="web" name="website" type="text" placeholder="Your Website" class="form-control"  value="{{old('website')}}">
                            @error('website')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="address">{{ __('Address') }} </label><span class="text-danger">*</span>
                            <textarea  id="address" name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"  placeholder="Address">{{old('address')}}</textarea>
                            @error('address')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="city">{{ __('City') }}</label><span class="text-danger">*</span>
                            <input id="city" name="city" type="text" placeholder="Your city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" value="{{old('city')}}">
                            @error('city')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="zip">{{ __('Post Code') }}</label>
                            <input id="zip" name="post_code" type="number" placeholder="Zip Code" class="form-control" value="{{old('post_code')}}">
                            @error('post_code')
                            <span class="text-danger">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn login-btn">{{ __('Sign Up') }}</button>
                </form>
                <div class="login-footer">
                    <a href="#">
                        <span><img src="https://my-bazar.maantheme.com/backend/img/icons/lock1.svg" alt=""></span>{{ __('Forgot Password?') }}</a>
                    <span>
                            <a href="{{route('seller.login')}}">{{ __('Sign In') }}</a>
                        </span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.includes.layout_js')
<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {

            // validate form on keyup and submit
            $("#LoginForm").validate();
            let showPass = document.querySelector('.hide-pass');
            showPass.addEventListener('click', function() {
                showPass.classList.toggle("show-pass");
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            })
        });
    })(jQuery);
    function fillup(email, password)
    {
        document.getElementById("user-email").value = email;
        document.getElementById("password").value = password;
    }
</script>
</body>

</html>


