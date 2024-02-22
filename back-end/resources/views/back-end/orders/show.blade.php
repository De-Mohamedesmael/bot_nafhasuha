@extends('back-end.layouts.app')
@php
    $transaction=$OrderService->transaction;
    $class='';
     switch ($OrderService->status){
            case 'pending':
                $class='pending';
                break;
            case 'approved':
            case 'PickUp':
                $class='approved';
                break;
            case 'completed':
            case  'received':
                $class='completed';
                break;

            case 'canceled':
            case 'declined':
                $class='canceled';
                break;
        }
@endphp
@section('title')
 @lang('lang.orders') | {{$transaction->invoice_no}}

@endsection
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">


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
        td.title-table {
            font-size: 15px;
            font-weight: 700;
            color: #25364F;
            padding: 15px 0;
        }
        tr.tr-sp {
            background-color: #f6f5fd;
        }
        a.image-popup {
            cursor: zoom-in;
        }
    </style>
@endsection
@section('sli_li')
    <span class="parent">  < <a href="{{route("admin.order.index")}}"> {{__('lang.orders')}} / </a> </span>

   {{$transaction->invoice_no}}
@endsection
@section('content')




    <div class="row">
        <div class="container-fluid mt-4">
            <div class="col-md-12">
                <div class="row card mt-3 p-3 ">
                    <div class="icon-order-number">
                        <img class="img-number" src="{{asset('assets/back-end/images/design/icon-sho-order.svg')}}" >
                        {{\App\CPU\translate('order_number')}} {{$OrderService->id}}
                    </div>
                    <hr class="hr-new-order">
                    <div class="col-md-12 table-order ">
                        <table>
                            <tr>
                                <td class="title-table">@lang('lang.invoice_no')</td>
                                <td class="value-table">{{$transaction->invoice_no}}</td>
                                <td class="title-table">@lang('lang.final_total')</td>
                                <td class="value-table">{{$transaction->final_total}}</td>
                            </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.service_title')</td>
                                <td class="value-table">{{$OrderService->category?->title}}</td>
                                <td class="title-table">@lang('lang.payment_method')</td>
                                <td class="value-table">{{__('lang.'.$OrderService->payment_method)}}</td>
                            </tr>
                            <tr>
                                <td class="title-table">@lang('lang.client_name')</td>
                                <td class="value-table">{{$user->name}}</td>
                                <td class="title-table">@lang('lang.status')</td>
                                <td class="value-table">
                                    <span class="span-status {{$class}}"> {{__('lang.'.$class)}}</span>
                                    </td>
                            </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.client_phone')</td>
                                <td class="value-table">{{$user->phone}}</td>
                                <td class="title-table">@lang('lang.address')</td>
                                <td class="value-table">{{$OrderService->address}}</td>
                            </tr>
                            <tr>
                                <td class="title-table">@lang('lang.provider_name')</td>
                                <td class="value-table">{{$provider?->name}}</td>
                                <td class="title-table">{{\App\CPU\translate('images')}}</td>
                                <td class="value-table">

  
                                    
                                    <div class="images">
                                        @foreach ($OrderService->getMedia('images') as $k=> $image)
                                        <a href="{{ $image->getUrl()}}" class="image-popup">
                                          <img src="{{ $image->getUrl()}}" alt="Image" height="50px" width="50px">
                                        </a>
                                            
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.provider_phone')</td>
                                <td class="value-table">{{$provider?->phone}}</td>
                                <td class="title-table">@lang('lang.cancel_reason')</td>
                                <td class="value-table">{{$OrderService->cancel_reason? $OrderService->cancel_reason?->title:''}}</td>
                            </tr>

                            <tr>
                                <td class="title-table">@lang('lang.suggested_price')</td>
                                <td class="value-table">{{$transaction->suggested_price}}</td>
                                <td class="title-table">{{\App\CPU\translate('canceled_by')}}</td>
                                <td class="value-table">
                                    @if (in_array($OrderService->status,['declined','canceled']))
                                        {{__('lang.'.$OrderService->canceled_type).' => '.$OrderService->canceledby?->name}}
                                    @endif
                                </td>
                            </tr>


                            <tr class="tr-sp">
                                <td class="title-table">@lang('lang.grand_total')</td>
                                <td class="value-table">{{$transaction->grand_total}}</td>
                                <td class="title-table">@lang('lang.updated_at')</td>
                                <td class="value-table">
                                    {{$OrderService->updated_at->format('Y-m-d h:i A')}}
                                </td>
                            </tr>

                            <tr>
                                <td class="title-table">@lang('lang.discount_amount')</td>
                                <td class="value-table">{{$transaction->discount_amount}}</td>
                                <td class="title-table">@lang('lang.created_at')</td>
                                <td class="value-table">
                                    {{$OrderService->created_at->format('Y-m-d h:i A')}}
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                @if( in_array($class,['approved','completed']))
                    <div id="map">
    
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    $(document).ready(function() {
          $('.image-popup').magnificPopup({
            type: 'image'
          });
        });
</script>
@if( in_array($class,['approved','completed']))
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>
    <script>
        
        let map, activeInfoWindow, markers ,info_window_data= [];
        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 24.774265,
                    lng: 46.738586,
                },
                zoom: 8
            });

            map.addListener("click", function(event) {
                mapClicked(event);
            });

            initMarkers();
        }

        /* --------------------------- Initialize Markers --------------------------- */
        function initMarkers() {
            markers=[];
            const initialMarkers = <?php echo $back_end_markers ?>;
            initialMarkers.forEach(async (markerData,index) => {
                const position =   { lat: parseFloat(markerData.lat), lng: parseFloat(markerData.long)};
                const marker = new google.maps.Marker({
                    position:position,
                    icon: {
                        url: markerData.img,
                        scaledSize: new google.maps.Size(35, 35),
                    },
                    title:  markerData.name ,
                    draggable: true,
                    map
                });

                markers.push({
                    key: markerData.id,
                    marker: marker
                });



                marker.addListener("dragend", (event) => {
                    markerDragEnd(event, index);
                });
            });

        }
        
       

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&callback=initMap&language={{app()->getLocale()}}" async></script>
@endif
@endsection
