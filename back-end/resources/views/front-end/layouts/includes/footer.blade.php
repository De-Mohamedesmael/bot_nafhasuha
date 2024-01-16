<footer>
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-12 animate__animated animate__bounceIn animate__slower">
                <div class="btn-company">
                    <a class="" href="{{url('provider')}}">
                        {{\App\CPU\translate('web_provider')}}
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-xs-12 link animate__animated animate__bounceIn animate__slower">
                <h2>{{\App\CPU\translate('links')}}</h2>
                <ul class="">
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
                </ul>

            </div>
            <div class="col-md-3 col-xs-12 address animate__animated animate__bounceIn animate__slower">
                <h2>{{\App\CPU\translate('contentUs')}}</h2>
                <ul>
                    <li>
                        {{\Settings::get('mobile_support','+06898976543')}}
                        <br>
                        {{\Settings::get('mobile_support2','+02345768896')}}

                    </li>
                    <li>
                        {{\Settings::get('email_support','test@test.com')}}

                    </li>
                </ul>
            </div>

            <div class="col col-md-3">
                <div class="footer-logo animate__animated animate__bounceIn animate__slower">
                    <img alt="footer" src="{{$logo_footer != null ? asset('assets/images/settings/'.$logo_footer) :asset('assets/front-end/public/images/footer_logo.svg')}}">
                    <div class="row">
                        <div class="social">
                            <ul>
                                @isset($icons)
                                    @foreach($icons as $icon)
                                        <li>
                                            <a href="{{$icon->link}}">
{{--                                                <i class="fa fa-{{$icon->title}}"></i>--}}
                                                <img src="{{asset('assets/images/'.$icon->image)}}" style=" border-radius: 50%;width: 25px;height: 25px">
                                            </a>
                                        </li>
                                    @endforeach
                                @endisset

                            </ul>


                        </div>
                    </div>


                </div>
            </div>


        </div>


    </div>
    <div class="col-md-12">
        <p class="text-center">
          {{\App\CPU\translate('All rights reserved to Nafshaha Company')}}   ©  {{date('Y')}}
        </p>
    </div>
</footer>
