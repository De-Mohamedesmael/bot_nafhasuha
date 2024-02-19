<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('title', __('lang.title') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::text('title', false , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="notifications"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
            </div>
        </div>
        @include('back-end.layouts.partials.translation_inputs', [
            'attribute' => 'title',
            'translations' => [],
            'type' => 'notifications',
        ])
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('sort', __('lang.sort') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::number('sort', false, ['class' => 'form-control', 'placeholder' => __('lang.sort'), 'required']) !!}

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group ">
            {!! Form::label('country_id', __('lang.country') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('country_id', $countries, false, ['class' => 'form-control', 'data-live-search' => 'true','style' => 'width: 100%', 'placeholder' => __('lang.please_select')]) !!}
        </div>
    </div>


</div>

