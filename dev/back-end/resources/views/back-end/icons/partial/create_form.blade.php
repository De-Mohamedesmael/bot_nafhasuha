<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('title', __('lang.title') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::text('title', false , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('link', __('lang.link') . ':*', [ 'class'=>"sp-label new-des"]) !!}
            <div class="input-group my-group">
                {!! Form::text('link', false, ['class' => 'form-control', 'placeholder' => __('lang.link'), 'required']) !!}

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

