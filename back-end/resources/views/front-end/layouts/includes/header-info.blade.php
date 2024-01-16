<header>
    <section class="menu1 menu carm4_menu1" id="menu1-y">

        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light d-flex ">

                <div  id="mobile_navbar">
                    <a href="#">{{\App\CPU\translate('Home')}}</a>
                    <a href="#our-partners">{{\App\CPU\translate('About')}}</a>
                    <a href="#services">{{\App\CPU\translate('Our-Services')}}</a>
                    <a href="#contentUs">{{\App\CPU\translate('contentUs')}}</a>

                </div>
                <button aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"
                        class="navbar-toggler"
                        data-target="#navbarText" data-toggle="collapse" type="button">
                </button>
                <span class="navbar-toggler-icon  " id="icon_bar"></span>

                <div class="btn-company">
                    <a class="" href="{{url('provider')}}">
                        {{\App\CPU\translate('web_provider')}}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbarText" style="justify-content: center;">
                    <ul class="navbar-nav links">

                        <li class="nav-item">
                            @if(app()->getLocale() == 'en')
                            <a class="nav-link" href="{{route('admin.switchLanguage', 'ar') }}">Ar</a>
                            @else
                            <a class="nav-link" href="{{route('admin.switchLanguage', 'en') }}">EN</a>
                            @endif

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-value="contentUs" href="#">{{\App\CPU\translate('contentUs')}}
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{route('front.info.show','terms-and-conditions')}}">{{\App\CPU\translate('Terms and Conditions')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-value="our-partners" href="#">{{\App\CPU\translate('our-partners')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-value="app-video" href="#">{{\App\CPU\translate('app-video')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-value="services" href="#">{{\App\CPU\translate('Our-Services')}}</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">{{\App\CPU\translate('Home')}}</a>
                        </li>
                    </ul>

                </div>
                <a class="navbar-brand" href="\">
                    <img alt="{{\App\CPU\translate('app_name')}}" height="60" src="{{asset('assets/images/settings/'.$logo)}}" width="100">
                </a>
            </nav>
        </div>

