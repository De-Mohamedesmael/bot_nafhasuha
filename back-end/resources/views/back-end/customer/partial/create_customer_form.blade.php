<div class="row">


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name', __('lang.name') . ':') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('lang.name')]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('phone', __('lang.mobile_number') . ':*') !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => __('lang.mobile_number'), 'required']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('email', __('lang.email') . ':') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('lang.email')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('city_id', __('lang.city') . ':*') !!}
            {!! Form::select('city_id', $cities, false, [
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
            {!! Form::label('area_id', __('lang.area') . ':*') !!}
            {!! Form::select('area_id', [], false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'required',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>
    <div class="row mt-4">

        <div class="col-sm-6">
            <label for="password">@lang('lang.password'):*</label>
            <input type="password" class="form-control" name="password" id="password"
                   required placeholder="Create New Password">
        </div>
        <div class="col-sm-6">
            <label for="pass">@lang('lang.confirm_password'):*</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required placeholder="Conform Password">
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('address', __('lang.address') . ':') !!}
            {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('lang.address')]) !!}
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
<input type="hidden" name="quick_add" value="{{ $quick_add }}">

