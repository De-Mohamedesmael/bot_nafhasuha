@extends('front.layouts.front')
@section('title')
    @lang('site.FAQ')
@endsection
@section('style')
<style>
    .accordion-button:not(.collapsed){
        background: linear-gradient(264.47deg, #285b89 29.39%, #41888b 93.49%);
        color: #fff;
    }
    button.accordion-button.collapsed, h2#headingThree3 {
        background-color: #f8f8f800 !important;

    }

</style>
@endsection
@section('content')

    <!-- start sub header -->

    <div class="Sub_Header">
        @if($Info->img)
            <img src="{{asset('assets/images/'.$Info->img)}}" alt="{{$Info->title}}">
        @else
            <img src="{{asset('assets/front-end/img/subHeader.svg')}}" alt="" />
        @endif

        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="Sub_Content">
                        <div class="us_head text-center wow slideInUp " data-wow-duration="1s" data-wow-delay="0s" data-wow-offset="10">
                            <h1>{{$Info->title}}</h1>

                            <span class="span_border"><span class="span_dotted"></span></span>
                            @if($Info->video)
                                <iframe
                                    width="727"
                                    height="409"
                                    src="{{$Info->video}}"
                                    title="{{$Info->title}}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            @endif
                            <p>
                                {!! $Info->description  !!}

                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
{{--
    <!-- Start Accordion Area  -->
    <div class=" mt-5">
        <div class="container">
            <div class="row g-5">
                @foreach($data as $q)
                    @php($is_show = $loop->iteration <= 2)

                        <div class="col-lg-6">
                            <div class="rbt-accordion-style accordion">
                                <div class="rbt-accordion-style rbt-accordion-04 accordion">
                                    <div class="accordion" id="accordionExamplec3">
                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingThree{{$loop->iteration}}">
                                                <button class="accordion-button {{$is_show?'':'collapsed'}}" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThree{{$loop->iteration}}"
                                                        aria-expanded="{{$is_show?'true':'false'}}" aria-controls="collapseThree{{$loop->iteration}}">

                                                 {{$loop->iteration}} -  {{$q->title}}
                                                </button>
                                            </h2>
                                            <div id="collapseThree{{$loop->iteration}}" class="accordion-collapse collapse {{$is_show?'show':''}}"
                                                 aria-labelledby="headingThree{{$loop->iteration}}" data-bs-parent="#accordionExamplec3">
                                                <div class="accordion-body card-body">

                                                    {!!  $q->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                @endforeach


            </div>
        </div>
    </div>

--}}
@endsection
@section('script')

@endsection
