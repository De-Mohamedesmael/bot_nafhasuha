@extends('back-end.layouts.app')
@section('title', __('lang.yearly_report'))
@section('styles')
    <style>
        input.daterangepicker-field.form-control {
            direction: ltr !important;
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
            font-size: 16px;
            font-weight: 500;
            color: #144d79;
            padding: 15px 0;
        }
        td.title-table {
            font-size: 18px;
            font-weight: 700;
            color: #25364F;
            padding: 15px 0;
        }
        tr.tr-sp {
            background-color: #f6f5fd;
        }
        a#printButton {
            background-color: #013e6bd4;
            color: #fff;
        }
        span.span-report {
            width: 100%;
            margin-bottom: 5px;

        }
        span.span-report span {
            float: right;
        }
        .strong-date-number {
            border: 1px solid #013e6b;
            padding: 5px 8px;
            border-radius: 10px;
            color: #013e6b;
        }



       .data-yearly th {
            text-align: center;
        }
        .data-yearly td {
            padding: 10px 15px !important;
        }


        .data-yearly .table {
            border-radius: 19px;
            box-shadow: 1px 2px 6px 6px #013e6b17;
        }

        #chart {
            width: 80%;
            height: 250px !important;
            min-height: 250px !important;

        }
        #chart2 {
            width: 80%;
            height: 250px !important;
            min-height: 250px !important;

        }
        #chart3 {
            width: 75%;
            height: 250px !important;
            min-height: 250px !important;
        }
        span.apexcharts-legend-text {
            margin: 0 5px;
            font-size: 13px !important;
            font-weight: 500 !important;
        }
    </style>
    @if(app()->getLocale() =="ar")

        <style>
            span.span-report span {
                float: left;
            }
        </style>
    @endif
@endsection
@section('sli_li')
    <span class="parent"> < {{__('lang.reports')}} / </span>  @lang('lang.yearly_report')
