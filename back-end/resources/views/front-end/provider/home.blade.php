@extends('front-end.provider.layouts.app')
@section('title',"شركة (مزود خدمة)")
@section('styles')
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
</style>
@endsection
@section('content')
    <section class="container" style="    padding: 70px 0;">
        <div class="count-section pb-3">
            <div class="overlay"></div>

            <div class="row">
                <div class="d-flex items" style="margin: auto;">
                    <div class="item animate__animated animate__bounceIn animate__slower">

                        <h1><span>100</span>+</h1>
                        <p>عملاء سعداء
                        </p>

                    </div>
                    <div class="item animate__animated animate__bounceIn animate__slower">
                        <h1><span>70</span>+</h1>
                        <p>عربيات تم اصلاحه
                        </p>
                    </div>
                    <div class="item animate__animated animate__bounceIn animate__slower">
                        <h1><span>80</span>+</h1>
                        <p>عدد السطحات

                        </p>
                    </div>
                    <div class="item  animate__animated animate__bounceIn animate__slower">
                        <h1><span>90</span>+</h1>
                        <p>عدد الورش لدينا
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

            <div class="sections-title text-center">


                <h2>شرح تطبيق مزد الخدمة</h2>
                <p>لوريم إيبسوم هو ببساطة نص شكلي للطباعة.</p>
            </div>
            <div class="items">
                <div class="item">
                    <img alt="1" src="{{asset('assets/front-end/public/images/service/maintenance.svg')}}">
                    <h4>صيانة</h4>
                </div>
                <div class="item">
                    <img alt="1" src="{{asset('assets/front-end/public/images/service/frame.svg')}}">
                    <h4>سطحة</h4>
                </div>
                <div class="item">
                    <img alt="1" src="{{asset('assets/front-end/public/images/service/group-1171274898.svg')}}">
                    <h4>الفحص الدورى</h4>
                </div>
                <div class="item">
                    <img alt="1" src="{{asset('assets/front-end/public/images/service/frame.svg')}}">
                    <h4>حواجز السيارات</h4>
                </div>
                <div class="item">
                    <img alt="1" src="{{asset('assets/front-end/public/images/service/group-1171274959.svg')}}">
                    <h4>استشارات اعطال</h4>
                </div>

            </div>
        </div>
    </section>

    <section id="app-video">
        <div class="sections-title text-center">
            <h2>شرح التطبيق</h2>
            <p>شرح تحميل وكيفية التعامل مع التطبيق</p>
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
                            <video controls crossorigin playsinline poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="player">
                                <!-- Video files -->
                                <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4" size="576">
                                <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4" size="720">
                                <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" type="video/mp4" size="1080">
                                <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1440p.mp4" type="video/mp4" size="1440">

                                <!-- Caption files -->
                                <track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt"
                                       default>
                                <track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt">

                                <!-- Fallback for browsers that don't support the <video> element -->
                                <a href="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" download>Download</a>
                            </video>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>


    <section id="our-partners">
        <div class="container">

            <div class="sections-title text-center">
                <h2>شركائنا</h2>
                <p>اكتر من30 الف مركز صيانة خاص بينا</p>
            </div>
            <!--        <div class="row">-->


            <div class="items   partners_slider owl-carousel owl-theme" style="    overflow: hidden;
     ">
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
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/service/image-2.svg')}}">
                </div>
                <div>
                    <img alt="Image 1" src="{{asset('assets/front-end/public/images/slider2.png')}}">
                </div>

            </div>
            <!--        </div>-->

        </div>


    </section>


    <section id="package">

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
    </section>
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


                <img alt="" src="{{asset('assets/front-end/public/images/review_code.svg')}}">

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


                <h2>ماذا يقول عملائنا</h2>
                <p>اكتر من30 الف مركز صيانة خاص بينا</p>
            </div>
            <br><br>
            <div class="row">

                <div class="owl-carousel client_slider ">

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

                    <div class="item">
                        <p>شركة محترمة الخدمة تتم علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية شركة محترمة الخدمة تتم
                            علي اكمل وجه ومركز الصيانة لديهم ذو خبرة عالية </p>

                        <div class="d-flex justify-content-end" style="justify-content: end;">
                            <span class="header-username">  احمدرافت</span>
                            <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/avtar.svg')}}">

                        </div>
                    </div>
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

            <div class="sections-title text-center">


                <h2>شاشة من التطبيق</h2>
                <p>اكتر من30 الف مركز صيانة خاص بينا</p>
            </div>
            <br><br>
            <div class="row">
                <div class="container" style="border-radius: 25px;
