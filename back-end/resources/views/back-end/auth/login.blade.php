@extends('back-end.layouts.login')
@section('styles')

    <style>
        .navbar-holder {
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .login-page, .register-page{
            background-image: url("{{asset('assets/back-end/images/design/login.svg')}}");
            background-size: cover;
            background-repeat: no-repeat;
        }
        .login-page .form-outer, .register-page .form-outer {
            height: 100vh;
            max-width: 550px;
            margin: 0;
            left: 8%;
            padding: 20px 0;
            position: relative;
        }
        .login-page .form-inner, .register-page .form-inner {
            border-radius: 15px;
            padding: 25px 50px;
            background: #fff;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            width: 100%;
            margin-top: 15px;
            height: 100%;
        }
        a.forgot-pass {
            color: #000 !important;
            font-size: 18px !important;

        }
        .btn-primary ,.btn-primary:hover{
            background-color: #013e6b;
            border-color: #013e6b;
        }
        .login-page .logo, .register-page .logo {
            margin: 25px !important;
        }
        label.label-input {
            text-align: start;
            width: 100%;
            font-weight: 700;
            font-size: 18px;
        }
        .login-page form, .register-page form {
            max-width: 350px;
        }
        input.input-material {
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 15px 45px 15px 15px;
        }
        i.icon-input {
            position: absolute;
            left: 10px;
            top: auto;
            font-size: 22px;
            margin-top: 13px;
        }
        button.btn.btn-primary.btn-block {
            padding: 15px;
            border-radius: 15px;
        }
    </style>
    @if(app()->getLocale())
        <style>
            .form-inner {
                direction: rtl;
            }
            i.icon-input {
                right: 10px;
                left: auto;
                top: auto;
            }
        </style>
    @endif
@endsection
@section('content')
@php
$logo = asset('assets\images\settings\\'.\Settings::get('logo','logo.svg'));
$site_title = \Settings::get('site_title',env('APP_NAME'));
$config_languages = config('constants.langs');
$watsapp_numbers = App\Models\System::getProperty('watsapp_numbers');

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
                    <label class="label-input" for="email">{{trans('lang.email')}}</label>
                    <input id="email" type="email" name="email" required class="input-material" value=""
                        placeholder="{{trans('lang.enter_email')}}">
                    <i class="icon-input fa fa-envelope-o fa-lg fa-fw" aria-hidden="true"></i>
                </div>

                <div class="form-group-material">
                    <label class="label-input" for="password">{{trans('lang.password')}}</label>
                    <input id="password" type="password" name="password"
                           required class="input-material" value=""
                        placeholder="*****">

                    <i class="icon-input" aria-hidden="true"style="    margin-top: 7px;">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 32 32">
                            <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"></path>
                        </svg>
                    </i>

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
                    <a href="#{{-- route('admin.password.request') --}}" class="forgot-pass">{{trans('lang.forgot_passowrd')}}</a>
                </div>
                <div>
                    <a target="_blank" href="https://api.whatsapp.com/send?phone={{$watsapp_numbers}}" style="color: #013e6b !important;padding: 0 10px;font-weight: 600">@lang('lang.contact_us')</a>
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
