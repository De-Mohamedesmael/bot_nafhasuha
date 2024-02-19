@extends('back-end.layouts.app')
@section('title', __('lang.bank'))
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
    <span class="parent"> <  <a href="{{route("admin.banks.index")}}"> {{__('lang.banks')}} </a> / </span>  @lang('lang.edit_bank')
@endsection
@section('content')
<section class="forms p-0" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="print-title">@lang('lang.edit_bank')</div>

                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.banks.update', $bank->id), 'id' =>
                        'bank-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('title', __('lang.title') . ':*' , [ 'class'=>"sp-label new-des"]) !!}
                                        <div class="input-group my-group">
                                            {!! Form::text('title', $bank->title , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                                            <span class="input-group-btn">
                                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                                        data-type="notifications"><i
                                                        class="dripicons-web text-primary fa-lg"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    @include('back-end.layouts.partials.translation_inputs', [
                                        'attribute' => 'title',
                                        'translations' => $bank->getTranslationsArray('title'),
                                        'type' => 'notifications',
                                    ])
                                </div>

                            </div>

                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group justify-cont">
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
                if ($("#bank-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#bank-form").attr("action"),
                        data: $("#bank-form").serialize(),
                        success: function (response) {
                            if (response.success) {
                                swal("Success", response.msg, "success");
                                setTimeout(() => {
                                    window.close();
                                }, 1000);
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