background: #FFF;padding: 25px">

                    <div class="col-md-4">
                        <div class="slider_review owl-carousel owl-theme" style="    display: flex;
    justify-content: center;
    align-items: center;
    height: 618px;    position: relative;">

                            <div class="item">

                                <div class="d-flex justify-content-end" style="justify-content: end;">
                                    <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/provider/screen.png')}}">
                                </div>


                            </div>
                            <div class="item">

                                <div class="d-flex justify-content-end" style="justify-content: end;">
                                    <img alt="" class="avatar img-responsive" src="{{asset('assets/front-end/public/images/provider/screen.png')}}">

                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="col-md-8">
                        <img alt="" class="img-items" src="{{asset('assets/front-end/public/images/setting.svg')}}" style="    position: absolute;
    right: 0;">
                        <h3>تطبيق نفحصها لكل خدمات صيانة السيارات</h3>
                        <p class="decs">يعد تطبيق نفحصها نظاماً متكاملاً , قدرته فائقة لتلبي معظم إحتياجات مراكز الخدمة بكل
                            أنواعها مع
                            اختلاف أحجامها. كما يتميز البرنامج بسهولة الاستخدام والإنسيابية , مما يحسن من أداء</p>
                    </div>
                </div>


            </div>
            <div class="w-100 text-center  pt-3">
                <p>
                    لننزل تطبيق 😍 نفحصها ولا تنسى الإعجاب 👍🏻 وانتظر رأيك لكتابته ✍🏻 على متجر جوجل بلاي لتطوير أنفسنا أكثر
                </p>


            </div>
            <div class="row w-80 text-center pt-3">
                <a class="col" href="#">
                    <img alt="1" src="{{asset('assets/front-end/public/images/google-play-black.png')}}">
                </a>
                <a class="col" href="#">
                    <img alt="1" src="{{asset('assets/front-end/public/images/app-store-black.png')}}">
                </a>
            </div>
        </div>
    </section>

    <section id="contentUs">
        <div class="container">

            <div class="sections-title text-center">
                <h2>تواصل معانا</h2>
                <p>يمكنك التواصل معنا بسهولة لطلب خدمتك. </p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2>نموذج الاتصال</h2>
                    <br>
                    <br>

                    <form action="#" method="post">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" placeholder="الاسم الكامل">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" placeholder="الايميل">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="form-control" placeholder="رقم المحمول">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                <textarea class="form-control" cols="10" id="" name=""
                                          placeholder="أكتب رسالتك" rows="5"
                                          style="text-align: end; font-size: 15px"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-danger">ارسال</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-md-6">
                    <h2>ابقي على تواصل</h2>
                    <br>
                    <br>

                    <div class="item">
                        <h4><span>: </span>العنوان </h4>
                        <p> الرياض شارع الصفا عمارة الصفا</p>
                    </div>
                    <hr>

                    <div class="item">
                        <h4><span>: </span>تليفون </h4>
                        <p>+06898976543 ,+02345768896</p>

                    </div>
                    <hr>
                    <div class="item">
                        <h4><span>: </span>الايميل </h4>
                        <p>www.nafhasuha.net</p>

                    </div>

                    <div class="socail justify-content-center d-flex">
                        <ul>
                            <li>
                                <a href="#">
                                    <svg fill="none" height="73" viewBox="0 0 72 73" width="72"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="36.3345" cy="36.5255" fill="#0077B5" r="35.5526"/>
                                        <path clip-rule="evenodd"
                                              d="M27.054 19.234C27.054 21.4473 25.3688 23.2385 22.7333 23.2385C20.2006 23.2385 18.5153 21.4473 18.5675 19.234C18.5153 16.9131 20.2005 15.1741 22.7838 15.1741C25.3687 15.1741 27.0035 16.9131 27.054 19.234ZM18.7793 52.1827V26.402H26.7916V52.181H18.7793V52.1827Z"
                                              fill="white"
                                              fill-rule="evenodd"/>
                                        <path clip-rule="evenodd"
                                              d="M33.2151 34.6443C33.2151 31.4286 33.1091 28.6872 33.0032 26.4201H39.9626L40.3325 29.952H40.4906C41.5451 28.3173 44.1806 25.8416 48.4508 25.8416C53.7217 25.8416 57.6756 29.3212 57.6756 36.9097V52.2008H49.6633V37.9137C49.6633 34.5905 48.5045 32.325 45.6051 32.325C43.3901 32.325 42.0732 33.8538 41.5468 35.3288C41.3349 35.8569 41.2306 36.5935 41.2306 37.3335V52.2008H33.2183V34.6443H33.2151Z"
                                              fill="white"
                                              fill-rule="evenodd"/>
                                    </svg>
                                </a>
                            </li>
                            <li><a href="#">
                                    <svg fill="none" height="73" viewBox="0 0 72 73" width="72"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="35.7828" cy="36.5255" fill="#1DA1F2" r="35.5526"/>
                                        <path clip-rule="evenodd"
                                              d="M57.1138 22.8578C55.5453 23.5546 53.8574 24.0239 52.087 24.2357C53.8944 23.1522 55.2823 21.4372 55.935 19.3938C54.2456 20.3977 52.37 21.1244 50.3778 21.5183C48.7808 19.8176 46.5042 18.7539 43.9887 18.7539C39.1552 18.7539 35.2362 22.673 35.2362 27.5064C35.2362 28.1918 35.3144 28.8601 35.4637 29.5015C28.1887 29.1374 21.7399 25.6521 17.4227 20.3565C16.669 21.6491 16.2381 23.1522 16.2381 24.7562C16.2381 27.7922 17.781 30.4713 20.1316 32.0412C18.6954 31.9957 17.3473 31.6018 16.1656 30.9462V31.0557C16.1656 35.2976 19.1846 38.8341 23.1861 39.6404C22.4523 39.8395 21.6788 39.9475 20.881 39.9475C20.3165 39.9475 19.7676 39.8921 19.2329 39.7897C20.3463 43.2665 23.58 45.7977 27.4095 45.8688C24.4147 48.2151 20.6407 49.6158 16.5396 49.6158C15.8315 49.6158 15.1347 49.5745 14.4507 49.4921C18.3242 51.9749 22.9244 53.4253 27.8673 53.4253C43.9659 53.4253 52.7696 40.0883 52.7696 28.5231C52.7696 28.1434 52.7625 27.7652 52.744 27.3912C54.4561 26.1541 55.9407 24.6126 57.1138 22.8578Z"
                                              fill="white"
                                              fill-rule="evenodd"/>
                                    </svg>
                                </a></li>
                            <li><a href="#">
                                    <svg fill="none" height="79" viewBox="0 0 79 79" width="79"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="39.4963" cy="39.7929" fill="#00E676" r="39.2072"/>
                                        <path d="M40.2269 14.8588C27.3786 14.8588 16.9069 25.5441 16.9069 38.6547C16.9069 42.8567 17.9894 46.9388 20.0131 50.5406L16.7187 62.8827L29.0729 59.5691C32.485 61.466 36.3206 62.4745 40.2269 62.4745C53.0752 62.4745 63.5469 51.7892 63.5469 38.6787C63.5469 32.3155 61.1231 26.3365 56.7226 21.8463C54.5653 19.6226 51.9956 17.8595 49.1634 16.6598C46.3313 15.4601 43.2934 14.8479 40.2269 14.8588ZM40.2504 18.8688C45.4274 18.8688 50.275 20.9338 53.9459 24.6797C55.7475 26.5175 57.1759 28.7001 58.1491 31.1023C59.1222 33.5046 59.6211 36.0792 59.6171 38.6787C59.6171 49.5801 50.9103 58.4405 40.2269 58.4405C36.7442 58.4405 33.3321 57.5041 30.3671 55.6791L29.6611 55.2709L22.3192 57.2399L24.2724 49.9403L23.8017 49.1719C21.8579 46.0231 20.8297 42.3761 20.8367 38.6547C20.8603 27.7532 29.5435 18.8688 40.2504 18.8688ZM31.9673 27.6572C31.5907 27.6572 30.9554 27.8012 30.4142 28.4015C29.8965 29.0018 28.3669 30.4666 28.3669 33.372C28.3669 36.3015 30.4612 39.1109 30.7201 39.5191C31.0495 39.9273 34.8617 45.9303 40.7211 48.4756C42.1094 49.1239 43.1919 49.4841 44.039 49.7482C45.4274 50.2044 46.6981 50.1324 47.71 49.9883C48.8395 49.8202 51.1456 48.5476 51.6398 47.1549C52.134 45.7622 52.134 44.5856 51.9928 44.3215C51.828 44.0814 51.4515 43.9373 50.8632 43.6732C50.275 43.337 47.4041 41.8963 46.8864 41.7042C46.3452 41.5121 46.0157 41.416 45.5686 41.9923C45.1921 42.5926 44.0626 43.9373 43.7331 44.3215C43.3802 44.7297 43.0507 44.7777 42.4859 44.4896C41.8741 44.1774 39.9916 43.5531 37.7796 41.5361C36.0382 39.9513 34.8852 38.0063 34.5322 37.406C34.2498 36.8297 34.5087 36.4696 34.7911 36.2054C35.0499 35.9413 35.4264 35.5091 35.6617 35.1489C35.9677 34.8127 36.0618 34.5486 36.25 34.1644C36.4383 33.7562 36.3442 33.42 36.203 33.1319C36.0618 32.8678 34.8852 29.8903 34.391 28.7137C33.9204 27.5611 33.4498 27.7052 33.0732 27.6812C32.7438 27.6812 32.3673 27.6572 31.9673 27.6572Z"
                                              fill="white" stroke="white" stroke-width="0.5"/>
                                    </svg>

                                </a></li>
                            <li><a href="#">
                                    <svg fill="none" height="73" viewBox="0 0 72 73" width="72"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="35.6917" cy="36.5255" fill="#3B5998" r="35.5526"/>
                                        <path clip-rule="evenodd"
                                              d="M45.1487 23.9864C44.0406 23.7648 42.5441 23.5993 41.6029 23.5993C39.0544 23.5993 38.8888 24.7073 38.8888 26.4802V29.6362H45.2595L44.7042 36.1738H38.8888V56.0588H30.9122V36.1738H26.8124V29.6362H30.9122V25.5925C30.9122 20.0535 33.5155 16.9509 40.0517 16.9509C42.3225 16.9509 43.9846 17.2833 46.1446 17.7266L45.1487 23.9864Z"
                                              fill="white"
                                              fill-rule="evenodd"/>
                                    </svg>
                                </a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="newspaper">
        <div class="container">
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


            <svg fill="none" height="178" style="    height: 99px;
    position: absolute;
    bottom: 30%;
    left: 20%;" viewBox="0 0 169 178" width="169" xmlns="http://www.w3.org/2000/svg">
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
            <div class="justify-content-center body">

                <h2>اشتراك في النشرة البريدية</h2>
                <p>اشترك ليصلك كل ماهو جديد من خدمات وتطورات في نفحصها</p>

                <div style="position: relative">
                    <input id="myInput" placeholder="البريدالالكتروني" type="text">
                    <button id="myButton" type="button">ارسال</button>
                </div>
            </div>

            <div>
                <svg fill="none" height="215" style="    top: 135px;
    position: absolute;
    height: 132px;
    right: 170px;" viewBox="0 0 174 215" width="174" xmlns="http://www.w3.org/2000/svg">
                    <path d="M124.22 213.14C56.3927 212.877 1.80038 161.089 2.07963 97.6652C2.35887 34.2414 57.4053 -17.1213 125.232 -16.8583C193.059 -16.5953 247.651 35.1924 247.372 98.6162C247.093 162.04 192.047 213.403 124.22 213.14Z"
                          stroke="white" stroke-width="3"/>
                </svg>
                <svg fill="none" height="162" style="    top: 110px;
    position: absolute;
    height: 132px;
    right: 190px;
" viewBox="0 0 215 162" width="215" xmlns="http://www.w3.org/2000/svg">
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



