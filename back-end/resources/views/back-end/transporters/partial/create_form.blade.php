<div class="row">


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('title', __('lang.title') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::text('title', false , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="transporter"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
            </div>
        </div>
        @include('back-end.layouts.partials.translation_inputs', [
            'attribute' => 'title',
            'translations' => [],
            'type' => 'transporter',
        ])
    </div>


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('price', __('lang.minimum_price') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::number('price', null, ['class' => 'form-control','required', 'placeholder' => __('lang.minimum_price')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('price_for_minute', __('lang.price_for_minute') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::number('price_for_minute', null, ['class' => 'form-control','required', 'placeholder' => __('lang.price_for_minute')]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('price_for_kilo', __('lang.price_for_kilo') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::number('price_for_kilo', null, ['class' => 'form-control','required', 'placeholder' => __('lang.price_for_kilo')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('sort', __('lang.sort') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::number('sort', null, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
        </div>
    </div>
    <div class="col-md-4 " style="margin-top: 10px;">
        <div class="container mt-3">
            <div class="red sp-label new-des" style="top: 5px !important;">(125px×55px)</div>
            <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                <div class="col-12">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-10 offset-1">
                                <div class="variants">
                                    <div class='file file--upload w-100'>
                                        <label for='file-input' class="w-100">
                                            <i class="fas fa-cloud-upload-alt"></i>Upload
                                        </label>
                                        <!-- <input  id="file-input" multiple type='file' /> -->
                                        <input type="file" id="file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-10 offset-1">
                    <div class="preview-container"></div>
                </div>
            </div>
        </div>
    </div>


</div>

