<div class="row">


    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name', __('lang.name') . ':*') !!}
            <div class="input-group my-group">
                {!! Form::text('name', false , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="splash_screen"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
            </div>
        </div>
        @include('back-end.layouts.partials.translation_inputs', [
            'attribute' => 'title',
            'translations' => [],
            'type' => 'splash_screen',
        ])
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('description', __('lang.description') . ':*') !!}
            <div class="input-group my-group">
                {!! Form::text('description', false, ['class' => 'form-control', 'placeholder' => __('lang.description'), 'required']) !!}
                <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="splash_screen_description"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
            </div>
        </div>
        @include('back-end.layouts.partials.translation_inputs', [
            'attribute' => 'description',
            'translations' => [],
            'type' => 'splash_screen_description',
        ])
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('sort', __('lang.sort') . ':') !!}
            {!! Form::number('sort', null, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
        </div>
    </div>




    <div class="col-md-4 " style="margin-top: 10px;">
        <div class="container mt-3">
            <div class="red">(200px×200px)</div>
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

