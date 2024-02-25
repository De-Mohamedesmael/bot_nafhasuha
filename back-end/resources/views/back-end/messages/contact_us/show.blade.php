<div>

    <div>

    </div>
</div>
<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.contact_us.send-message'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}

        <div class="modal-header">

            <h4 class="modal-title">{{ $ContactUs->title}}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <h4 class="modal-title">@lang('lang.phone') : {{ $ContactUs->phone}}</h4>
            <div class="note-c">
                {!! $ContactUs->note !!}
            </div>
            {!! Form::hidden('phone', $ContactUs->phone ) !!}

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::textarea('replay', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('lang.replay-message')]) !!}
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.send' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
