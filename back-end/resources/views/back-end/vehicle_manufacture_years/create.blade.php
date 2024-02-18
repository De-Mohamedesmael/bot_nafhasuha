@extends('back-end.layouts.app')
@section('title', __('lang.add_vehicle_manufacture_year'))
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
    <span class="parent"> <  <a href="{{route("admin.vehicle_manufacture_years.index")}}"> {{__('lang.vehicle_manufacture_years')}} </a> / </span>  @lang('lang.add_vehicle_manufacture_year')
@endsection
@section('content')
<section class="forms p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="print-title">@lang('lang.add_vehicle_manufacture_year')</div>

                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open(['url' => route('admin.vehicle_manufacture_years.store'), 'id' => 'cities-form', 'method' => 'POST', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                            @include('back-end.vehicle_manufacture_years.partial.create_form')


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group justify-cont">
                                        <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn"
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
    <!-- Modal -->

@endsection

@section('javascript')
    <script type="text/javascript">

        $('.datepicker').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });

        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            if ($("#cities-form").valid()) {
                tinyMCE.triggerSave();
                document.getElementById("loader").style.display = "block";
                document.getElementById("content").style.display = "none";
                $.ajax({
                    type: "POST",
                    url: $("form#cities-form").attr("action"),
                    data: $("#cities-form").serialize(),
                    success: function (response) {
                        myFunction();
                        if (response.code == 200) {
                            swal("Success", response.msg, "success");
                            $(".translations").val("").change();
                           $("input:not([type='submit'])").val("").change();
                            const previewContainer = document.querySelector('.preview-container');
                            previewContainer.innerHTML = '';
                        }else if(response.code == 405){
                            swal("Error", response.error, "error");

                        } else {
                            swal("Error", response.msg, "error");
                        }
                    },
                    error: function (response) {
                        myFunction();
                        if (!response.success) {
                            swal("Error", response.msg, "error");
                        }
                    },
                });
            }
        }
    </script>
@endsection
