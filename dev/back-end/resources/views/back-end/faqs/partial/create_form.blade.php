<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('category_faq_id', __('lang.category_faq') . ':*',[ 'class'=>"sp-label new-des"]) !!}
            {!! Form::select('category_faq_id', $category_faqs,false, [
    'class' => 'selectpicker
            form-control',
    'data-live-search' => 'true',
    'placeholder' => __('lang.please_select'),
]) !!}
        </div>
    </div>
    <div class="col-md-6">
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

    <div  class="col-md-6">
        <div class="form-group pt-2">
            <label  class="input-label sp-label new-des" style="top: 4px !important;"
                   for="ar_description">{{\App\CPU\translate('description')}}
                (ar)</label>
            <textarea name="translations[ar][description]" class="editor textarea" cols="30"
                      rows="10" >{!!old('description') !!}</textarea>
        </div>
    </div>
    <div  class="col-md-6">
        <div class="form-group pt-2">
            <label  class="input-label sp-label new-des" style="top: 4px !important;"
                   for="ar_description">{{\App\CPU\translate('description')}}
                (en)</label>
            <textarea name="translations[en][description]" class="editor textarea" cols="30"
                      rows="10" >{!!old('description') !!}</textarea>
        </div>
    </div>


</div>

