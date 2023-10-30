<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') - {{\App\CPU\translate('app_name')}} </title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="{{\App\CPU\translate('app_name')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="summary_large_image" name="twitter:card"/>
    <meta content="{{\App\CPU\translate('app_name')}}" name="twitter:image:src">
    <meta content="{{\App\CPU\translate('app_name')}}" property="og:image">
    <meta content="{{\App\CPU\translate('app_name')}}" name="twitter:title">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1" name="viewport">
    <link href="{{asset('assets/front-end/images/black20circle.f13.2k-128x128.png')}}" rel="shortcut icon" type="image/x-icon">

    @include('front-end.layouts.includes.head-css')
    @yield('styles')
</head>
{{-- @section('body')
@show --}}
<body>
<div class="loading">
    <img alt="" src="public/images/logo.svg" width="100">
</div>
@include('sweetalert::alert')
@include('front-end.layouts.includes.header')
@yield('content')
@include('front-end.layouts.includes.footer')
@include('front-end.layouts.includes.script')
@yield('scripts')
</body>
</html>
