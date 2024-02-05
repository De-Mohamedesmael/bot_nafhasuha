@php
    $logo = \Settings::get('logo');;
    $logo_footer = \Settings::get('logo_footer');
    $site_title = App\Models\System::getProperty('site_title');
    $watsapp_numbers = App\Models\System::getProperty('watsapp_numbers');
@endphp
<header class="header no-print">
    <nav class="navbar">
        <div class="container-fluid ">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <div class="logo-div " >
                    <a id="toggle-btn" href="#" class="menu-btn">
                        <i class="fa fa-bars" style="margin-top: 5px !important;">
                        </i>
                    </a>
                    <span class="brand-big"><img src="{{$logo_footer != null ? asset('assets/images/settings/'.$logo_footer) :asset('assets/front-end/public/images/footer_logo.svg')}}"
                                                 width="120">&nbsp;&nbsp;<a href="{{url('/')}}">
                        <h1 class="d-inline">{{$site_title}}</h1>
                    </a></span>
                </div>



                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <li class="sli_li">
                        @yield('sli_li')
                    </li>
                    @php
                        $config_languages = config('constants.langs');
                        $languages = [];
                        foreach ($config_languages as $key => $value) {
                        $languages[$key] = $value['full_name'];
                        }
                    @endphp
                    <li class="nav-item">
                        <a href="{{route('admin.switchLanguage', app()->getLocale() =="ar"?'en':'ar') }}" class="btn btn-link" data-toggle="tooltip" data-title="{{app()->getLocale() =="ar"?$languages['en']:$languages['ar']}}">
                            <img src="{{asset('assets/back-end/images/design/flag-'.(app()->getLocale() =="ar"?'en':'ar').'.png')}}">
                        </a>
                       {{--  <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false" class="nav-link dropdown-item">
                            <img src="{{asset('assets/back-end/images/design/flag-'.app()->getLocale().'.png')}}">
                           </a>
                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                            @foreach ($languages as $key => $lang)
                                <li>
                                    <a href="{{route('admin.switchLanguage', $key) }}" class="btn btn-link">
                                        {{$lang}}</a>
                                </li>
                            @endforeach

                        </ul>--}}
                    </li>
                    {{--    <li class="nav-item">
                           <a target="_blank" href="{{action('ContactUsController@getUserContactUs')}}" id="contact_us_btn" data-toggle="tooltip" data-title="@lang('lang.contact_us')"
                               style="background-image: url('{{asset('images/handshake.jpg')}}');" class="btn no-print">
                           </a>
                           <a target="_blank" href="https://api.whatsapp.com/send?phone={{$watsapp_numbers}}" id="contact_us_btn" data-toggle="tooltip" data-title="@lang('lang.contact_us')"
                               style="background-image: url('{{asset('images/watsapp.jpg')}}');background-size: 40px;" class="btn no-print">
                           </a>
                       </li>--}}


                    <li class="nav-item">
                        <a id="btnFullscreen">
                            <i class="dripicons-expand"></i></a></li>
                    @include('back-end.layouts.partials.notification_list')


                    {{-- <li class="nav-item">
                        <a class="dropdown-item" href="{{action('HomeController@getHelp')}}" target="_blank"><i
                                class="dripicons-information"></i> @lang('lang.help')</a>
                    </li> --}}
                    <li class="nav-item">
                        <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" class="nav-link dropdown-item" style="    height: 50px;">
                            @php
                                $employee =  Auth::guard('admin')->user();
                            @endphp
                            <img src="@if(!empty($employee->getFirstMediaUrl('image'))){{$employee->getFirstMediaUrl('image')}}@else{{asset('assets/images/default.jpg')}}@endif"
                                 style="width: 50px; height: 50px; border: 2px solid #fff; padding: 5px; border-radius: 50%;     background-color: #e4e7f1;    margin-top: -18px;" />

                            <span style="    line-height: 15px;">
                                <span >
                                @lang('lang.welcome') : {{ucfirst(Auth::user()->name)}} <i class="fa fa-angle-down"></i>
                                </span>
                                    <br>
                            <span>
                                {{Auth::user()->email}}
                            </span>

                            </span>



                        </a>
                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">


                            <li>
                                <a href="{{route('admin.getProfile')}}"><i class="dripicons-user"></i>
                                    @lang('lang.profile')</a>
                            </li>
                            @can('settings.general_settings.view')
                            <li>
                                <a href="{{route('admin.settings.getGeneralSetting')}}"><i
                                        class="dripicons-gear"></i> @lang('lang.settings')</a>
                            </li>
                            @endcan

                            {{--<li>
                                <a href="{{url('my-transactions/'.date('Y').'/'.date('m'))}}"><i
                                        class="dripicons-swap"></i> @lang('lang.my_transactions')</a>
                            </li>
                            @if(Auth::user()->role_id != 5)
                            <li>
                                <a href="{{url('my-holidays/'.date('Y').'/'.date('m'))}}"><i
                                        class="dripicons-vibrate"></i> @lang('lang.my_holidays')</a>
                            </li>
                            @endif
                                --}}
                            <li>
                                <a href="#" id="logout-btn"><i class="dripicons-power"></i>
                                    @lang('lang.logout')
                                </a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
