@extends('back-end.layouts.app')
@section('title', __('lang.edit_category_faq'))
@section('styles')
    <style>
        div#content {
            padding-top: 0;
        }
        .sp-label.new-des {
            top: -1px !important;
        }
        .sp-label.new-des.back-e9 {
            background: linear-gradient( to top, #e9ecef 0%, #e9ecef 50%, #ffffff00 50%, #ffffff00 100% ) !important;
        }

    </style>
@endsection
@section('sli_li')
    <span class="parent"> <  <a href="{{route("admin.category_faqs.index")}}"> {{__('lang.category_faqs')}} </a> / </span>  @lang('lang.edit_category_faq')
@endsection
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="print-title">@lang('lang.edit_category_faq')</div>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.category_faqs.update', $category_faq->id), 'id' =>
                        'category_faq-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('title', __('lang.title') . ':*',[ 'class'=>"sp-label new-des"]) !!}
                                    <div class="input-group my-group">
                                        {!! Form::text('title', $category_faq->title , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                                        <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="notifications"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
                                    </div>
                                </div>
                                @include('back-end.layouts.partials.translation_inputs', [
                                    'attribute' => 'title',
                                    'translations' => $category_faq->getTranslationsArray('title'),
                                    'type' => 'notifications',
                                ])
                            </div>




                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('sort', __('lang.sort') . ':*',[ 'class'=>"sp-label new-des"]) !!}
                                    <div class="input-group my-group">
                                        {!! Form::number('sort', $category_faq->sort, ['class' => 'form-control', 'placeholder' => __('lang.sort'), 'required']) !!}

                                    </div>
                                </div>
                            </div>


                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group  justify-cont">
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

        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            setTimeout(() => {
                if ($("#category_faq-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#category_faq-form").attr("action"),
                        data: $("#category_faq-form").serialize(),
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