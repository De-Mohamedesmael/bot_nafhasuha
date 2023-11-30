<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.order.send-offer', $order->id), 'method' => 'post', 'id' =>
        'send_offer_price_form', 'enctype' => 'multipart/form-data' ])
        !!}

        <div class="modal-header">

            <h4 class="modal-title"@if($order->isOfferPrice()) >@lang( 'lang.send_offer' ) @else @lang( 'lang.accept')@endif</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="well">
                      <strong>@lang('lang.customer_name'): </strong>{{ $order->user?->name }}<br>
                      <strong>@lang('lang.mobile'): </strong>{{ $order->user?->phone }}<br><br>
                    </div>
                  </div>
                <div class="col-md-6">
                    <div class="well">
                        <strong>@lang('lang.invoice_no'): </strong>{{ $order->transaction?->invoice_no }}<br>
                        @if($order->isOfferPrice())
                            <strong>@lang('lang.suggested_price'): </strong><span class=""
                                data-currency_symbol="true">{{ @num_format($order->transaction?->suggested_price) }}</span><br>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <input type="hidden" name="order_id" value="{{$order->id}}">
                @if($order->isOfferPrice())
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('amount', __('lang.amount'). ':*', []) !!} <br>
                            {!! Form::text('amount', @num_format(1), ['class' => 'form-control', 'placeholder'
                            => __('lang.amount')]) !!}
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('provider_id', __('lang.provider') . ':', []) !!}
                        {!! Form::select('provider_id', $providers, request()->provider_id, [
'class' => 'form-control
                    filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="send_offer()">@lang( 'lang.save' )</button>
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

</script>
