<div class="row">


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('type', __('lang.type') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('type', ['OutSite'=>__('lang.OutSite'),'Service'=>__('lang.Service'),'Info'=>__('lang.Info'),], false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'required',
]) !!}
        </div>
    </div>
    <div class="col-md-4 hide div_type_id"id="div_Service">
        <div class="form-group">
            {!! Form::label('service_id', __('lang.service') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('service_id', $categories, false, [
    'class' => 'selectpicker
            form-control input_Service',
    'data-live-search' => 'true',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>
    <div class="col-md-4 hide div_type_id" id="div_Info">
        <div class="form-group">
            {!! Form::label('info_id', __('lang.page') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('info_id', $infos, false, [
    'class' => 'selectpicker
            form-control input_Info',
    'data-live-search' => 'true',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>
    <div class="col-md-4 div_type_id"id="div_OutSite">
        <div class="form-group">
            {!! Form::label('url', __('lang.url') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            {!! Form::url('url', null, ['class' => 'form-control input_OutSite', 'required','placeholder' => __('lang.url')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('sort', __('lang.sort') . ':', [ 'class'=>"sp-label new-des"]) !!}
            {!! Form::number('sort', null, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('start_at', __('lang.start_at'). ':', [ 'class'=>"sp-label new-des"]) !!}
                {!! Form::text('start_at', @format_date(date('Y-m-d')), ['class' => 'form-control datepicker',
                 'required',
                'placeholder' => __('lang.payment_date')]) !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('end_at', __('lang.end_at'). ':', [ 'class'=>"sp-label new-des"]) !!}
                {!! Form::text('end_at', null, ['class' => 'form-control datepicker',

                'placeholder' => __('lang.end_at')]) !!}
            </div>
        </div>


    </div>

    <div class="col-md-4 " style="margin-top: 10px;">
        <div class="container mt-3">
            <div class="red sp-label new-des" style="top: 5px !important;">(655px×320px)</div>
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

