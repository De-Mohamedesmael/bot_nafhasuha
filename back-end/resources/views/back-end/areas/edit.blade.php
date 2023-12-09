@extends('back-end.layouts.app')
@section('title', __('lang.edit_area'))
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_area')</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.areas.update', $area->id), 'id' =>
                        'area-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('title', __('lang.title') . ':*') !!}
                                    <div class="input-group my-group">
                                        {!! Form::text('title', $area->general_title , ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                                        <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="notifications"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
                                    </div>
                                </div>
                                @include('back-end.layouts.partials.translation_inputs', [
                                    'attribute' => 'title',
                                    'translations' => $area->getTranslationsArray('title'),
                                    'type' => 'notifications',
                                ])
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('sort', __('lang.sort') . ':') !!}
                                    {!! Form::number('sort', $area->sort, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    {!! Form::label('city_id', __('lang.city') . ':') !!}
                                    {!! Form::select('city_id', $cities, $area->city_id, ['class' => 'form-control', 'data-live-search' => 'true','style' => 'width: 100%', 'placeholder' => __('lang.please_select')]) !!}
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

        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            setTimeout(() => {
                if ($("#area-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#area-form").attr("action"),
                        data: $("#area-form").serialize(),
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
