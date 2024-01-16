@extends('front-end.layouts.app-info')
@section('title')
    @if($Info->type=='who-are-we')
        @lang('site.Read About Our')
    @else
        {{$Info->title}}
    @endif
@endsection
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
        #package .items img {
            width: 150px;
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

        <div class="head-header">
            <div class="container">
                <div class="row ">
                    <div class="image col">
                        @if($Info->img)
                            <img src="{{asset('assets/images/'.$Info->img)}}" alt="{{$Info->title}}">
                        @else
                            <img alt="" src="{{asset('assets/front-end/public/images/provider/header_two.svg')}}">
                        @endif
                    </div>
                    <div class="text col">
                        <h3>
                            {{$Info->title}}
                        </h3>

                        <p class="">
                            {!! $Info->description  !!}
                        </p>
                    </div>
                </div>
            </div>


        </div>
    </section>
</header>




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

