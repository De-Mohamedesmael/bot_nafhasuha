<div class="row">
    <div class="col-md-4">
        <div class="form-group">
        <label class="sp-label new-des" for="type_notify"><b>@lang('lang.type_notify')</b></label>
            <div class="input-group my-group">
        {!! Form::select('type_notify', ['Notify'=>__('lang.Notify'),'Mail'=>__('lang.Mail'),'SMS'=>__('lang.SMS')],  'Notify', ['class' => 'form-control ','data-live-search' => 'true', 'placeholder' => __('lang.please_select'), 'id' => 'type_notify']) !!}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('title', __('lang.title') . ':*' ,['class'=>"sp-label new-des"]) !!}
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
            {!! Form::label('description', __('lang.description') . ':*',['class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::text('body', false, ['class' => 'form-control', 'placeholder' => __('lang.body'), 'required']) !!}
                <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="notifications_description"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
            </div>
        </div>
        @include('back-end.layouts.partials.translation_inputs', [
            'attribute' => 'body',
            'translations' => [],
            'type' => 'notifications_description',
        ])
    </div>

    <div class="col-md-4" id="div_type">
        <div class="form-group">
            {!! Form::label('type', __('lang.type') . ':*',['class'=>"sp-label new-des"]) !!}
            {!! Form::select('type', ['3'=>__('lang.3'),'2'=>__('lang.2')], false, [
                'class' => 'selectpicker
                        form-control',
                'data-live-search' => 'true',
                'required',
            ]) !!}
        </div>
    </div>
    <div class="col-md-4 hide div_type_id"id="div_2">
        <div class="form-group">
            {!! Form::label('type_id', __('lang.coupons') . ':*',['class'=>"sp-label new-des"]) !!}
            {!! Form::select('type_id', $coupons, false, [
                'class' => 'selectpicker
                        form-control input_2',
                'data-live-search' => 'true',
                'placeholder' => __('lang.please_select'),
            ]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('type_model', __('lang.type_model') . ':*',['class'=>"sp-label new-des"]) !!}
            {!! Form::select('type_model', ['User'=>__('lang.User'),'Provider'=>__('lang.Provider')], false, [
                'class' => 'selectpicker
                        form-control',
                'data-live-search' => 'true',
                'required',
            ]) !!}
        </div>
    </div>
    <div class="col-md-4  div_type_model_id"id="div_User">
        <div class="form-group">
            {!! Form::label('user_id', __('lang.user') . ':*',['class'=>"sp-label new-des"]) !!}
            {!! Form::select('user_id[]', $users, false, [
                'class' => 'selectpicker
                        form-control input_User',
                'data-actions-box'=>'true',
                'data-live-search' => 'true','multiple',
                'required',
                 'id'=>'user_id',
            ]) !!}
        </div>
    </div>
    <div class="col-md-4 hide div_type_model_id"id="div_Provider">
        <div class="form-group">
            {!! Form::label('provider_id', __('lang.provider') . ':*',['class'=>"sp-label new-des"]) !!}
            {!! Form::select('provider_id[]', $providers, false, [
                'class' => 'selectpicker
                        form-control  input_Provider',
                'id'=>'provider_id',
                'data-actions-box'=>'true',
                'data-live-search' => 'true','multiple',
            ]) !!}
        </div>
    </div>


    <div class="col-md-4 " style="margin-top: 10px;"  id="div_image">
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

