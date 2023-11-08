<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @include('layouts.partials.css')
    @yield('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('style')
    <style>
        .mCSB_draggerRail {
            width: 16px !important;
        }

        .mCSB_dragger_bar {
            width: 10px !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        .preview-category-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-brand-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-class-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-edit-product-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }

        .preview {
            position: relative;
            width: 150px;
            height: 150px;
            padding: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin: 30px 0px;
            border: 1px solid #ddd;
        }

        .preview img {
            width: 100%;
            height: 100%;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            object-fit: cover;

        }

        .delete-btn {
            position: absolute;
            top: 156px;
            right: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: transparent;
            color: rgba(235, 32, 38, 0.97);
        }

        .crop-btn {
            position: absolute;
            top: 156px;
            left: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
            background: transparent;
            color: #007bff;
        }
    </style>
    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants>div {
            margin-right: 5px;
        }

        .variants>div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>input[type='file'] {
            display: none
        }

        .file>label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file>label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file>label>i {
            padding-right: 5px;
        }

        .file--upload>label {
            color: hsl(204, 86%, 53%);
            border-color: hsl(204, 86%, 53%);
        }

        .file--upload>label:hover {
            border-color: hsl(204, 86%, 53%);
            background-color: hsl(204, 86%, 96%);
        }

        .file--upload>label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading>label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        .file--uploading>label>i {
            animation: pulse 5s infinite;
        }

        .file--uploading>label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading>label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success>label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success>label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success>label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger>label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger>label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger>label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled>label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }

        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }
    </style>
</head>

