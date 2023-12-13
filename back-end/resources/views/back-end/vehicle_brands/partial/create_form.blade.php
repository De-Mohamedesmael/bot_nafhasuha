<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('title', __('lang.title') . ':*') !!}
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
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('vehicle_type_id', __('lang.vehicle_type') . ':*') !!}
            {!! Form::select('vehicle_type_id', $vehicle_types, false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>


</div>

