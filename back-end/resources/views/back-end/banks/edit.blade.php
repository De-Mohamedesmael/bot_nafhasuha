@extends('back-end.layouts.app')
@section('title', __('lang.bank'))
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_bank')</h4>
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
                                        {!! Form::label('title', __('lang.title') . ':*') !!}
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