<body onload="myFunction()">
    <div id="loader"></div>
    @if (request()->segment(1) != 'pos')
        @include('layouts.partials.header')
    @endif
    <div class="@if (request()->segment(1) != 'pos') page @else pos-page @endif">
        @include('layouts.partials.sidebar')
        <div style="display:none" id="content" class="animate-bottom">
            @foreach ($errors->all() as $message)
                <div class="alert alert-danger alert-dismissible text-center">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>{{ $message }}
                </div>
            @endforeach
            <input type="hidden" id="__language" value="{{ session('language') }}">
            <input type="hidden" id="__decimal" value=".">
            <input type="hidden" id="__currency_precision" value="2">
            <input type="hidden" id="__currency_symbol" value="$">
            <input type="hidden" id="__currency_thousand_separator" value=",">
            <input type="hidden" id="__currency_symbol_placement" value="before">
            <input type="hidden" id="__precision" value="3">
            <input type="hidden" id="__quantity_precision" value="3">
            <input type="hidden" id="system_mode" value="{{ env('SYSTEM_MODE') }}">
            @yield('content')
        </div>

        @include('layouts.partials.footer')


        <div class="modal view_modal no-print" role="dialog" aria-hidden="true"></div>
        <div class="modal" id="cropper_modal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('lang.crop_image_before_upload')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-8">
                                    <img src="" id="sample_image" />
                                </div>
                                <div class="col-md-4">
                                    <div class="preview_div"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="crop" class="btn btn-primary">@lang('lang.crop')</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $cash_register = App\Models\CashRegister::where('user_id', Auth::user()->id)
                ->where('status', 'open')
                ->first();
        @endphp
        <input type="hidden" name="is_register_close" id="is_register_close"
            value="@if (!empty($cash_register)) {{ 0 }}@else{{ 1 }} @endif">
        <input type="hidden" name="cash_register_id" id="cash_register_id"
            value="@if (!empty($cash_register)) {{ $cash_register->id }} @endif">
        <div id="closing_cash_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal text-left">
        </div>

        <!-- This will be printed -->
        <section class="invoice print_closing_cash print-only" id="print_closing_cash"> </section>
    </div>

    <script type="text/javascript">
        base_path = "{{ url('/') }}";
        current_url = "{{ url()->current() }}";
    </script>

    @include('layouts.partials.currencies_obj')
    @include('layouts.partials.javascript')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = base_path + settings.url;
                }
            },
        });
    </script>
    @yield('javascript')
    @stack('javascripts')

    <script type="text/javascript">
        @if (session('status'))
            swal(
                @if (session('status.success') == '1')
                    "Success"
                @else
                    "Error"
                @endif , "{{ session('status.msg') }}",
                @if (session('status.success') == '1')
                    "success"
                @else
                    "error"
                @endif );
        @endif
        $(document).ready(function() {
            let cash_register_id = $('#cash_register_id').val();

            if (cash_register_id) {
                $('#power_off_btn').removeClass('hide');
            }

            $(document).on('hidden.bs.modal', '#closing_cash_modal', function() {
                $('#print_closing_cash').html('');
            });
            $(document).on('click', '#print-closing-cash-btn', function() {
                let cash_register_id = parseInt($(this).data('cash_register_id'));
                console.log('/cash/print-closing-cash/' + cash_register_id, 'cash_register_id');
                $.ajax({
                    method: 'GET',
                    url: '/cash/print-closing-cash/' + cash_register_id,
                    data: {},
                    success: function(result) {
                        $('#print_closing_cash').html(result);
                        $('#print_closing_cash').printThis({
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "",
                            header: "<h1>@lang('lang.closing_cash')</h1>",
                            footer: "",
                            base: true,
                            pageTitle: "Closing Cash",
                            removeInline: false,
                            printDelay: 333,
                            header: null,
                            formValues: true,
                            canvas: true,
                            base: null,
                            doctypeString: '<!DOCTYPE html>',
                            removeScripts: true,
                            copyTagClasses: true,
                            beforePrintEvent: null,
                            beforePrint: null,
                            afterPrint: null,
                            afterPrintEvent: null,
                            canvas: false,
                            noPrintSelector: ".no-print",
                            iframe: false,
                            append: null,
                            prepend: null,
                            noPrintClass: "no-print",
                            importNode: true,
                            pagebreak: {
                                avoid: "",
                                after: "",
                                before: "",
                                mode: "css",
                                pageBreak: "auto",
                                pageSelector: "",
                                styles: "",
                                selector: "",
                                validSelectors: [],
                                validTags: [],
                                width: "",
                                height: ""
                            },

                        });
                        // __print_receipt("print_closing_cash");
                    },
                });
            })
        })

        jQuery.validator.setDefaults({
            errorPlacement: function(error, element) {
                if (element.parent().parent().hasClass('my-group')) {
                    element.parent().parent().parent().find('.error-msg').html(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });
        $(document).on('click', '.btn-modal', function(e) {
            e.preventDefault();
            var container = $(this).data('container');
            $.ajax({
                url: $(this).data('href'),
                dataType: 'html',
                success: function(result) {
                    $(container).html(result).modal('show');
                },
            });
        });
        @if (request()->segment(1) != 'pos')
            if ($(window).outerWidth() > 1199) {
                $('nav.side-navbar').removeClass('shrink');
            }
        @endif
        function myFunction() {
            setTimeout(showPage, 150);
        }

        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("content").style.display = "block";
        }

        $("div.alert").delay(3000).slideUp(750);

        $(document).on('click', '.delete_item', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "Are you sure You Wanna Delete it?",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    swal({
                        title: 'Please Enter Your Password',
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your password",
                                type: "password",
                                autocomplete: "off",
                                autofocus: true,
                            },
                        },
                        inputAttributes: {
                            autocapitalize: 'off',
                            autoComplete: 'off',
                        },
                        focusConfirm: true
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        swal(
                                            'Success',
                                            'Correct Password!',
                                            'success'
                                        );

                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: data,
                                            success: function(result) {
                                                if (result.success ==
                                                    true) {
                                                    swal(
                                                        'Success',
                                                        result.msg,
                                                        'success'
                                                    );
                                                    setTimeout(() => {
                                                        location
                                                            .reload();
                                                    }, 1500);
                                                    location.reload();
                                                } else {
                                                    swal(
                                                        'Error',
                                                        result.msg,
                                                        'error'
                                                    );
                                                }
                                            },
                                        });

                                    } else {
                                        swal(
                                            'Failed!',
                                            'Wrong Password!',
                                            'error'
                                        )

                                    }
                                }
                            });
                        }
                    });
                }
            });
        });


        $(".daterangepicker-field").daterangepicker({
            callback: function(startDate, endDate, period) {
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date = endDate.format('YYYY-MM-DD');
                var title = start_date + ' To ' + end_date;
                $(this).val(title);
                $('input[name="start_date"]').val(start_date);
                $('input[name="end_date"]').val(end_date);
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });


        $(document).on('click', "#power_off_btn", function(e) {
            let cash_register_id = $('#cash_register_id').val();
            let is_register_close = parseInt($('#is_register_close').val());
            if (!is_register_close) {
                getClosingModal(cash_register_id);
                return 'Please enter the closing cash';
            } else {
                return;
            }
        });


        function getClosingModal(cash_register_id, type = 'close') {
            $.ajax({
                method: 'get',
                url: '/cash/add-closing-cash/' + cash_register_id,
                data: {
                    type
                },
                contentType: 'html',
                success: function(result) {
                    $('#closing_cash_modal').empty().append(result);
                    $('#closing_cash_modal').modal('show');
                },
            });
        }
        $(document).on('click', '#closing-save-btn, #adjust-btn', function(e) {
            $('#is_register_close').val(1);
        })
        $(document).on('click', '#logout-btn', function(e) {
            let cash_register_id = $('#cash_register_id').val();

            let is_register_close = parseInt($('#is_register_close').val());
            if (!is_register_close) {
                getClosingModal(cash_register_id, 'logout');
                return 'Please enter the closing cash';
            } else {
                $('#logout-form').submit();
            }
        })
        $(document).on('click', '.close-btn-add-closing-cash', function(e) {
            e.preventDefault()
            $('form#logout-form').submit();
        })
        $(document).on('click', '.notification-list', function() {
            $.ajax({
                method: 'get',
                url: '/notification/notification-seen',
                data: {},
                success: function(result) {
                    if (result) {
                        $('.notification-number').text(0);
                        $('.notification-number').addClass('hide')
                    }
                },
            });
        })
        $(document).on('click', '.notification_item', function(e) {
            e.preventDefault();
            let mark_read_action = $(this).data('mark-read-action');
            let href = $(this).data('href');
            $.ajax({
                method: 'get',
                url: mark_read_action,
                data: {},
                success: function(result) {

                },
            });
            window.open(href, '_blank');
        })
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        $('input').attr('autocomplete', 'off');
    </script>
</body>

</html>
