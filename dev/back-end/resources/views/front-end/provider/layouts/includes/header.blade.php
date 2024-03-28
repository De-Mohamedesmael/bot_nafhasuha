<header>
    <section class="menu1 menu carm4_menu1" id="menu1-y">

        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light d-flex ">

                <button aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"
                        class="navbar-toggler"
                        data-target="#navbarText" data-toggle="collapse" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="btn-company">
                    <a class="" href="{{url('/')}}">
                        {{\App\CPU\translate('web_user')}}
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
                        <li class="nav-item">
                            <a class="nav-link" data-value="our-partners" href="#">{{\App\CPU\translate('About')}}
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
                        <li class="nav-item active">
                            <a class="nav-link" href="#">{{\App\CPU\translate('Home')}}</a>
                        </li>
                    </ul>


                </div>
                <a class="navbar-brand" href="\">
                    <img alt="{{\App\CPU\translate('app_name')}}" height="60" src="{{asset('assets/images/settings/'.$logo)}}" width="100">
                </a>
            </nav>
        </div>
        <div class="head-header">
            <div class="container">
                <div class="row ">
                    <div class="image col">
                        <img alt="" src="{{asset('assets/front-end/public/images/provider/header_two.svg')}}">
                    </div>
                    <div class="text col">
                        <h3>
                            {!!  \App\CPU\translate('welcome_nf_user') !!} <span>{!!  \App\CPU\translate('welcome_nf_user2') !!}</span>
                            <br>{!!  \App\CPU\translate('welcome_nf_user3') !!}
                        </h3>

                        <p class="">{{\Settings::get('welcome_messages_provider_'.app()->getLocale(),"يعد موقع نفحصها نظاماً متكاملاً , قدرته فائقة لتلبي معظم إحتياجات مراكز الخدمة بكل
                            أنواعها مع
                            اختلاف أحجامها. كما يتميز البرنامج بسهولة الاستخدام والإنسيابية , مما يحسن من أداء العاملين
                            بالمركز , وبالتالى يرفع من كفاءة ومستوى الخدمة. و يوفر هذا النظام الأدوات الأساسية لإدارة
                            متكاملة بشكل احترافى متميز محاسبياً و إدارياً , بأعلي")}}</p>                    </div>
                </div>
            </div>


        </div>


    </section>
</header>
