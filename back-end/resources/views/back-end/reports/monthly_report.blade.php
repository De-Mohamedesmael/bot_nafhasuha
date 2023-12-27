@extends('back-end.layouts.app')
@section('title', __('lang.monthly_report'))
@section('styles')
    <style>

    </style>
    @if(app()->getLocale() =="ar")

        <style>

        </style>
    @endif
@endsection
@section('content')
<div class="col-md-12  no-print">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4>@lang('lang.monthly_report')</h4>
        </div>

        <form action="">
            <div class="col-md-12">
                <div class="row">

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
                        <button type="submit" class="btn btn-success mt-2">@lang('lang.filter')</button>
                        <a href="{{route('admin.reports.getMonthlyReport')}}"
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
                        @php
                        $next_year = $year + 1;
                        $pre_year = $year - 1;
                        @endphp
                        <tr>
                            <th><a href="{{route('admin.reports.getMonthlyReport').'year='.$pre_year}}"><i
                                        class="fa fa-arrow-left"></i> {{trans('lang.previous')}}</a></th>
                            <th colspan="10" class="text-center">{{$year}}</th>
                            <th><a href="{{route('admin.reports.getMonthlyReport').'?year='.$next_year}}">{{trans('lang.next')}}
                                    <i class="fa fa-arrow-right"></i></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@lang('lang.January')</strong><br></td>
                            <td><strong>@lang('lang.February')</strong><br></td>
                            <td><strong>@lang('lang.March')</strong><br></td>
                            <td><strong>@lang('lang.April')</strong><br></td>
                            <td><strong>@lang('lang.May')</strong><br></td>
                            <td><strong>@lang('lang.June')</strong><br></td>
                            <td><strong>@lang('lang.July')</strong><br></td>
                            <td><strong>@lang('lang.August')</strong><br></td>
                            <td><strong>@lang('lang.September')</strong><br></td>
                            <td><strong>@lang('lang.October')</strong><br></td>
                            <td><strong>@lang('lang.November')</strong><br></td>
                            <td><strong>@lang('lang.December')</strong><br></td>
                        </tr>
                        <tr>
                            @for($i=1 ; $i<=12 ; $i++)
                                <td>

                                    <span  class="span-report" >
                                        @if(!empty($data['count_all_order'][$i]))
                                            <strong>@lang("lang.count_all_order")</strong><br>
                                            <span>{{$data['count_all_order'][$i]}}</span>
                                        @endif
                                    </span>
                                    <span class="span-report">
                                            @if(!empty($data['count_order_completed'][$i]))
                                            <strong>@lang("lang.count_order_completed")</strong><br><span>{{$data['count_order_completed'][$i]}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['total_discount'][$i]))
                                            <strong>@lang("lang.total_discount")</strong><br>

                                            <span>{{@num_format($data['total_discount'][$i])}} {{\App\CPU\translate('SAR')}}</span>

                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_deducted_total'][$i]))
                                            <strong>@lang("lang.sum_deducted_total")</strong><br>

                                            <span>{{@num_format($data['sum_deducted_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_provider_total'][$i]))
                                            <strong>@lang("lang.sum_provider_total")</strong><br><span>{{@num_format($data['sum_provider_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @elseif(!empty($data['sum_deducted_total'][$i]) )
                                            <strong>@lang("lang.sum_provider_total")</strong><br><span>{{@num_format(0)}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total'][$i]))
                                            <strong>@lang("lang.sum_final_total")</strong><br><span>{{@num_format($data['sum_final_total'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>

                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_TopUpCredit'][$i]))
                                            <strong>@lang("lang.sum_final_total_TopUpCredit")</strong><br><span>{{@num_format($data['sum_final_total_TopUpCredit'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_Withdrawal'][$i]))
                                            <strong>@lang("lang.sum_final_total_Withdrawal")</strong><br><span>{{@num_format($data['sum_final_total_Withdrawal'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                        </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_InvitationBonus'][$i]))
                                            <strong>@lang("lang.sum_final_total_InvitationBonus")</strong><br>
                                            <span>{{@num_format($data['sum_final_total_InvitationBonus'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                         </span>
                                    <span class="span-report">
                                            @if(!empty($data['sum_final_total_JoiningBonus'][$i]))
                                            <strong>@lang("lang.sum_final_total_JoiningBonus")</strong><br><span>{{@num_format($data['sum_final_total_JoiningBonus'][$i])}} {{\App\CPU\translate('SAR')}}</span>
                                        @endif
                                        </span>



                                </td>
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
