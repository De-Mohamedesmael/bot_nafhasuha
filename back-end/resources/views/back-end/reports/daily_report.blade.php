@extends('back-end.layouts.app')
@section('title', __('lang.daily_report'))
@section('styles')
    <style>
        span.span-report {
            width: 100%;
            margin-bottom: 5px;

        }
        span.span-report span {
            float: right;
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
@section('content')
<div class="col-md-12  no-print">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4>@lang('lang.daily_report')</h4>
        </div>

        <form action="">
            <div class="col-md-12">
                <div class="row">

                    {{--
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_date', __('lang.start_date'), []) !!}
                            {!! Form::text('start_date', request()->start_date, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('start_time', __('lang.start_time'), []) !!}
                            {!! Form::text('start_time', request()->start_time, [
'class' => 'form-control
                        time_picker sale_filter',
]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_date', __('lang.end_date'), []) !!}
                            {!! Form::text('end_date', request()->end_date, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('end_time', __('lang.end_time'), []) !!}
                            {!! Form::text('end_time', request()->end_time, [
                                'class' => 'form-control time_picker
                                                        sale_filter',
                            ]) !!}
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('city_id', __('lang.city'), []) !!}
                            {!! Form::select('city_id', $cities, request()->city_id, ['class' =>
                            'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('area_id', __('lang.area'), []) !!}
                            {!! Form::select('area_id', [], false, ['class' =>
                            'form-control selectpicker', 'id' =>
                            'area_id', 'data-live-search' => 'true', 'placeholder' =>
                            __('lang.all')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('payment_type', __('lang.payment_type'), []) !!}
                            {!! Form::select('payment_type', $payment_types, request()->payment_type,
                            ['class' =>
                            'form-control', 'placeholder' => __('lang.all'),'data-live-search'=>"true"]) !!}
                        </div>
                    </div>



                    <div class="col-md-3">
                        <br>
                        <button type="submit" class="btn btn-success mt-2">
                            @lang('lang.filter')
                        </button>
                        <a href="{{route('admin.reports.getDailyReport')}}"
                            class="btn btn-danger mt-2 ml-2">@lang('lang.clear_filter')</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="col-md-12">
                <table class="table table-bordered"
                    style="border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                    <thead>
                        <tr>
                            <th><a
                                    href="{{url('report/get-daily-sale-report?year='.$prev_year.'&month='.$prev_month)}}"><i
                                        class="fa fa-arrow-left"></i> {{trans('lang.previous')}}</a></th>
                            <th colspan="5" class="text-center">
                                {{date("F", strtotime($year.'-'.$month.'-01')).' ' .$year}}</th>
                            <th><a
                                    href="{{url('report/get-daily-sale-report?year='.$next_year.'&month='.$next_month)}}">{{trans('lang.next')}}
                                    <i class="fa fa-arrow-right"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@lang('lang.sunday')</strong></td>
                            <td><strong>@lang('lang.monday')</strong></td>
                            <td><strong>@lang('lang.tuesday')</strong></td>
                            <td><strong>@lang('lang.wednesday')</strong></td>
                            <td><strong>@lang('lang.thursday')</strong></td>
                            <td><strong>@lang('lang.friday')</strong></td>
                            <td><strong>@lang('lang.saturday')</strong></td>
                        </tr>
                        @php
                        $i = 1;
                        $flag = 0;
                        @endphp
                        @while ($i <= $number_of_day) <tr>
                            @for($j=1 ; $j<=7 ; $j++) @if($i> $number_of_day)
                                @php
                                break;
                                @endphp
                                @endif
                                @if($flag)
                                @if($year.'-'.$month.'-'.$i == date('Y').'-'.date('m').'-'.(int)date('d'))
                                <td>
                                    <p style="color:red"><strong>{{$i}}</strong></p>
                                    @else
                                <td>
{{--                                    "count_all_order"--}}
{{--                                    "count_order_completed"--}}
{{--                                    "sum_deducted_total"--}}
{{--                                    "sum_final_total"--}}
{{--                                    "sum_final_total_canceled"--}}
{{--                                    "sum_final_total_TopUpCredit"--}}
{{--                                    "sum_final_total_Withdrawal"--}}
{{--                                    "sum_final_total_InvitationBonus"--}}
{{--                                    "sum_final_total_JoiningBonus"--}}
{{--                                    "total_discount"--}}
                                    <p><strong>{{$i}}</strong></p>
                                    @endif
                                        <span  class="span-report" >
                                            @if(!empty($data['count_all_order'][$i]))
                                                <strong>@lang("lang.count_all_order")</strong>
                                                <span>{{$data['count_all_order'][$i]}}</span>
                                            @endif
                                        </span>
                                        <span class="span-report">
                                            @if(!empty($data['count_order_completed'][$i]))
                                                <strong>@lang("lang.count_order_completed")</strong><span>{{$data['count_order_completed'][$i]}}</span>
                                            @endif
                                         </span>
                                        <span class="span-report">
                                            @if(!empty($data['total_discount'][$i]))
                                                <strong>@lang("lang.total_discount")</strong>

                                                <span>{{@num_format($data['total_discount'][$i])}} {{\App\CPU\translate('SAR')}}</span>

                                            @endif
                                        </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_deducted_total'][$i]))
                                                <strong>@lang("lang.sum_deducted_total")</strong>

                                                <span>{{@num_format($data['sum_deducted_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                         </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_provider_total'][$i]))
                                                <strong>@lang("lang.sum_provider_total")</strong><span>{{@num_format($data['sum_provider_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @elseif(!empty($data['sum_deducted_total'][$i]) )
                                                <strong>@lang("lang.sum_provider_total")</strong><span>{{@num_format(0)}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                        </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_final_total'][$i]))
                                                <strong>@lang("lang.sum_final_total")</strong><span>{{@num_format($data['sum_final_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                         </span>

                                        <span class="span-report">
                                            @if(!empty($data['sum_final_total_TopUpCredit'][$i]))
                                                <strong>@lang("lang.sum_final_total_TopUpCredit")</strong><span>{{@num_format($data['sum_final_total_TopUpCredit'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                         </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_final_total_Withdrawal'][$i]))
                                                <strong>@lang("lang.sum_final_total_Withdrawal")</strong><span>{{@num_format($data['sum_final_total_Withdrawal'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                        </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_final_total_InvitationBonus'][$i]))
                                                <strong>@lang("lang.sum_final_total_InvitationBonus")</strong>
                                                <span>{{@num_format($data['sum_final_total_InvitationBonus'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                         </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_final_total_JoiningBonus'][$i]))
                                                <strong>@lang("lang.sum_final_total_JoiningBonus")</strong><span>{{@num_format($data['sum_final_total_JoiningBonus'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                        </span>

                                </td>
                                @php
                                $i++;
                                @endphp
                                @elseif($j == $start_day)
                                @if($year.'-'.$month.'-'.$i == date('Y').'-'.date('m').'-'.(int)date('d'))
                                <td>
                                    <p style="color:red"><strong>{{$i}}</strong></p>
                                    @else
                                <td>
                                    <p><strong>{{$i}}</strong></p>
                                    @endif

                                    <span  class="span-report" >
                                            @if(!empty($data['count_all_order'][$i]))
                                            <strong>@lang("lang.count_all_order")</strong>
                                            <span>{{$data['count_all_order'][$i]}}</span>
                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['count_order_completed'][$i]))
                                            <strong>@lang("lang.count_order_completed")</strong><span>{{$data['count_order_completed'][$i]}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['total_discount'][$i]))
                                            <strong>@lang("lang.total_discount")</strong>

                                            <span>{{@num_format($data['total_discount'][$i])}} {{\App\CPU\translate('SAR')}}</span>

                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_deducted_total'][$i]))
                                            <strong>@lang("lang.sum_deducted_total")</strong>

                                            <span>{{@num_format($data['sum_deducted_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                        <span class="span-report">
                                            @if(!empty($data['sum_provider_total'][$i]))
                                                <strong>@lang("lang.sum_provider_total")</strong><span>{{@num_format($data['sum_provider_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                            @elseif(!empty($data['sum_deducted_total'][$i]) )
                                                <strong>@lang("lang.sum_provider_total")</strong><span>{{@num_format(0)}} {{\App\CPU\translate('SAR')}}</span>
                                            @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total'][$i]))
                                            <strong>@lang("lang.sum_final_total")</strong><span>{{@num_format($data['sum_final_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>

                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_TopUpCredit'][$i]))
                                            <strong>@lang("lang.sum_final_total_TopUpCredit")</strong><span>{{@num_format($data['sum_final_total_TopUpCredit'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_Withdrawal'][$i]))
                                            <strong>@lang("lang.sum_final_total_Withdrawal")</strong><span>{{@num_format($data['sum_final_total_Withdrawal'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_InvitationBonus'][$i]))
                                            <strong>@lang("lang.sum_final_total_InvitationBonus")</strong>
                                            <span>{{@num_format($data['sum_final_total_InvitationBonus'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_JoiningBonus'][$i]))
                                            <strong>@lang("lang.sum_final_total_JoiningBonus")</strong><span>{{@num_format($data['sum_final_total_JoiningBonus'][$i])}} {{\App\CPU\translate('SAR')}} </span>
                                        @endif
                                        </span>

                                </td>
                                @php
                                $flag = 1;
                                $i++;
                                continue;
                                @endphp

                                @else
                                <td></td>
                                @endif

                                @endfor

                                </tr>
                                @endwhile

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
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
</script>
@endsection
