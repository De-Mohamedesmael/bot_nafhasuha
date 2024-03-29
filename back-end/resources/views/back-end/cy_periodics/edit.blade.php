@extends('back-end.layouts.app')
@section('title', __('lang.edit_cy_periodic'))
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
    <span class="parent"> <  <a href="{{route("admin.cy_periodics.index")}}"> {{__('lang.cy_periodics')}} </a> / </span>  @lang('lang.edit_cy_periodic')
@endsection
@section('content')
    <section class="forms p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="print-title">@lang('lang.edit_cy_periodic')</div>

                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open(['url' => route('admin.cy_periodics.update', $cy_periodic->id), 'id' =>
                            'cy_periodic-form',
                            'method' =>
                            'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('title', __('lang.title') . ':*',[ 'class'=>"sp-label new-des"]) !!}
                                        <div class="input-group my-group">
                                            {!! Form::text('title', $cy_periodic->title , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                                            <span class="input-group-btn">
                                    <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                            data-type="notifications"><i
                                            class="dripicons-web text-primary fa-lg"></i></button>
                                </span>
                                        </div>
                                    </div>
                                    @include('back-end.layouts.partials.translation_inputs', [
                                        'attribute' => 'title',
                                        'translations' => $cy_periodic->getTranslationsArray('title'),
                                        'type' => 'notifications',
                                    ])
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('price', __('lang.price') . ':',[ 'class'=>"sp-label new-des"]) !!}
                                        {!! Form::number('price', $cy_periodic->price, ['class' => 'form-control','required', 'placeholder' => __('lang.price')]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('sort', __('lang.sort') . ':',[ 'class'=>"sp-label new-des"]) !!}
                                        {!! Form::number('sort', $cy_periodic->sort, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        {!! Form::label('city_id', __('lang.city') . ':',[ 'class'=>"sp-label new-des"]) !!}
                                        {!! Form::select('city_id', $cities, $cy_periodic->city_id, ['class' => 'form-control', 'data-live-search' => 'true','style' => 'width: 100%', 'placeholder' => __('lang.please_select')]) !!}
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('providers', __('lang.provider') . ':*',[ 'class'=>"sp-label new-des"]) !!}
                                        {!! Form::select('providers[]', $providers, $array_providers, [
                                            'class' => 'selectpicker
                                                    form-control ',
                                            'id'=>'providers',
                                            'data-actions-box'=>'true',
                                            'data-live-search' => 'true','multiple',
                                        ]) !!}
                                    </div>
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
                if ($("#cy_periodic-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#cy_periodic-form").attr("action"),
                        data: $("#cy_periodic-form").serialize(),
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
