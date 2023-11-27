<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.customer.postPay', $customer->id), 'method' => 'post', 'id' =>
        'add_payment_form', 'enctype' => 'multipart/form-data' ])
        !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.add_wallet_balance' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="well">
                      <strong>@lang('lang.customer_name'): </strong>{{ $customer->name }}<br>
                      <strong>@lang('lang.mobile'): </strong>{{ $customer->phone }}<br><br>
                    </div>
                  </div>
                <div class="col-md-6">
                    <div class="well">
                        <strong>@lang('lang.wallet_balance'): </strong><span class=""
                            data-currency_symbol="true">{{ @num_format($getWalletBalance) }}</span><br>
                       </div>
                </div>
            </div>
            <br>
            <div class="row">
                <input type="hidden" name="customer_id" value="{{$customer->id}}">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('amount', __('lang.amount'). ':*', []) !!} <br>
                        {!! Form::text('amount', @num_format(1), ['class' => 'form-control', 'placeholder'
                        => __('lang.amount')]) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('paid_on', __('lang.payment_date'). ':', []) !!} <br>
                        {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control datepicker',
                        'readonly', 'required',
                        'placeholder' => __('lang.payment_date')]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $('.selectpicker').selectpicker('refresh');
    $('.datepicker').datepicker({
        language: '{{session('language')}}',
        todayHighlight: true,
    });
    $('#add_payment_form #method').change(function(){
        var method = $(this).val();

        if(method === 'card'){
            $('.card_field').removeClass('hide');
            $('.not_cash_fields').addClass('hide');
            $('.not_cash').attr('required', false);
        }
        else if(method === 'cash'){
            $('.not_cash_fields').addClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', false);
        }else{
            $('.not_cash_fields').removeClass('hide');
            $('.card_field').addClass('hide');
            $('.not_cash').attr('required', true);
        }
    })
</script>
