@extends('back-end.layouts.login')

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
    <div class="form-outer text-center d-flex align-items-center">
        <div class="form-inner">

            <div class="navbar-holder">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" style="color: gray" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @lang('lang.language')
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach ($languages as $key => $lang)
                        <a class="dropdown-item" href="{{route('admin.switchLanguage', $key) }}">
                            {{$lang}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
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
            <a href="{{ route('admin.password.request') }}" class="forgot-pass">{{trans('lang.forgot_passowrd')}}</a>
            <p>
                <a href="#">@lang('lang.contact_us')</a>
            </p>
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
