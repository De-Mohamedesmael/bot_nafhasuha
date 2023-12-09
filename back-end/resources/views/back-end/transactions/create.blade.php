@extends('back-end.layouts.app')
@section('title', __('lang.create') .' '.__('lang.Withdrawal'))

@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.create') @lang('lang.Withdrawal')</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open(['url' => route('admin.transaction.store'), 'id' => 'transaction-form', 'method' => 'POST', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                            @include('back-end.transactions.partial.create_form')


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn"
                                            class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            <div id="cropped_images"></div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal gift_card_modal no-print" role="dialog" aria-hidden="true"></div>
    </section>
    <!-- Modal -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-modal" style="display:none">
                        <div id="croppie-container"></div>
                        <button data-dismiss="modal" id="croppie-cancel-btn" type="button" class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-submit-btn" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">




        $('.datepicker').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });

        $('#provider_id').on('change', function(){
            var provider_id =$(this).val();

            getWallet(provider_id);

        });
        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            submitForm();
        });
        function getWallet(provider_id){
            $.get( "{{url('/admin/provider/get_wallet')}}", { provider_id: provider_id })
                .done(function( data ) {
                    if(data.success){
                        $('#get_wallet').html(data.wallet);
                    }else {
                        swal("Error", data.msg, "error");
                    }

                });
        }
        function submitForm() {
            if ($("#transaction-form").valid()) {
                tinyMCE.triggerSave();
                document.getElementById("loader").style.display = "block";
                document.getElementById("content").style.display = "none";
                $.ajax({
                    type: "POST",
                    url: $("form#transaction-form").attr("action"),
                    data: $("#transaction-form").serialize(),
                    success: function (response) {
                        myFunction();
                        if (response.code == 200) {
                            swal("Success", response.msg, "success");
                            $("#full_name").val("").change();
                            $("#iban").val("").change();
                            $("#bank_id").selectpicker("refresh");
                            $("#provider_id").selectpicker("refresh");
                            var provider_id =$("#provider_id").val();
                            getWallet(provider_id);
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
