@extends('back-end.layouts.app')
@section('title')
 @lang('lang.providers') | {{$provider->name}}

@endsection
@section('styles')
    <style>
        input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            visibility: hidden;
            display: none;
        }

        .check {
            position: relative;
            display: block;
            width: 70px;
            height: 30px;
            background-color: #f46a6a;
            cursor: pointer;
            border-radius: 20px;
            overflow: hidden;
            transition: ease-in 0.5s;
        }

        input:checked[type="checkbox"] ~ .check {
            background-color: #34c38f;
            /*   box-shadow: 0 0 0 1200px #092c3e; */
        }

        .check:before {
            content: '';
            position: absolute;
            top: 3px;
            left: 4px;
            background-color: #eff2f7;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            transition: all 0.5s;
        }

        input:checked[type="checkbox"] ~ .check:before {
            transform: translateX(-50px);
        }

        .check:after {
            content: '';
            position: absolute;
            top: 3px;
            right: 4px;
            background-color: #eff2f7;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            transform: translateX(50px);
            transition: all 0.5s;

        }

        input:checked[type="checkbox"] ~ .check:after {
            transform: translateX(0px);
        }
        .btn-modal {
            cursor: pointer;
        }
        td{
            text-align: center;
        }
        div#map {
            margin: 25px;
            height: 400px;
            width: 95%;
            border-radius: 10px !important;
            text-align: center;
        }

        div#map img {
            width:90px;
            height:90px;
            margin:5px;
            border-radius: 10px !important;
        }
        .pending-info-map {
            display: inline-flex;
        }
        .img-info-map img {
            width: 50px !important;
            height: 50px !important;
            border-radius: 50% !important;
        }
        span.font-label-info-map {
            padding: 5px;
            /* color: #000; */
            font-weight: 400;
            font-size: 14px;
        }
        span.font-value-info-map {
            padding: 5px 0;
        }


        .rate-info-map .star{
            font-family: Wingdings;
            font-size: 18px;
            color: #ccc;
            display: inline-block;


        }
        .rate-info-map .star-mark {
            color: gold;
        }
        .rate-info-map .star:first-child:before{
            color: transparent;

        }
        .rate-info-map .star::before{
            font-family: Wingdings;
            font-size: 18px;
            content: "\2605";
        }
        .icon-order-number {
            font-weight: 700;
            font-size: 18px;
            padding: 0 15px !important;
        }
        img.img-number {
            padding: 5px;
            border-radius: 50%;
            background-color: #F2F2F6;
        }
        table {
            width: 100%;
        }
        .table-order {
            border: 1px solid #e5e5e5;
            border-radius: 15px;
            padding: 0;
        }
        td {
            font-size: 14px;
            font-weight: 500;
            color: #144d79;
            padding: 15px 0;
        }
        .status_0{
            padding: 2px 10px;
            color: #fff;
            background-color: #af0000a3;
        }
        .status_1{
            padding: 2px 10px;
            color: #fff;
            background-color: rgba(0, 175, 108, 0.64);
        }
        td.title-table {
            font-size: 15px;
            font-weight: 700;
            color: #25364F;
            padding: 15px 0;
        }
        tr.tr-sp {
            background-color: #f6f5fd;
        }
    </style>
@endsection
@section('sli_li')
    <span class="parent">  < <a href="{{route("admin.provider.index")}}"> {{__('lang.providers')}} / </a> </span>

    {{$provider->name}}
@endsection
@section('content')




    <div class="row">
        <div class="container-fluid mt-4">
            <div class="col-md-12">
                <div class="row card mt-3 p-3 ">
                    <div class="icon-order-number">
                        <img class="img-number" src="{{asset('assets/back-end/images/design/icon-sho-order.svg')}}" >
                        {{\App\CPU\translate('name')}} : {{$provider->name}}
                    </div>
                    <hr class="hr-new-order">
                    <div class="col-md-12 table-order ">
                        <table>
                            <tr>
                                <td class="title-table">@lang('lang.phone')</td>
                                <td class="value-table">{{$provider->phone}}</td>
                                <td class="title-table">@lang('lang.email')</td>
                                <td class="value-table">{{$provider->email}}</td>
                            </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.status')</td>
                                <td class="value-table">

                                    <span class="span-status status_{{$provider->is_active}}"> {{__('lang.status_'.$provider->is_active)}}</span>

                                </td>

                                <td class="title-table">@lang('lang.services_from_home')</td>
                                <td class="value-table">

                                    <span class="span-status status_{{$provider->is_active}}"> {{__('lang.status_'.$provider->services_from_home)}}</span>

                                </td>
                             </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.provider_type')</td>
                                <td class="value-table">{{__('lang.'.$provider->provider_type)}}</td>

                                <td class="title-table">@lang('lang.service_title')</td>
                                <td class="value-table">
                                    @foreach($provider->categories as $category)
                                        {{$category->title}}
                                        @if(!$loop->last)  |   @endif
                                    @endforeach

                                </td>
                             </tr>
                            <tr>
                                <td class="title-table">@lang('lang.country')</td>
                                <td class="value-table">{{$provider->country?->title}}</td>
                                <td class="title-table">@lang('lang.city')</td>
                                <td class="value-table">{{$provider->city?->title}}</td>

                            </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.area')</td>
                                <td class="value-table">{{$provider->area?->title}}</td>
                                <td class="title-table">@lang('lang.address')</td>
                                <td class="value-table">{{$provider->address}}</td>
                            </tr>
                            <tr>
                                <td class="title-table">@lang('lang.commercial_register')</td>
                                <td class="value-table">
                                    @foreach ($provider->getMedia('commercial_register') as $image)
                                        <div class="image-order" >
                                            <img src="{{ $image->getUrl()}} " height="50px" width="50px"></div>
                                    @endforeach
                                </td>
                                <td class="title-table">{{\App\CPU\translate('image')}}</td>
                                <td class="value-table">

                                   @foreach ($provider->getMedia('images') as $image)
                                   <div class="image-order" >
                                            <img src="{{ $image->getUrl()}} " height="50px" width="50px"></div>
                                    @endforeach
                                </td>
                            </tr>


                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection
