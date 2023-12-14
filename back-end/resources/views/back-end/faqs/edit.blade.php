@extends('back-end.layouts.app')
@section('title', __('lang.edit_faq'))
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_faq')</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.faqs.update', $faq->id), 'id' =>
                        'faq-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('category_faq_id', __('lang.category_faq') . ':*') !!}
                                    {!! Form::select('category_faq_id', $category_faqs, $faq->category_faq_id, [
                            'class' => 'selectpicker
                                    form-control',
                            'data-live-search' => 'true',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('title', __('lang.title') . ':*') !!}
                                    <div class="input-group my-group">
                                        {!! Form::text('title', $faq->title , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                                        <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="notifications"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
                                    </div>
                                </div>
                                @include('back-end.layouts.partials.translation_inputs', [
                                    'attribute' => 'title',
                                    'translations' => $faq->getTranslationsArray('title'),
                                    'type' => 'notifications',
                                ])
                            </div>

                            <div  class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="input-label"
                                           for="ar_description">{{\App\CPU\translate('description')}}
                                        (ar)</label>
                                    <textarea name="translations[ar][description]" class="editor textarea" cols="30"
                                              rows="10" >{!!old('description',isset($faq->getTranslationsArray('description')['ar'])?$faq->getTranslationsArray('description')['ar']['description']:null) !!}</textarea>
                                </div>
                            </div>
                            <div  class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="input-label"
                                           for="ar_description">{{\App\CPU\translate('description')}}
                                        (en)</label>
                                    <textarea name="translations[en][description]" class="editor textarea" cols="30"
                                              rows="10" >{!!old('description',isset($faq->getTranslationsArray('description')['en'])?$faq->getTranslationsArray('description')['en']['description']:null) !!}</textarea>
                                </div>
                            </div>




                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" value="{{trans('lang.save')}}" id="submit-btn"
                                        class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>


</section>

@endsection

@section('javascript')
    <script>
        tinymce.init({
            selector: ".editor",
            height: 250,
            plugins: [
                "advlist autolink lists link charmap print preview anchor textcolor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime table contextmenu paste code wordcount",
            ],
            toolbar:
                "insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat",
            branding: false,
        });
        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            setTimeout(() => {
                if ($("#faq-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#faq-form").attr("action"),
                        data: $("#faq-form").serialize(),
                        success: function (response) {
                            if (response.code == 200) {
                                swal("Success", response.msg, "success");
                                setTimeout(() => {
                                    window.close();
                                }, 1000);
                            }else if(response.code == 405){
                                swal("Error", response.error, "error");

                            } else {
                                swal("Error", response.msg, "error");
                            }
                        },
                        error: function (response) {
                            if (!response.success) {
                                swal("Error", response.msg, "error");
                            }
                        },
                    });
                }
            });
        });

    </script>


@endsection
