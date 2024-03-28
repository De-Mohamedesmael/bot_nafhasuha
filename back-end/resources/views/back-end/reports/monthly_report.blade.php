@extends('back-end.layouts.app')
@section('title', __('lang.monthly_report'))
@section('styles')
    <style>
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
        .strong-date-number.this-day {
            border: 1px solid #bf0000;
            padding: 5px 8px;
            border-radius: 10px;
            color: #bf0000;
        }
        td {
            padding: 0;
        }
        td.td-title {
            width: 200px;
            padding: 0;
        }
        .table-data-calender{
            width: 100%;
        }


        tr.tr-data{
            color: #013e6b;
            font-size: 15px;
            font-weight: 500;
        }
        tr.tr-data-s {
            background-color: #f6f5fd;

        }
        td.td-data {
            text-align: center;
            padding: 7px;

        }
        #chart {
            width: 100%;
            max-height: 500px !important;
        }
        #chart2 {
            width: 100%;
            max-height: 500px !important;
        }
        .th-next {
            cursor: pointer;
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
    <span class="parent"> < {{__('lang.reports')}} / </span>  @lang('lang.monthly_report')@endsection
@section('content')
<div class="col-md-12  no-print">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="print-title">@lang('lang.monthly_report')</div>
        </div>

        <form action="" id="Form_fillter">
            <div class="col-md-12">
                <input type="hidden" name="year"  id="year" value="{{request()->year}}">

                <div class="row">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('area_id', __('lang.area'), [ 'class'=>"sp-label new-des"]) !!}
                            {!! Form::select('area_id', [], false, ['class' =>
                            'form-control selectpicker', 'id' =>
                            'area_id', 'data-live-search' => 'true', 'placeholder' =>
                            __('lang.all')]) !!}
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


                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success mt-2">@lang('lang.filter')</button>
                        <a href="{{route('admin.reports.getMonthlyReport')}}"
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
                <div class="col-md-6">
                    <canvas id="chart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="chart2"></canvas>
                </div>
            </div>
            <div class="col-md-12" id="bodyPrint">
                <table class="table table-bordered"
                    style="border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                    <thead>

                        <tr>
                            <th>#</th>
                            <th><a class="th-next"
                                   id="th_prev" ><i
                                        class="fa fa-arrow-{{app()->getLocale() == 'en' ?"left":"right"}}"></i> {{trans('lang.previous')}}</a></th>
                            <th colspan="10" class="text-center">{{$year}}</th>
                            <th><a class="th-next"
                                   id="th_next" >{{trans('lang.next')}}
                                    <i class="fa fa-arrow-{{app()->getLocale() == 'en' ?"right":"left"}}"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td><strong> &nbsp; &nbsp; @lang('lang.January') &nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.February')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.March')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.April')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.May')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.June')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.July')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.August')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.September')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.October')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.November')&nbsp; &nbsp;</strong><br></td>
                            <td><strong>&nbsp; &nbsp;@lang('lang.December')&nbsp; &nbsp;</strong><br></td>
                        </tr>
                        <tr>
                            @for($i=0 ; $i<=12 ; $i++)
                                @if($i == 0)
                                    <td class="td-title">
                                        <table class="table-data-calender tm-3" >
                                            <tr class="tr-data tr-data-s">
                                                <td class="td-data">@lang("lang.count_all_order")</td>
                                            </tr>
                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.count_order_completed")</td>
                                            </tr>
                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.count_order_canceled")</td>
                                            </tr>
                                            <tr class="tr-data tr-data-s">
                                                <td class="td-data">@lang("lang.total_discount")</td>
                                            </tr>

                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.sum_deducted_total")</td>
                                            </tr>
                                            <tr class="tr-data tr-data-s">
                                                <td class="td-data">@lang("lang.sum_provider_total")</td>
                                            </tr>
                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.sum_final_total")</td>
                                            </tr>
                                            <tr class="tr-data tr-data-s">
                                                <td class="td-data">@lang("lang.sum_final_total_TopUpCredit")</td>
                                            </tr>
                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.sum_final_total_Withdrawal")</td>
                                            </tr>
                                            <tr class="tr-data tr-data-s">
                                                <td class="td-data">@lang("lang.sum_final_total_InvitationBonus")</td>
                                            </tr>
                                            <tr class="tr-data ">
                                                <td class="td-data">@lang("lang.sum_final_total_JoiningBonus")</td>
                                            </tr>

                                        </table>

                                    </td>
                                @else
                                    <td style="padding: 0 !important;">
                                            <table class="table-data-calender">
                                                <tr class="tr-data tr-data-s">
                                                    <td class="td-data"><span>@if(!empty($data['count_all_order'][$i])) {{$data['count_all_order'][$i]}} @else 0 @endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['count_order_completed'][$i])) {{$data['count_order_completed'][$i]}} @else 0 @endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['count_order_canceled'][$i])) {{$data['count_order_canceled'][$i]}} @else 0 @endif</span></td>
                                                </tr>
                                                <tr class="tr-data tr-data-s">
                                                    <td class="td-data"><span>@if(!empty($data['total_discount'][$i])) {{@num_format($data['total_discount'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['sum_deducted_total'][$i])) {{@num_format($data['sum_deducted_total'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data tr-data-s">
                                                    <td class="td-data"><span>@if(!empty($data['sum_provider_total'][$i])) {{@num_format($data['sum_provider_total'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['sum_final_total'][$i])) {{@num_format($data['sum_final_total'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data tr-data-s">
                                                    <td class="td-data"><span>@if(!empty($data['sum_final_total_TopUpCredit'][$i])) {{@num_format($data['sum_final_total_TopUpCredit'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['sum_final_total_Withdrawal'][$i])) {{@num_format($data['sum_final_total_Withdrawal'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data tr-data-s">
                                                    <td class="td-data"><span>@if(!empty($data['sum_final_total_InvitationBonus'][$i])) {{@num_format($data['sum_final_total_InvitationBonus'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>
                                                <tr class="tr-data ">
                                                    <td class="td-data"><span>@if(!empty($data['sum_final_total_JoiningBonus'][$i])) {{@num_format($data['sum_final_total_JoiningBonus'][$i])}} {{\App\CPU\translate('SAR')}} @else {{@num_format(0)}} {{\App\CPU\translate('SAR')}}@endif</span></td>
                                                </tr>

                                            </table>

                                    </td>
                                @endif
                            @endfor
                        </tr>
                    </tbody>
                </table>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @php
        $count_all_order = json_encode(array_values($data['count_all_order']));
        $count_order_completed = json_encode(array_values($data['count_order_completed']));
        $count_order_canceled = json_encode(array_values($data['count_order_canceled']));



        $total_discount = json_encode(array_values($data['total_discount']));
        $sum_deducted_total = json_encode(array_values($data['sum_deducted_total']));
        $sum_provider_total = json_encode(array_values($data['sum_provider_total']));
        $sum_final_total = json_encode(array_values($data['sum_final_total']));
        $sum_final_total_TopUpCredit = json_encode(array_values($data['sum_final_total_TopUpCredit']));
        $sum_final_total_Withdrawal = json_encode(array_values($data['sum_final_total_Withdrawal']));
        $sum_final_total_InvitationBonus = json_encode(array_values($data['sum_final_total_InvitationBonus']));
        $sum_final_total_JoiningBonus = json_encode(array_values($data['sum_final_total_JoiningBonus']));


    @endphp
    <script>
        var months=["{{__('site.months.1')}}","{{__('site.months.2')}}","{{__('site.months.3')}}","{{__('site.months.4')}}"
            ,"{{__('site.months.5')}}","{{__('site.months.6')}}","{{__('site.months.7')}}","{{__('site.months.8')}}"
            ,"{{__('site.months.9')}}","{{__('site.months.10')}}","{{__('site.months.11')}}","{{__('site.months.12')}}"];
        var ctx = document.getElementById('chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: "{{__('lang.count_all_order')}}",
                        data: {!! $count_all_order !!},
                        backgroundColor: 'rgba(14,81,131,0)',
                        borderColor: '#0E5183',
                        borderWidth: 3
                    },{
                        label: "{{__('lang.count_order_completed')}}",
                        data: {!! $count_order_completed !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#00b051',
                        borderWidth: 3
                    },{
                        label: "{{__('lang.count_order_canceled')}}",
                        data: {!! $count_order_canceled !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#FF285C',
                        borderWidth: 3
                    }
                ]
            },
            options: {

                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    }
                }
            }
        });






        var ctx2 = document.getElementById('chart2').getContext('2d');
        var myChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: "{{__('lang.sum_final_total')}}",
                        data: {!! $sum_final_total !!},
                        backgroundColor: 'rgba(14,81,131,0)',
                        borderColor: '#0E5183',
                        borderWidth: 3
                    },{
                        label: "{{__('lang.sum_deducted_total')}}",
                        data: {!! $sum_deducted_total !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#00b051',
                        borderWidth: 3
                    },{
                        label: "{{__('lang.sum_provider_total')}}",
                        data: {!! $sum_provider_total !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#09b5ce',
                        borderWidth: 3
                    },{
                        label: "{{__('lang.total_discount')}}",
                        data: {!! $total_discount !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#FF285C',
                        borderWidth: 3
                    }

                    ,{
                        label: "{{__('lang.sum_final_total_TopUpCredit')}}",
                        data: {!! $sum_final_total_TopUpCredit !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#b79600',
                        borderWidth: 3
                    }
                    ,{
                        label: "{{__('lang.sum_final_total_Withdrawal')}}",
                        data: {!! $sum_final_total_Withdrawal !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#06233f',
                        borderWidth: 3
                    }
                    ,{
                        label: "{{__('lang.sum_final_total_InvitationBonus')}}",
                        data: {!! $sum_final_total_InvitationBonus !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: '#b67482',
                        borderWidth: 3
                    }
                    ,{
                        label: "{{__('lang.sum_final_total_JoiningBonus')}}",
                        data: {!! $sum_final_total_JoiningBonus !!} ,
                        backgroundColor: 'rgba(255,40,92,0)',
                        borderColor: 'rgba(0,0,204,0)',
                        borderWidth: 3
                    }
                ]
            },
            options: {

                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    }
                }
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
        $('#th_prev').on('click', function(e){
            $('#year').val("{{$year - 1}}");
            $('#Form_fillter').submit();
        });

        $('#th_next').on('click', function(e){
            $('#year').val("{{$year + 1}}");
            $('#Form_fillter').submit();
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
    </script>
@endsection
