<!doctype html>
<html lang="en">
@php
    $logo = \Settings::get('logo');

    $createdAt = \Carbon\Carbon::parse($order->created_at);
    $transaction=$order->transaction;
    $provider=$order->provider_with_rate;
    $tax_number=\Settings::get('tax_number','5677894225677');
    $value_tax=\Settings::get('value_tax',15);
    $amount_tax = (int)($transaction->final_total *$value_tax /100);
   $provider_img= $provider->getFirstMedia('images') != null ? $provider->getFirstMedia('images')->getUrl() : asset('assets/images/settings/'.$logo);
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{\App\CPU\translate('app_name')}}</title>
    <link href="{{asset('assets/front-end/FontAwesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front-end/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front-end/bootstrap/css/bootstrap-grid.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front-end/js/bootstrap.min.css')}}" rel="stylesheet"/>
    <link as="style"
          href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,600,700,800,900&display=swap"
          onload="this.onload=null;this.rel='stylesheet'"
          rel="preload">
    <noscript>
        <link href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,600,700,800,900&display=swap"
              rel="stylesheet">
    </noscript>

    <link as="style"
          href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,600,700,800,900&display=swap"
          onload="this.onload=null;this.rel='stylesheet'"
          rel="preload">
    <noscript>
        <link href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,600,700,800,900&display=swap"
              rel="stylesheet">
    </noscript>
    <style>
        body {
            background-color: #DFDFDF;
            direction: rtl;
            font-size: 18px;
            font-weight: 700;
        }
        .w-50 {
            width: 50% !important;
        }
        .top-line-red {
            height: 35px;
            background-color: #8C0001;
            width: 50%;
            border-radius: 20px 20px 0px 0px;
            margin: 15px 25% 0;
        }
        .container-invoice {
            background-color: #fff;
            width: 100%;

            border-radius: 20px 20px 0px 0px;
        }
        .logo-div {
            justify-content: center;
            width: 100%;
            display: inline-flex;
            margin: 20px 0;
        }


        .invoice-header-div , .invoice-body-data {
            padding: 0 8% 25px;
        }
        .invoice-header-data {
            width: 100% !important;
            display: inline-flex;
            padding: 0 25px;
        }
        .invoice-data {
            text-align: start;
        }
        .invoice-date {
            text-align: end;
        }
        .invoice-adders {
            padding: 20px;
        }
        .invoice-header {
            border-radius: 20px 20px 0px 0px;
            background-color: #F3F4F8;
        }
        .adders-img {
            width: 35px;
        }
        .invoice-date {
            direction: ltr;
            text-align: left;
        }

        .invoice-body-data {
            padding-top: 20px;
            background-color: #fff;
        }
        .service-data {
            display: flex;
        }
        .service-image {
            margin: 20px;
            border-radius: 50%;
            background-color: #f3f4f8;
        }
        img.service-img {
            width: 50px;
        }
        .service-des {
            margin: 20px 0;
        }
        .service-title {
            font-size: 20px;
        }
        .service-code {
            font-size: 13px;
            color: #6f767e;
        }
        .service-sub-title {
            font-size: 15px;
            color: #255aa3c7;
        }
        .line-bil {
            width: 100%;
            display: flex;
            margin-top: 10px;
        }
        .tax-data {
            margin: 15px;
            border-top: 1px dashed #D3CFCF;

        }
        .title-line {
            width: 50%;
        }
        .data-line {
            width: 50%;
            text-align: left;
        }
        .small-size {
            font-size: 15px;
            color: #374151;
        }
        .top-border-line {
            margin-top: 10px;
            padding: 10px;
            border-top: 1px solid #BEC5D1;
        }
        .data-group {
            margin-top: 15px;
            border-radius: 15px;
            border: 1px solid #bec5d1;
            padding: 20px;
        }
        .provider-group {
            display: flex;
            padding: 15px;
            border: 1px solid #b8b8b870;
            border-radius: 5px;
            margin-top: 15px;
        }
        .provider-data  ,.provider-data .service-title ,.provider-data .service-code,.provider-rate {

            padding: 5px;
        }
        i.star-icon{
            color: #FFC700;
        }
        .w-30 {
            width: 30%;
            margin-top: 20px;
        }
        .qr-img {
            width: 150px;
        }
        .print-img ,  .share-img{
            padding: 10px;
        }
        .print-image {
            margin: 15px 30px;
            border: 1px solid #e0e0e0cf;
            padding: 0 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .share-image {
            margin: 15px 30px;
            border: 1px solid #e0e0e0cf;
            padding: 0 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .qr-image {
            margin: 15px 30px;
            padding: 0 20px;
            cursor: pointer;
        }
        img.provider-img {
            max-width: 100px;
            border-radius: 50%;
        }
        @media only screen and (max-width: 600px) {
            body{
                font-size: 15px;
                font-weight: 700;
            }
            .top-line-red {
                height: 12px;
                background-color: #8C0001;
                width: 50%;
                border-radius: 20px 20px 0px 0px;
                margin: 6px 25% 0;
            }
            .logo-div {
                margin: 10px 0;
            }
            .logo-img {
                width: 100px;
            }
            .invoice-adders {
                padding: 10px;
                font-size: 14px;
            }
            .adders-img {
                width: 25px;
            }
            .invoice-header-div, .invoice-body-data {
                padding: 0 5% 5px;
            }
            h2.title {
                font-size: 20px;
                padding-top: 10px;
                margin-top: 0 !important;
            }
            .data-group {
                margin-top: 10px;
                padding: 10px;
            }
            .small-size {
                font-size: 12px;
            }
            .provider-img {
                width: 80px;
            }
            .provider-data, .provider-data .service-title, .provider-data .service-code, .provider-rate {
                padding: 2px;
            }
            .service-title {
                font-size: 15px;
            }
            .print-img, .share-img {
                padding: 10px;
                width: 60px;
            }
            h3 {
                font-size: 15px;
            }
            .qr-img {
                width: 70px;
            }
            .print-image {
                margin: 10px;
                padding: 0 10px;
            }
            .qr-image {
                margin: 20px 10px;
                padding: 0 10px;
            }
            .share-image {
                margin: 10px;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
<section class="container">
    <div class="row" id="all-data">
        <div class="top-line-red"></div>
        <div class="container-invoice ">
            <div class="invoice-header ">
                <div class="logo-div row">
                    <img class="logo-img" src="{{asset('assets/images/settings/'.$logo)}}" >
                </div>
                <div class="invoice-data">
                    <div class="invoice-header invoice-header-div">
                        <div class="invoice-header-data">
                            <div class="invoice-number  w-50" >
                                الفاتورة رقم :  {{$order->id}}
                            </div>

                            <div class="invoice-date  w-50" >
                                {{$createdAt->format('j F Y')}}
                            </div>
                        </div>
                        <div class="invoice-adders w-100">

                            <img class="adders-img" src="{{asset('assets/images/map-icon.png')}}" >

                            {{$order->address}}
                        </div>
                    </div>
                    <div class="invoice-body-data">
                        <h2 class="title">
                            بيانات الطلب
                        </h2>

                        <div class="data-group">
                            <div class="service-data">
                                <div class="service-image">
                                    @php
                                        if($order->type =="Maintenance"){
                                            $img =$order->service?->image;
                                        }else{
                                           $img = $order->category?->image;
                                        }
                                    @endphp
                                    <img class="service-img" src="{{asset('assets/images/'.$img)}}" >
                                </div>
                                <div class="service-des">
                                    <div class="service-title">
                                        خدمة
                                        @if($order->type =="Maintenance")
                                            {{$order->service?->title }}
                                        @else
                                            {{$order->category?->title }}
                                        @endif
                                    </div>
                                    <div class="service-code">
                                        كود : <span> #{{$transaction->invoice_no}}</span>
                                    </div>
                                    <div class="service-sub-title">

                                        @if($order->type =="ChangeBattery")
                                            {{$transaction->type_battery?->title }}
                                        @elseif($order->type =="Petrol")
                                            {{$transaction->type_gasoline?->title }}
                                        @elseif($order->type =="Tire")
                                            {{$transaction->tire?->title}}
                                        @endif
                                        تغير البطارية الخاصة بالسيارة
                                    </div>
                                </div>
                            </div>
                            <div class="price-data">
                                <div class="tax-data">
                                    <div class="line-bil">
                                        <div class="title-line">الرقم الضريبي </div>
                                        <div class="data-line">{{$tax_number}}</div>
                                    </div>
                                    <div class="line-bil small-size">
                                        <div class="title-line">القيمة الاساسية</div>
                                        <div class="data-line">{{(int)($transaction->final_total-$amount_tax)}}  ريال </div>
                                    </div>
                                </div>
                                <div class="second-data">
                                    <div class="line-bil small-size top-border-line">
                                        <div class="title-line">المجموع الجزئي</div>
                                        <div class="data-line">{{(int)($transaction->final_total-$amount_tax)}}  ريال </div>
                                    </div>
                                    <div class="line-bil small-size top-border-line">
                                        <div class="title-line">ضريبة القيمة المضافة ({{$value_tax}}%)</div>
                                        <div class="data-line">{{(int)$amount_tax}}  ريال </div>
                                    </div>
                                    <div class="line-bil small-size top-border-line">
                                        <div class="title-line">اجمالي السعر المدفوع</div>
                                        <div class="data-line final_total">{{(int)($transaction->final_total)}}  ريال </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <h2 class="title mt-4">
                            بيانات مقدم الخدمة
                        </h2>
                        <div class="provider-group">
                            <div class="provider-image">
                                <img class="provider-img" src="{{$provider_img}}">
                            </div>
                            <div class="provider-data">
                                <div class="service-title">
                                    {{$provider->name}}
                                </div>
                                <div class="service-code">
                                    مزود الخدمة
                                </div>
                                <div class="provider-rate">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="star-icon fa fa-star{{$provider->totalRate < $i && $provider->totalRate > $i-1 ?"-half-o":($provider->totalRate < $i ? "-o":null)}}" aria-hidden="true"></i>


                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="row" style="    justify-content: center;    text-align: center;">
                            <a class="print-image "onclick="window.print();">
                                <img class="print-img" src="{{asset('assets/images/print-icon.png')}}" >
                                <h3>
                                    طباعة
                                </h3>
                            </a>
                            <div class="qr-image" id="bn-qr" >

                                <img class="qr-img" src="{{generate_qr_code($transaction->invoice_no)}}" >

                            </div>
                            <a class="share-image" id="bn-share" >
                                <img class="share-img" src="{{asset('assets/images/share-icon.png')}}" >
                                <h3>
                                    مشاركة
                                </h3>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<script>



    let shareData = {
        url: window.location.href,
    }

    document.querySelector('#bn-share').addEventListener('click', () => {
        navigator.share(shareData);
    });
</script>
</body>
</html>
