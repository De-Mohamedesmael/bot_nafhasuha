@extends('front-end.layouts.app')
@section('title',\App\CPU\translate('index'))
@section('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>

    <style>

    img.img-count-section-right-icon {
        position: absolute;
        right: -45px;
        margin-top: 15px;
        width: 80px;
    }
    img.img-count-section-left-icon {
        position: absolute;
        left: -45px;
        margin-top: 32px;
        width: 50px;

    }
    #our-client .row .item {
        box-shadow: 1px 1px 6px 6px rgb(0 0 0 / 4%);
        border-radius: 5px;
    }
    #our-client .row .owl-carousel .owl-dots {
        margin: 10px;
    }

    .client-slider-right {
        right: 0;
        position: absolute;
        bottom: 0;
        width: 0;
        height: 0;
        background-color: #ffffff94;
        border-radius: 0 0 0;
        border-top: 100px solid transparent;
        border-left: 100px solid transparent;
        border-right: 2px solid transparent;
        border-bottom: 100px solid #fff3ec94;
    }

    .client-slider-right img {
        position: absolute;
        right: 32px;
        width: 86px;
        top: 10px;
    }
    .client-slider-left {
        position: absolute;
        left: -3%;
        bottom: 0;
        width: 170px;
        height: 150px;
        background-color: #fff3ec94;
        border-radius: 0 100px 0 0;
        box-shadow: 10px -6px 20px 13px #fff3ec94;
    }
    .client-slider-left img {
        width: 80px;
        margin: 55px 0px 0px 60px;
        transform: scale(-1,-1);
    }
    .modal-dialog {
        max-width: 70% !important;
    }
    video#player {
        width: 100%;
    }
    .testimonials-slider .user_image {
        cursor: pointer;
    }
    .modal-content {
        position: relative;
        background-color: #fff0;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid rgb(0 0 0 / 0%);
        border-radius: .3rem;
        outline: 0;
    }
    .modal-header {
        border-bottom: none;
    }
    button.close span {
        color: #9f0a0a;
    }
    #screen-app {
        padding: 50px 0;
    }

    #our-partners .items img {
        width: 150px;
        height: 100px;
    }
    #our-client .row .item img.review-image {
        width: 45px !important;
        height: 45px !important;
        border-radius: 50% !important;
    }
