
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('provider_id', __('lang.provider') . ':*' ,['class'=>'sp-label new-des']) !!}
            {!! Form::select('provider_id', $providers, false, [
                'class' => 'selectpicker
                        form-control',
                'data-live-search' => 'true',
                'required',
                'placeholder' => __('lang.please_select'),
            ]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('bank_id', __('lang.bank') . ':*',['class'=>'sp-label new-des']) !!}
            {!! Form::select('bank_id', $banks, false, [
                'class' => 'selectpicker
                        form-control',
                'data-live-search' => 'true',
                'required',
                'placeholder' => __('lang.please_select'),
            ]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('amount', __('lang.amount'). ':*',['class'=>'sp-label new-des']) !!}
            {!! Form::text('amount', @num_format(1), ['class' => 'form-control', 'placeholder'
            => __('lang.amount')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('iban', __('lang.iban'). ':*',['class'=>'sp-label new-des']) !!}
            {!! Form::text('iban', false, ['class' => 'form-control', 'placeholder'
            => __('lang.iban')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('full_name', __('lang.full_name'). ':*',['class'=>'sp-label new-des']) !!}
            {!! Form::text('full_name', false, ['class' => 'form-control', 'placeholder'
            => __('lang.full_name')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('paid_on', __('lang.payment_date'). ':',['class'=>'sp-label new-des back-e9']) !!}
            {!! Form::text('paid_on', @format_date(date('Y-m-d')), ['class' => 'form-control datepicker',
            'readonly', 'required',
            'placeholder' => __('lang.payment_date')]) !!}
        </div>
    </div>


</div>