@endsection
@section('content')
<div class="col-md-12  no-print">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="print-title">@lang('lang.yearly_report')</div>
        </div>
        <form action="">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('year', __('lang.year'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::selectRange('year', 2020, date('Y'), request()->year??date('Y'), ['class' => 'form-control']) !!}

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_date', __('lang.start_date'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::text('start_date', request()->start_date, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_time', __('lang.start_time'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::text('start_time', request()->start_time, [
'class' => 'form-control
                        time_picker sale_filter',
]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_date', __('lang.end_date'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::text('end_date', request()->end_date, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_time', __('lang.end_time'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::text('end_time', request()->end_time, [
                                'class' => 'form-control time_picker
                                                        sale_filter',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('payment_type', __('lang.payment_type'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::select('payment_type', $payment_types, request()->payment_type,
                            ['class' =>
                            'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('category_id',  __('lang.category') ,  ['class'=>'sp-label new-des']) !!}
                            {!! Form::select('category_id', $categories, request()->category_id, [
    'class' => 'form-control filter_product
                        selectpicker',
    'data-live-search' => 'true',
    'placeholder' => __('lang.all'),
    ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('city_id', __('lang.city'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::select('city_id', $cities, request()->city_id, ['class' =>
                            'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('area_id', __('lang.area'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::select('area_id', [], false, ['class' =>
                            'form-control selectpicker', 'id' =>
                            'area_id', 'data-live-search' => 'true', 'placeholder' =>
                            __('lang.all')]) !!}
                        </div>
                    </div>


                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success mt-2">@lang('lang.filter')</button>
                        <a href="{{route('admin.reports.getYearlyReport')}}"
                            class="btn btn-danger mt-2 ml-2">@lang('lang.clear_filter')</a>
                        <a id="printButton" class="btn  mt-2 ml-2"><i
                                class="dripicons-print"></i>
                            @lang('lang.print')
                        </a>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <div class="row" >
                <div class="col-md-4">
                    <h3>@lang('lang.orders')</h3>
                    <div id="chart"></div>
                </div>
                <div class="col-md-4">
                    <h3>@lang('lang.total_orders')</h3>
                    <div id="chart2"></div>
                </div>
                <div class="col-md-4">
                    <h3>@lang('lang.payment_types_report')</h3>
                    <div id="chart3"></div>
                </div>

            </div>
            <div class="col-md-12" id="bodyPrint">
                <div class="row data-yearly" >
                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2" style="font-size: 1.2 rem; color: #013e6b;">
                                        <h3>@lang('lang.orders')</h3>
                                    </th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>@lang("lang.count_all_order")</td>
                                    <td style="text-align: right">{{$data['count_all_order']}}</td>
                                </tr>
                                <tr  class="tr-sp">
                                    <td>@lang("lang.count_order_completed")</td>
                                    <td style="text-align: right">{{$data['count_order_completed']}} </td>
                                </tr>
                                <tr  >
                                    <td>@lang("lang.count_order_canceled")</td>
                                    <td style="text-align: right;color: red">{{$data['count_order_canceled']}} </td>
                                </tr>
                                @if(request()->category_id)
                                    <tr  >
                                        <td>@lang("lang.count_order_category") ({{$categories[request()->category_id]}}) </td>
                                        <td style="text-align: right;">{{$data['count_order_category']}}% : {{\App\CPU\translate('from')}} {{$count_all}} {{\App\CPU\translate('orders')}} </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #013e6b;">
                                    <h3>@lang('lang.total_orders')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.sum_deducted_total")</td>
                                <td style="text-align: right">{{@num_format($data['sum_deducted_total'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr  class="tr-sp">
                                <td>@lang("lang.sum_provider_total")</td>
                                <td style="text-align: right">{{@num_format($data['sum_provider_total'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.total_discount")</td>
                                <td style="color: red;text-align: right">{{@num_format($data['total_discount'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr  class="tr-sp">
                                <td>@lang("lang.sum_final_total")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #013e6b;">
                                    <h3>@lang('lang.wallet_customer')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.sum_final_total_TopUpCredit")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_TopUpCredit'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr  class="tr-sp">
                                <td>@lang("lang.sum_final_total_Withdrawal")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_Withdrawal'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.sum_final_total_InvitationBonus")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_InvitationBonus'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr  class="tr-sp">
                                <td>@lang("lang.sum_final_total_JoiningBonus")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_JoiningBonus'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4  mt-2">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #013e6b;">
                                    <h3>@lang('lang.payment_types_report')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>@lang("lang.Online")</td>
                                    <td style="text-align: right">{{@num_format($data['sum_final_total_Online'])}} {{\App\CPU\translate('SAR')}}</td>
                                </tr>
                                <tr  class="tr-sp">
                                    <td>@lang("lang.Wallet")</td>
                                    <td style="text-align: right">{{@num_format($data['sum_final_total_Wallet'])}} {{\App\CPU\translate('SAR')}}</td>
                                </tr>
                                <tr>
                                    <td>@lang("lang.Cash")</td>
                                    <td style="text-align: right">{{@num_format($data['sum_final_total_Cash'])}} {{\App\CPU\translate('SAR')}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4 mt-2">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #013e6b;">
                                    <h3>@lang('lang.Join')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.Join_user")</td>
                                <td style="text-align: right">{{$data['Join_user']}}</td>
                            </tr>
                            <tr  class="tr-sp">
                                <td>@lang("lang.Join_provider")</td>
                                <td style="text-align: right">{{$data['Join_provider']}}</td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(".daterangepicker-field").daterangepicker({
	  callback: function(startDate, endDate, period){
	    var start_date = startDate.format('YYYY-MM-DD');
	    var end_date = endDate.format('YYYY-MM-DD');
	    var title = start_date + ' To ' + end_date;
	    $(this).val(title);
	    $('input[name="start_date"]').val(start_date);
	    $('input[name="end_date"]').val(end_date);
	  }
	});
        $('#city_id').on('change', function(){
        var city_id =$(this).val();

        $.get( "{{url('/admin/city/areas')}}", { city_id: city_id })
        .done(function( data ) {
        if(data.code == 200){
        var e=data.data;
        $('#area_id')
        .find('option')
        .remove();
        $("#area_id").append('<option value="" selected>{{__("lang.please_select")}}</option>');

        $.each(e, function (key, val) {
        $("#area_id").append('<option value="'+val.id+'" >'+val.title+'</option>');
    });
        $("#area_id").selectpicker("refresh");
    }

    });

    });


    document.getElementById("printButton").addEventListener("click", function() {
        var content_html = document.getElementById("bodyPrint").innerHTML;

        $.ajax({
            url: "{{route('admin.print')}}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                content_html:content_html,
            },
            success: function (response) {

                if (response.status) {

                    var printWindow = window.open("", "_blank");
                    printWindow.document.write( response.data.html_c );
                    printWindow.print();
                    printWindow.close();
                } else {
                    swal("Error", response.msg, "error");
                }
            },
            error: function (response) {
                console.log(response);
            },
        });

    });


@php

        $count_all_r= $count_all != 0 ? $count_all :$data['count_all_order'];
        $count_order_completed_r= $count_all_r == 0? 0: ($data['count_order_completed']/$count_all_r)* 100 ;
        $count_order_canceled_r= $count_all_r == 0? 0: ($data['count_order_canceled']/$count_all_r)* 100 ;
        $count_order_category=$data['count_order_category'];



        $sum_final_total_r=$data['sum_final_total'];
        $total_discount_r=$data['sum_final_total'] != 0? ($data['total_discount']/$sum_final_total_r)* 100: $data['sum_final_total'];
        $sum_provider_total_r=$data['sum_final_total'] != 0? ($data['sum_provider_total']/$sum_final_total_r)* 100: $data['sum_final_total'];
        $sum_deducted_total_r=$data['sum_final_total'] != 0? ($data['sum_deducted_total']/$sum_final_total_r)* 100: $data['sum_final_total'];


        $sum_pay_r=$data['sum_final_total_Cash']+$data['sum_final_total_Wallet']+$data['sum_final_total_Online'];
        $sum_final_total_Cash_r=$sum_pay_r != 0? ($data['sum_final_total_Cash']/$sum_pay_r)* 100: 0;
        $sum_final_total_Wallet_r=$sum_pay_r != 0? ($data['sum_final_total_Wallet']/$sum_pay_r)* 100: 0;
        $sum_final_total_Online_r=$sum_pay_r != 0? ($data['sum_final_total_Online']/$sum_pay_r)* 100: 0;
@endphp
// chart orders
    @if($count_all != 0 )
         series_=[{{$count_order_category}}, {{$count_order_completed_r}}, {{$count_order_canceled_r}}];
        labels_= [ '{{__("lang.count_order_category")}}', '{{__("lang.count_order_completed")}}', '{{__("lang.count_order_canceled")}}'];
        colors_ =['rgba(14,81,131,0.57)','rgba(0,176,81,0.56)','rgba(255,40,92,0.53)'];

    @else
        series_=[{{$count_order_completed_r}}, {{$count_order_canceled_r}}];
        labels_= [  '{{__("lang.count_order_completed")}}', '{{__("lang.count_order_canceled")}}'],
        colors_ =['rgba(0,176,81,0.54)','rgba(255,40,92,0.68)'];
    @endif
    const chartData = {
        series: series_,
        labels: labels_,
    };
    const chartOptions = {
        chart: {
            type: 'pie'
        },
        labels: chartData.labels,
        series: chartData.series,
        colors:['rgba(14,81,131,0.57)','rgba(0,176,81,0.56)','rgba(255,40,92,0.53)'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 90
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    const chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
    chart.render();



    //chart total orders
    const chartOptions2 = {
        chart: {
            type: 'pie'
        },
        labels: [ '{{__("lang.sum_deducted_total")}}', '{{__("lang.sum_provider_total")}}', '{{__("lang.total_discount")}}'],
        series: [{{$sum_deducted_total_r}}, {{$sum_provider_total_r}}, {{$total_discount_r}}],
        colors:['rgba(14,81,131,0.57)','rgba(0,176,81,0.56)','rgba(255,40,92,0.53)'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 90
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    const chart2 = new ApexCharts(document.querySelector("#chart2"), chartOptions2);
    chart2.render();




//chart payment types

    const chartOptions3 = {
        chart: {
            type: 'pie'
        },
        labels: [ '{{__("lang.Cash")}}', '{{__("lang.Wallet")}}', '{{__("lang.Online")}}'],
        series: [{{$sum_final_total_Cash_r}}, {{$sum_final_total_Wallet_r}}, {{$sum_final_total_Online_r}}],
        colors:['rgba(100,74,71,0.83)','rgb(180,132,143)','rgba(0,180,238,0.95)'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 90
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Initialize the pie chart
    const chart3 = new ApexCharts(document.querySelector("#chart3"), chartOptions3);
    chart3.render();
</script>

@endsection