</style>
@endsection
@section('content')
    <section class="container" style="    padding: 70px 0;">
        <div class="count-section pb-3">
            <div class="overlay"></div>

            <div class="row">
                <div class="d-flex items" style="margin: auto;">
                    <div class="item animate__animated animate__bounceIn animate__slower">

                        <h1><span>{{\Settings::get('happy_customers',100)}}</span>+</h1>
                        <p> {{\App\CPU\translate('Happy_customers')}}</p>

                    </div>
                    <div class="item animate__animated animate__bounceIn animate__slower">
                        <h1><span>{{\Settings::get('Cars_repaired',500)}}</span>+</h1>
                        <p>
                            {{\App\CPU\translate('Cars_repaired')}}</p>
                    </div>
                    <div class="item animate__animated animate__bounceIn animate__slower">
                        <h1><span>{{\Settings::get('recovery_vehicle',80)}}</span>+</h1>
                        <p>
                            {{\App\CPU\translate('recovery_vehicle')}}
                        </p>
                    </div>
                    <div class="item  animate__animated animate__bounceIn animate__slower">
                        <h1><span>{{\Settings::get('Number_of_workshops_we_have',120)}}</span>+</h1>
                        <p>
                            {{\App\CPU\translate('Number_of_workshops_we_have')}}
                        </p>
                    </div>
                </div>

            </div>
            <img class="img-count-section-right-icon" src="{{asset('assets/front-end/public/images/image_2023-10-25_09-49-26.png')}}">
            <img class="img-count-section-left-icon" src="{{asset('assets/front-end/public/images/image_2023-10-25_09-52-07.png')}}">

        </div>
    </section>

    <section id="services">

        <div class="container">

            <div class="sections-title text-center animate__animated animate__bounceIn animate__slower">


                <h2>{{\App\CPU\translate('Our-Services')}}</h2>
                <p>{{\App\CPU\translate('sub_Our-Services')}}</p>
            </div>
            @php $new_div = true @endphp
                @isset($categories)
                    @foreach($categories as $k=> $category)
                    @if($new_div || $loop->first) <div class="items"> @endif


                        <div class="item animate__animated animate__bounceIn animate__slower">
                            <img alt="{{$category->title}}" src="{{asset('assets/images/'.$category->image)}}">
                            <h4>{{$category->title}}</h4>
                        </div>
                        @if(($k % 4 ==0 || $loop->last) && !$loop->first)
                            </div>
                            @php $new_div = true @endphp
                        @else
                            @php $new_div = false @endphp
                        @endif
                    @endforeach
                @else
                    <div class="items">
                    </div>
                @endisset


            </div>

    </section>

    <section id="app-video">
        <div class="sections-title text-center">
            <h2>{{\App\CPU\translate('Explanation of the application')}}</h2>
            <p>{{\App\CPU\translate('Explanation of downloading and how to use the application')}}</p>
        </div>
        <div class="video">
            <section class=" testimonials-slider">
                <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(14 38 64 / 38%)">
                </div>
                <div class="user col-md-12 col-lg-12">
                    <div class="user_image " data-toggle="modal" data-target="#exampleModal">

                        <img alt="" class="lazyload js-play"  loading="lazy" src="{{asset('assets/front-end/public/images/bi_play-circle.svg')}}">

                    </div>

                </div>
            </section>
            <div class="modal " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a  class="close js-stop" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <video controls crossorigin playsinline poster="{{ \Settings::get('booster_video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg')}}" id="player">
                                <!-- Video files -->
                                <source src="{{ \Settings::get('video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4')}}" type="video/mp4" size="576">
                                <source src="{{ \Settings::get('video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4')}}" type="video/mp4" size="720">
                                <source src="{{ \Settings::get('video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4')}}" type="video/mp4" size="1080">
                                <source src="{{ \Settings::get('video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4')}}" size="1440">

                                <!-- Caption files -->
{{--                                <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt"--}}
{{--                                       default>--}}
{{--                                <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt">--}}

                                <!-- Fallback for browsers that don't support the <video> element -->
                                <a href="{{ \Settings::get('video_user','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4')}}" download>Download</a>
                            </video>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>


    <section id="our-partners">
        <div class="container">

            <div class="sections-title text-center animate__animated animate__bounceIn animate__slower">
                <h2>{{\App\CPU\translate('our-partners')}}</h2>
                @php $Number_of_workshops= \Settings::get('Number_of_workshops_we_have',120) @endphp
                <p>{{__('messages.We have more than maintenance centers',['count'=>$Number_of_workshops])}}</p>
            </div>
            <!--        <div class="row">-->


            <div class="items  @if($companies->count() > 5) partners_slider  owl-carousel owl-theme @endif" style="    overflow: hidden;
     ">
                @isset($companies)

                    @foreach($companies as $company)
                        <div>
                            <img alt="{{$company->title}}" src="{{asset('assets/images/'.$company->image)}}">
                        </div>
                    @endforeach
                @endisset
                {{--
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>

                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/slider2.png')}}">
                </div>
                --}}

            </div>
            <!--        </div>-->

        </div>


    </section>


    {{-- <section id="package">

       <div class="sections-title text-center">
           <h2>مميزة الشغل في نفحصها</h2>
           <p>اكتر من30 الف مركز صيانة خاص بينا</p>
       </div>
       <div class="container" style="padding: 3%;">

           <div class="row text-center ">
               <div class="col-md-4 items">
                   <img src="{{asset('assets/front-end/public/images/provider/service.png')}}">
                   <h4>هتختار وقتك</h4>
                   <p>هتحدد الوقت المناسب ليك نفحصها هتسهل كل حاجة عليك</p>
               </div>
               <div class="col-md-4 items">
                   <img src="{{asset('assets/front-end/public/images/provider/service1.png')}}">
                   <h4>زود دخلك</h4>
                   <p>هتحدد الوقت المناسب ليك نفحصها هتسهل كل حاجة عليك</p>
               </div>
               <div class="col-md-4 items">
                   <img src="{{asset('assets/front-end/public/images/provider/service2.png')}}">
                   <h4>قريب وبعيد هيجيلك</h4>
                   <p>هتحدد الوقت المناسب ليك نفحصها هتسهل كل حاجة عليك</p>
               </div>
           </div>
       </div>
   </section> --}}
    <section id="review">
        <div class=" items">
            <div class="first" style="position: relative">
                <svg fill="none" height="168" style="    left: -59px;
    position: absolute;
    height: 132px;" viewBox="0 0 211 168" width="211" xmlns="http://www.w3.org/2000/svg">
                    <path d="M209.497 51.5C209.497 114.924 154.677 166.5 86.8499 166.5C19.0223 166.5 -35.7975 114.924 -35.7975 51.5C-35.7975 -11.9243 19.0223 -63.5 86.8499 -63.5C154.677 -63.5 209.497 -11.9243 209.497 51.5Z"
                          stroke="white" stroke-width="3"/>
                </svg>
                <svg fill="none" height="129" style="    left: -29px;
    position: absolute;
    height: 100px;" viewBox="0 0 249 129" width="249" xmlns="http://www.w3.org/2000/svg">
                    <path d="M246.976 12.5C246.976 75.9243 192.156 127.5 124.329 127.5C56.5013 127.5 1.68152 75.9243 1.68152 12.5C1.68152 -50.9243 56.5013 -102.5 124.329 -102.5C192.156 -102.5 246.976 -50.9243 246.976 12.5Z"
                          stroke="white" stroke-width="3"/>
                </svg>
            </div>


            <div class="justify-content-center body animate__animated animate__bounceIn animate__slower" >

                @php $review_code = \Settings::get('image_review_code') ? asset('assets/images/settings/'.\Settings::get('image_review_code')) :asset('assets/front-end/public/images/review_code.svg'); @endphp
                <img alt="{{\App\CPU\translate('app_name')}}" src="{{$review_code}}">

            </div>

            <div>
                <svg fill="none" height="215" style="
                   top: 20%;
    position: absolute;
    height: 132px;
    right: -3%;
" viewBox="0 0 174 215" width="174" xmlns="http://www.w3.org/2000/svg">
                    <path d="M124.22 213.14C56.3927 212.877 1.80038 161.089 2.07963 97.6652C2.35887 34.2414 57.4053 -17.1213 125.232 -16.8583C193.059 -16.5953 247.651 35.1924 247.372 98.6162C247.093 162.04 192.047 213.403 124.22 213.14Z"
                          stroke="white" stroke-width="3"/>
                </svg>
                <svg fill="none" height="162" style="top: 15%;
    position: absolute;
    height: 133px;
    right: -3%;
" viewBox="0 0 215 162" width="215" xmlns="http://www.w3.org/2000/svg">
                    <path d="M123.893 159.98C56.0662 159.717 1.47397 107.929 1.75321 44.5051C2.03246 -18.9187 57.0789 -70.2814 124.906 -70.0185C192.733 -69.7555 247.325 -17.9677 247.046 45.4561C246.767 108.88 191.72 160.243 123.893 159.98Z"
                          stroke="white" stroke-width="3"/>
                </svg>
            </div>

        </div>
    </section>

    <section id="our-client">
        <div class="container">

            <div class="sections-title text-center animate__animated animate__bounceIn animate__slower">

                @php $happy_customers= \Settings::get('happy_customers',120) @endphp

                <h2>{{\App\CPU\translate('What our customers say')}}</h2>
                <p>{{__('messages.More than 100 satisfied customers have nafhasiha',['count'=>$happy_customers])}}</p>
            </div>
            <br><br>
            <div class="row">

                <div class="owl-carousel client_slider ">

                    @isset($reviews)

                        @foreach($reviews as $review)
                            <div class="item">
                                <p>{{$review->comment}}</p>
                                <div class="d-flex justify-content-end" style="justify-content: end;">
                                    <span class="header-username">{{$review->name}}</span>
                                    <img alt="{{$review->name}}" class="avatar img-responsive review-image"
                                         src="{{$review->full_path_image}}">

                                </div>


                            </div>
                        @endforeach
                    @endisset

                        {{--   <div class="item">
                               <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                                   علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>
                               <div class="d-flex justify-content-end" style="justify-content: end;">
                                   <span class="header-username">  احمدرافت</span>
                                   <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                               </div>


                           </div>
                           <div class="item">
                               <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                                   علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>
                               <div class="d-flex justify-content-end" style="justify-content: end;">
                                   <span class="header-username">  احمدرافت</span>
                                   <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                               </div>
                           </div>
                           <div class="item">
                               <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                                   علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>

                               <div class="d-flex justify-content-end" style="justify-content: end;">
                                   <span class="header-username">  احمدرافت</span>
                                   <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                               </div>
                           </div>

                                            <div class="item">
                                                <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                                                    علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>
                                                <div class="d-flex justify-content-end" style="justify-content: end;">
                                                    <span class="header-username">  احمدرافت</span>
                                                    <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                                                </div>
                                            </div>

                                            <div class="item">
                                                <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                                                    علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>

                                                <div class="d-flex justify-content-end" style="justify-content: end;">
                                                    <span class="header-username">  احمدرافت</span>
                                                    <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                                                </div>
                                            </div>

                                            --}}
                </div>
                <div  class="client-slider-right" >
                    <img alt="" src="{{asset('assets/front-end/public/images/image_2023-10-25_11-06-59.png')}}">
{{--                    <img class="img-count-client-slider-right-icon" src="{{asset('assets/front-end/public/images/image_2023-10-25_10-11-30.png')}}">--}}

                </div>
                <div class="client-slider-left" >
                    <img alt=""src="{{asset('assets/front-end/public/images/image_2023-10-25_09-49-26.png')}}">

                    {{--                    <img class="img-count-client-slider-left-icon" src="{{asset('assets/front-end/public/images/image_2023-10-25_10-11-07.png')}}">--}}

                </div>




            </div>
        </div>
    </section>
    <section id="screen-app">

        <div class="container">

            <div class="sections-title text-center animate__animated animate__bounceIn animate__slower">


                <h2>{{\App\CPU\translate('Screen of the application')}}</h2>
            </div>
            <br><br>
            <div class="row">
                <div class="container" style="border-radius: 25px;
background: #FFF;padding: 25px">

                    <div class="col-md-4 animate__animated animate__bounceIn animate__slower">
                        <div class="slider_review owl-carousel owl-theme" style="    display: flex;
    justify-content: center;
    align-items: center;
    height: 618px;    position: relative;">
                            @forelse($screens as $screen)
                                @if($screen->getFirstMedia('images') )
                                    <div class="item">
                                        <div class="d-flex justify-content-end" style="justify-content: end;">
                                            <img alt="" class="avatar img-responsive" src="{{$screen->getFirstMedia('images')->getUrl()}}">
                                        </div>
                                    </div>
                                @endif
                            @empty
                                    <div class="item">

                                        <div class="d-flex justify-content-end" style="justify-content: end;">
                                            <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/screen.png')}}">
                                        </div>


                                    </div>
                                    <div class="item">

                                        <div class="d-flex justify-content-end" style="justify-content: end;">
                                            <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/screen.png')}}">

                                        </div>
                                    </div>
                            @endforelse




                        </div>

                    </div>
                    <div class="col-md-8 animate__animated animate__bounceIn animate__slower">
                        <img alt="" class="img-items" src="{{asset('assets/front-end/public/images/setting.svg')}}" style="    position: absolute;
    right: 0;">
                        <h3>{{\Settings::get('app_screen_user_title','تطبيق نفحصها لكل خدمات صيانة السيارات')}}</h3>
                        <p class="decs">{{\Settings::get('app_screen_user_desc','يعد تطبيق نفحصها نظاماً متكاملاً , قدرته فائقة لتلبي معظم إحتياجات مراكز الخدمة بكل
                            أنواعها مع
                            اختلاف أحجامها. كما يتميز البرنامج بسهولة الاستخدام والإنسيابية , مما يحسن من أداء')}}</p>
                    </div>
                </div>


            </div>
            <div class="w-100 text-center pt-3">
                    <p>
                        {{\Settings::get('app_download_user_title','لننزل تطبيق 😍 نفحصها ولا تنسى الإعجاب 👍🏻 وانتظر رأيك لكتابته ✍🏻 على متجر جوجل بلاي لتطوير أنفسنا أكثر')}}
                    </p>


            </div>
            <div class="row w-80 text-center pt-3">
                <a class="col" href="{{\Settings::get('app_download_user_google','#')}}">
                    <img alt="1" src="{{asset('assets/front-end/public/images/google-play-black.png')}}">
                </a>
                <a class="col" href="{{\Settings::get('app_download_user_app_store','#')}}">
                    <img alt="1" src="{{asset('assets/front-end/public/images/app-store-black.png')}}">
                </a>
            </div>
        </div>
    </section>

    <section id="contentUs">
        <div class="container">

            <div class="sections-title text-center animate__animated animate__bounceIn animate__slower">
                <h2>{{\App\CPU\translate('contentUs')}}</h2>
                <p>{{\App\CPU\translate('You can easily contact us to request your service.')}}</p>
            </div>
            <div class="row">
                <div class="col-md-6 animate__animated animate__bounceIn animate__slower">
                    <h3 class="text-center">{{__('site.You Can Contact With Me')}}</h3>
                    <br>
                    <br>
                    <form method="POST" action="{{route('front.contact_us.store')}}" >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="contact-name"
                                        placeholder="{{__('site.Name')}}"
                                    />
                                    @error('contact-name')

                                    <span class=" alert alert-danger">
                                                {{ $message }}
                                            </span>

                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="contact-phone"
                                        placeholder="{{__('site.Phone')}}"
                                    />
                                    @error('contact-phone')

                                    <span class=" alert alert-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
{{--                            <div class="col-md-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input--}}
{{--                                        class="form-control"--}}
{{--                                        type="email"--}}
{{--                                        name="contact-email"--}}
{{--                                        placeholder="{{__('site.E-mail')}}"--}}
{{--                                    />--}}
{{--                                    @error('contact-email')--}}

{{--                                    <span class=" alert alert-danger">--}}
{{--                                            {{ $message }}--}}
{{--                                        </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-md-12">
                                <div class="form-group">

                                    <textarea
                                        class="form-control"
                                        name="contact-message"
                                        cols="10"
                                        rows="5"
                                        placeholder="{{__('site.Message_service')}}" style="text-align: end; font-size: 15px"
                                    ></textarea>
                                    @error('contact-message')

                                    <span class=" alert alert-danger">
                                                    {{ $message }}
                                                </span>

                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-danger">{{__('site.GET IT NOW')}}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-md-6 animate__animated animate__bounceIn animate__slower">
                    <h2>@lang('site.Get in Touch')</h2>
                    <br>
                    <br>

                    <div class="item">
                        <h4><span>: </span>@lang('site.Our Location') </h4>
                        <p> {{\Settings::get('location',' الرياض شارع الصفا عمارة الصفا')}}</p>
                    </div>
                    <hr>

                    <div class="item">
                        <h4><span>: </span>@lang('site.Contact Phone Number') </h4>
                        <p> {{\Settings::get('mobile_support','+02345768896')}} , {{\Settings::get('mobile_support2','+02345768896')}}</p>

                    </div>
                    <hr>
                    <div class="item">
                        <h4><span>: </span>@lang('site.Our Email Address') </h4>
                        <p>{{\Settings::get('email_support','test@test.com')}}</p>

                    </div>

                    <div class="socail justify-content-center d-flex">
                        <ul>
                            @isset($icons)
                                @foreach($icons as $icon)
                                    <li>
                                        <a href="{{$icon->link}}">
                                            {{--                                                <i class="fa fa-{{$icon->title}}"></i>--}}
                                            <img src="{{asset('assets/images/'.$icon->image)}}" style=" border-radius: 50%;width: 50px;height: 50px">
                                        </a>
                                    </li>
                                @endforeach
                            @endisset


                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="newspaper">
        <div class="container animate__animated animate__bounceIn animate__slower">
            <div class="first " style="position: relative">
                <svg fill="none" height="168" style="    left: -59px;
    position: absolute;
    height: 132px;" viewBox="0 0 211 168" width="211" xmlns="http://www.w3.org/2000/svg">
                    <path d="M209.497 51.5C209.497 114.924 154.677 166.5 86.8499 166.5C19.0223 166.5 -35.7975 114.924 -35.7975 51.5C-35.7975 -11.9243 19.0223 -63.5 86.8499 -63.5C154.677 -63.5 209.497 -11.9243 209.497 51.5Z"
                          stroke="white" stroke-width="3"/>
                </svg>
                <svg fill="none" height="129" style="    left: -29px;
    position: absolute;
    height: 100px;" viewBox="0 0 249 129" width="249" xmlns="http://www.w3.org/2000/svg">
                    <path d="M246.976 12.5C246.976 75.9243 192.156 127.5 124.329 127.5C56.5013 127.5 1.68152 75.9243 1.68152 12.5C1.68152 -50.9243 56.5013 -102.5 124.329 -102.5C192.156 -102.5 246.976 -50.9243 246.976 12.5Z"
                          stroke="white" stroke-width="3"/>
                </svg>
            </div>


            <svg fill="none" height="178" style="    height: 99px;
    position: absolute;
    bottom: 8%;
    left: 12%;" viewBox="0 0 169 178" width="169" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd"
                      d="M152.164 108.795C151.187 107.835 149.635 107.85 148.667 108.812C137.064 119.973 124.847 125.649 112.39 125.791C100.261 125.928 88.0072 120.666 75.9846 110.24C73.3123 107.965 69.1815 103.227 65.5478 97.6325C68.5967 93.2469 70.6284 88.0534 71.6316 83.0498C73.1112 75.66 72.0338 68.8652 69.5952 65.5C68.244 63.6365 66.5016 62.4946 64.4643 62.0185C61.8566 61.4129 58.3132 61.9532 54.2465 65.4026C48.6357 70.1842 47.2719 77.0115 48.948 84.5253C49.8333 88.4753 51.6207 92.6204 53.8471 96.6039C51.2709 98.6603 48.0658 99.8499 44.1652 99.4216C35.9084 98.571 29.7176 91.7911 25.314 83.8142C21.495 76.9133 16.8943 66.7187 18.1104 58.6181C18.5222 55.8653 19.7264 53.4994 17.4545 51.2496C13.9761 47.7686 10.7876 51.3719 10.2168 55.1529C8.99478 63.2752 11.8899 73.0593 14.7472 80.5521C19.9911 94.3409 29.8315 106.743 43.1188 108.452C49.3479 109.225 54.6335 107.569 58.9148 104.491C62.9786 110.131 67.3615 114.797 70.2373 117.139C84.4668 128.493 98.7875 133.774 112.643 132.97C126.485 132.175 139.91 125.379 152.181 112.291C153.141 111.315 153.14 109.755 152.164 108.795ZM59.8689 86.6834C59.0048 84.3918 58.4209 82.1404 58.2496 79.9995C58.0364 77.2771 58.5615 74.7525 60.6833 72.9584C61.0429 72.659 61.4808 72.241 61.8403 71.9416C62.1107 72.887 62.5785 74.7465 62.5854 76.2846C62.5849 79.7179 61.8044 83.8035 60.2378 87.6086C60.0905 87.2898 59.9728 86.9906 59.8689 86.6834Z"
                      fill="white"
                      fill-rule="evenodd"/>
                <path clip-rule="evenodd"
                      d="M62.1668 71.6806C62.41 71.8156 62.643 71.9014 62.6244 71.7102C62.5958 71.4697 62.4342 71.5079 62.1668 71.6806Z"
                      fill="white"
                      fill-rule="evenodd"/>
                <path clip-rule="evenodd"
                      d="M145.365 114.224C144.235 116.75 143.142 119.436 142.363 121.998C141.164 125.994 140.65 129.651 141.636 132.313C141.944 133.64 143.282 134.465 144.602 134.143C145.93 133.836 146.754 132.497 146.433 131.177C146.365 130.519 146.594 129.835 146.812 129.067C147.309 127.257 148.42 125.366 149.63 123.328C151.176 120.728 153.036 118.13 154.623 115.727C156.067 113.553 157.27 111.537 158.036 109.938C159.246 107.422 159.311 105.31 159.038 104.073C158.961 103.749 156.28 98.9457 151.693 100.859C150.054 101.549 145.99 103.509 145.789 103.607C143.348 104.943 138.813 107.359 134.494 108.623C133.129 109.025 131.812 109.382 130.611 109.433C130.547 109.323 130.484 109.213 130.406 109.11C129.285 107.518 127.104 107.143 125.504 108.251C124.617 108.781 123.46 110.275 123.151 111.17C122.557 112.908 123.218 114.307 124.84 115.463C127.05 117.033 130.247 117.482 133.84 117.188C137.617 116.862 141.896 115.529 145.365 114.224ZM149.354 106.157C149.622 105.635 149.713 105.252 149.352 105.167C148.947 105.07 149.217 105.759 149.354 106.157Z"
                      fill="white"
                      fill-rule="evenodd"/>
            </svg>
            <div class="justify-content-center body ">

                <h2>{{\App\CPU\translate('Subscribe to the newsletter')}}</h2>
                <p>{{\App\CPU\translate('Subscribe to receive all new services and developments in Nakhsa')}}</p>

                    <div style="position: relative">
                        <form action="{{route('front.subscribe.store')}}"  method="Post" class="row">
                            @csrf
                            <input id="myInput" name="email" type="email" required  placeholder="{{\App\CPU\translate('email')}}" >
                            <button>
                                {{\App\CPU\translate('Subscribe')}}
                            </button>
                        </form>
                    </div>

            </div>

            <div>
                <svg fill="none" height="215" style="    top: -30px;
    position: absolute;
    height: 132px;
    right: 0;" viewBox="0 0 174 215" width="174" xmlns="http://www.w3.org/2000/svg">
                    <path d="M124.22 213.14C56.3927 212.877 1.80038 161.089 2.07963 97.6652C2.35887 34.2414 57.4053 -17.1213 125.232 -16.8583C193.059 -16.5953 247.651 35.1924 247.372 98.6162C247.093 162.04 192.047 213.403 124.22 213.14Z"
                          stroke="white" stroke-width="3"/>
                </svg>
                <svg fill="none" height="162" style="top: -74px;
    position: absolute;
    height: 132px;
    right: 10px;" viewBox="0 0 215 162" width="215" xmlns="http://www.w3.org/2000/svg">
                    <path d="M123.893 159.98C56.0662 159.717 1.47397 107.929 1.75321 44.5051C2.03246 -18.9187 57.0789 -70.2814 124.906 -70.0185C192.733 -69.7555 247.325 -17.9677 247.046 45.4561C246.767 108.88 191.72 160.243 123.893 159.98Z"
                          stroke="white" stroke-width="3"/>
                </svg>
            </div>

        </div>
    </section>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // This is the bare minimum JavaScript. You can opt to pass no arguments to setup.
        const player = new Plyr('#player');

        // Expose
        window.player = player;

        // Bind event listener
        function on(selector, type, callback) {
            document.querySelector(selector).addEventListener(type, callback, false);
        }

        // Play
        on('.js-play', 'click', () => {
            player.play();
        });

        // Pause
        on('.js-pause', 'click', () => {
            player.pause();
        });

        // Stop
        on('.js-stop', 'click', () => {
            player.stop();
        });

        // Rewind
        on('.js-rewind', 'click', () => {
            player.rewind();
        });

        // Forward
        on('.js-forward', 'click', () => {
            player.forward();
        });
    });
</script>

@endsection



