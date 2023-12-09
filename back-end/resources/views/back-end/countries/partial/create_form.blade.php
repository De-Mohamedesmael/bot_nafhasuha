<div class="row">
    <div class="col-md-4">
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
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('sort', __('lang.sort') . ':*') !!}
            <div class="input-group my-group">
                {!! Form::number('sort', false, ['class' => 'form-control', 'placeholder' => __('lang.sort'), 'required']) !!}

            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('count_number', __('lang.count_number') . ':*') !!}
            <div class="input-group my-group">
                {!! Form::number('count_number', false, ['class' => 'form-control', 'placeholder' => __('lang.count_number'), 'required']) !!}

            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('code_number', __('lang.code_number') . ':*') !!}
            <div class="input-group my-group">
                {!! Form::text('code_number', false, ['class' => 'form-control', 'placeholder' => __('lang.code_number'), 'required']) !!}

            </div>
        </div>
    </div>

    <div class="col-md-4 " style="margin-top: 10px;">
        <div class="container mt-3">
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

