@extends('back-end.layouts.login')
@section('styles')

    <style>
        .navbar-holder {
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .login-page, .register-page{
            background-image: url("{{asset('assets/back-end/images/design/login-05.png')}}");
            background-size: cover;
            background-repeat: no-repeat;
        }
        .login-page .form-outer, .register-page .form-outer {
            min-height: 100vh;
            max-width: 420px;
            margin: 0;
            left: 8%;
            padding: 20px 0;
            position: relative;
        }
        .login-page .form-inner, .register-page .form-inner {
            border-radius: 5px;
            padding: 10px;
            background: #fff;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            width: 100%;
            margin-top: 15px;
            height: 50%;
        }
        a.forgot-pass {
            color: #000 !important;
            font-size: 18px !important;

        }
        .btn-primary ,.btn-primary:hover{
            background-color: #013e6b;
            border-color: #013e6b;
        }
    </style>
@endsection
@section('content')
@php
$logo = asset('assets\images\settings\\'.\Settings::get('logo','logo.svg'));
$site_title = \Settings::get('site_title',env('APP_NAME'));
$config_languages = config('constants.langs');
$languages = [];
foreach ($config_languages as $key => $value) {
$languages[$key] = $value['full_name'];
}
@endphp
<div class="container">
    <div class="navbar-holder">
        <div class="dropdown">
            <button class="btn dropdown-toggle" style="color: gray" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$languages[app()->getLocale()]}}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @foreach ($languages as $key => $lang)
                    <a class="dropdown-item" href="{{route('admin.switchLanguage', $key) }}">
                        {{$lang}}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="form-outer text-center d-flex align-items-center">

        <div class="form-inner">


            <div class="logo mt-3">@if($logo)<img src="{{$logo}}" width="200">&nbsp;&nbsp;@endif</div>
            @if(session()->has('delete_message'))
            <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                    data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{
                session()->get('delete_message') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login') }}" id="login-form">
                @csrf
                <div class="form-group-material">
                    <input id="email" type="email" name="email" required class="input-material" value=""
                        placeholder="{{trans('lang.email')}}">
                </div>

                <div class="form-group-material">
                    <input id="password" type="password" name="password"
                           required class="input-material" value=""
                        placeholder="{{trans('lang.password')}}">
                </div>
              {{--  @error('email')
                    <p style="color:red">
                        <strong>{{ $message }}</strong>
                    </p>
                    <br>
                @enderror --}}
                <button type="submit" class="btn btn-primary btn-block">{{trans('lang.login')}}</button>
            </form>
            <div style="
    display: inline-flex;
">
                <div>
                    <a target="_blank" href="https://api.whatsapp.com/send?phone={{$watsapp_numbers}}" style="color: #013e6b !important;padding: 0 10px;font-weight: 600">@lang('lang.contact_us')</a>
                </div>
                <div>
                    <a href="#{{-- route('admin.password.request') --}}" class="forgot-pass">{{trans('lang.forgot_passowrd')}}</a>
                </div>

            </div>

            <div class="copyrights text-center">
                <p>&copy; {{ $site_title }} | <span class="">@lang('lang.developed_by')
                        <a target="_blank" href="http://bot-ksa.com">nafhasuha.com</a></span></p>
                <p>
                    <a href="mailto:info@bot-ksa.com">info@nafhasuha.com</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
