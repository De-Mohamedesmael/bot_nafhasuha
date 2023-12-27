@extends('back-end.layouts.app')
@section('title', __('lang.yearly_report'))
@section('styles')
    <style>
        input.daterangepicker-field.form-control {
            direction: ltr !important;
        }
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
            <h4>@lang('lang.yearly_report')</h4>
        </div>
        <form action="">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('year', __('lang.year'), []) !!}
                            {!! Form::selectRange('year', 2020, date('Y'), request()->year??date('Y'), ['class' => 'form-control']) !!}

                        </div>
                    </div>
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
                    </div>

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
                        <a href="{{route('admin.reports.getYearlyReport')}}"
                            class="btn btn-danger mt-2 ml-2">@lang('lang.clear_filter')</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2" style="font-size: 1.2 rem; color: #7c5cc4;">
                                        <h3>@lang('lang.orders')</h3>
                                    </th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>@lang("lang.count_all_order")</td>
                                    <td style="text-align: right">{{$data['count_all_order']}}</td>
                                </tr>
                                <tr>
                                    <td>@lang("lang.count_order_completed")</td>
                                    <td style="text-align: right">{{$data['count_order_completed']}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #7c5cc4;">
                                    <h3>@lang('lang.total_orders')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.sum_deducted_total")</td>
                                <td style="text-align: right">{{@num_format($data['sum_deducted_total'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.sum_provider_total")</td>
                                <td style="text-align: right">{{@num_format($data['sum_provider_total'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.total_discount")</td>
                                <td style="color: red;text-align: right">{{@num_format($data['total_discount'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
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
                                <th colspan="2" style="font-size: 1.2 rem; color: #7c5cc4;">
                                    <h3>@lang('lang.wallet_customer')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.sum_final_total_TopUpCredit")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_TopUpCredit'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.sum_final_total_Withdrawal")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_Withdrawal'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.sum_final_total_InvitationBonus")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_InvitationBonus'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
                                <td>@lang("lang.sum_final_total_JoiningBonus")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_JoiningBonus'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <table class="table">
                            <thead>
                            <tr>
                                <th colspan="2" style="font-size: 1.2 rem; color: #7c5cc4;">
                                    <h3>@lang('lang.payment_types_report')</h3>
                                </th>

                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>@lang("lang.Online")</td>
                                <td style="text-align: right">{{@num_format($data['sum_final_total_Online'])}} {{\App\CPU\translate('SAR')}}</td>
                            </tr>
                            <tr>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
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
</script>

@endsection
