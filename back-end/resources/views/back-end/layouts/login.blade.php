@php
    \Settings::set('logo','logo.svg');
        $logo = \Settings::get('logo');
@endphp
    <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{\Settings::get('site_title',env('APP_NAME'))}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="manifest" href="{{url('manifest.json')}}">
    <link rel="icon" type="image/png" href="{{asset('/assets/images/settings/'.$logo)}}" />
    <!-- Bootstrap CSS-->
    @include('back-end.layouts.partials.css')
</head>

<body>
<input type="hidden" id="__language" value="{{session('language')}}">
<div class="page login-page" @yield('content') </div>
<script type="text/javascript">
    base_path = "{{url('/')}}";
</script>
@include('back-end.layouts.partials.javascript-auth')
@yield('javascript')
</body>

</html>
