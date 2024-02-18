<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('provider_type', __('lang.provider_type') . ':*',[ 'class'=>"sp-label new-des" ,'style'=>"right: 30px !important;font-size: 12px;"]) !!}
            {!! Form::select('provider_type', ['ProviderCenter'=>__('lang.ProviderCenter'),'Provider'=>__('lang.provider')], false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'required',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('services_from_home', __('lang.services_from_home') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('services_from_home', [0=>__('lang.no'),1=>__('lang.yes')], false, [
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
            {!! Form::label('name', __('lang.name') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('lang.name')]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('phone', __('lang.mobile_number') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => __('lang.mobile_number'), 'required']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('email', __('lang.email') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('lang.email')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('city_id', __('lang.city') . ':*',[ 'class'=>"sp-label new-des"]) !!}
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
            {!! Form::label('area_id', __('lang.area') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('area_id', [], false, [
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
            {!! Form::label('categories[]', __('lang.categories') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('categories[]', $categories, false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'id'=>'categories_id',
    'multiple'=>'true',
    'required',
]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="sp-label new-des" for="password">@lang('lang.password'):*</label>
            <input type="password" class="form-control" name="password" id="password"
                   required placeholder="Create New Password">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="sp-label new-des"  for="pass">@lang('lang.confirm_password'):*</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required placeholder="Conform Password">
        </div>
    </div>



    <div class="d-flex col-md-4">

        <div class=" col-6 p-0">
            <div class="form-group">
                <label class="sp-label new-des"  for="lat">@lang('lang.lat'):*</label>
                <input type="text" class="form-control" name="lat" id="lat"
                       required >
            </div>
        </div>
        <div class="col-6 p-0">
            <div class="form-group">
                <label class="sp-label new-des"  for="long">@lang('lang.long'):*</label>
                <input type="text" class="form-control" id="long"
                       name="long"  required >
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('address', __('lang.address') . ':',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('lang.address')]) !!}
        </div>
    </div>
    <div class="col-md-4 " style="margin-top: 10px;">
        <div class="container mt-3">
            <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                <div class="col-12">
                    <div class="mt-3">
                        <div class="">
                            <div class="col-12 offset-1">
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

