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
    @include('back-end.layouts.partials.css')
    <style>
        .red {
            color: #b90000;
        }
        span.count-span-side-bar {
            float: right;
            color: #fff;
            background-color: #00c9db;
            padding-right: .6em;
            padding-left: .6em;
            border-radius: 10rem;
            display: inline-block;
            padding: .3125em .5em;
            font-size: 75%;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .3125rem;
            transition: all .2s ease-in-out;
        }
        span.pending {
            color: #00c9db !important;
            background-color: rgba(0, 201, 219, .05) !important;

        }
        span.approved {
            color: #00c9a7;
            background-color: rgba(0, 201, 167, .1);
        }
        span.completed {
            color: #fff;
            background-color: #00c9a7;

        }

        span.canceled {
            color: #fff;
            background-color: #ed4c78;
        }
        span.span-status {
            padding: 5px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
    @if(app()->getLocale() == 'ar')
        <style>
            body{
                direction: rtl;
                text-align: right;
            }
            .page {
                position: relative;
                margin-right: 230px;
                margin-left: 0 !important;
            }
            .page.active{
                margin-left: 0 !important;
                margin-right: 0 !important;

            }
            span.count-span-side-bar {
                float: left!important;
            }
            .side-navbar {
                left: auto;
                right: 0;
                text-align: right;
                direction: rtl;
            }


            .list-unstyled {
                padding-left: 0;
                padding-right: 0;
            }
            .side-navbar li a[data-toggle="collapse"]::before {
                right: auto;
                left: 5%;
            }
            apan.count-span-side-bar {
                float: left;

            }
            nav.navbar .nav-menu li:last-child .dropdown-menu {
                float: left;
                right: auto;
                left: 20px;
            }
            .dropdown-menu.edit-options li a, .dropdown-menu.edit-options li .btn-link {
                text-align: right;
            }
        </style>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

{{--    new di--}}
    <style>
        .logo-div {
            width: 220px;
            padding: 0 5px;
            background-color: #013e6b;
        }
        header .container-fluid {
            padding: 0;
        }
        .brand-big {
            left: auto !important;
            right: 0;
        }
        nav.navbar a.menu-btn {
            width: 25px;
            height: 25px;
            line-height: 28px;
        }
        nav.navbar a.menu-btn {
            border: 1px solid #ffffff;
            color: #ffffff;
        }
        nav.navbar a.menu-btn:hover {
            background: #ffffff;
            border: 1px solid #ffffff;
            color: #013e6b;
        }
        .side-navbar {
            width: 220px;
        }
        .page {
            margin-right: 220px;
        }
        .side-navbar li a {
            margin: 0;
            padding: 8px 12px;
        }
        .side-navbar {
            background-color: #013e6b;
        }
        .side-navbar li a,.side-navbar li a i {
            color: #ffffff;
        }
        .side-navbar li a:focus, .side-navbar li a:hover, .side-navbar li.active > a, .side-navbar li a[aria-expanded="true"] {
            color: #013e6b !important;
        }
        .side-navbar li a:focus i , .side-navbar li a:hover i , .side-navbar li.active > a i  {
            color: #013e6b;
        }
        .logo-div {
            width: 220px;
            padding: 0 5px;
            background-color: #013e6b;
            height: 65px;
        }
        .side-navbar{
            padding-left: 0!important;
        }
        .main-menu li {
            background-color: #013e6b;
        }
        nav.navbar {
            background: #f5f5f5!important;
            border-bottom: none;
        }
        .side-navbar li.li-item.active ul a {
            background: none;
        }
        .side-navbar li.li-item.active ul a:hover {
            background: #f5f5f5;
        }
        .mCS-dir-rtl > .mCSB_inside > .mCSB_container {
            margin-right: 0;
            margin-left: 0 !important;
        }
        div#content {
            background-color: #f5f5f5!important;
            padding-top: 20px;
        }
        .main-menu {
            background-color: #f8f8f8;
        }
        .main-menu ,li.li-item.active {
            background: linear-gradient( to right, #f8f8f8 0%, #f8f8f8 50%,  #013e6b 50%,  #013e6b 100% );
        }
        li.li-item.active a {
            margin-right: 20px;
            border-radius: 0 25px;
        }
        .side-navbar li.li-item.active a {
            background: #f8f8f8;
        }
        .side-navbar li ul li a{
            border-radius: 20px !important;
        }
        .side-navbar li ul{
            padding: 0 !important;
        }
        .next-active {
            border-radius: 20px 0 0;
        }
        .previous-active {
            border-radius:  0  0 0 20px;
        }
        .sp-label {
            position: absolute;
            top: -10px;
            right: 25px;
            padding: 0 10px;
            z-index: 2;
            background: linear-gradient(
        to top,
        #fdfdff 0%,
        #fdfdff 50%,
        whitesmoke 50%,
        whitesmoke 100%
        );
        }
        .next-active-children {
            border-radius: 20px 0 0 !important;
            padding-right: 10px !important;
            margin-right: 20px !important;
            background-color: #013E6B !important;
        }

        .form-control, .input-group-text, .bootstrap-select.form-control {
            border-color: #9c9f9c;
        }
        li.sli_li {
            right: 20%;
            position: absolute;
            color: #013E6B;
            font-size: 20px;
            font-weight: 500;
        }
        ul#orders {
            padding: 5px 30px 5px 10px;
            margin: 0 ;
            border-radius: 0;
            background-color: #013e6b;
        }
        .next-active-children {
            border-radius: 20px 0 0 !important;
        }
        .li-item li.active,.li-item li.active a {
            color: #ffffff !important;
            background: #013e6b !important;
        }
        .side-navbar li ul li a {

            margin-right: 13px;
        }
        .li-item  li::before, .li-item li::after {
            position: absolute;
            display: flex;
        }
        .li-item li::before {
            content: ' ';
            right: 10px;
            top: 13px;
            height: 8px;
            width: 8px;
            background-color: #fff;
            align-items: center;
            border-radius: 50%;
            justify-content: center;
        }
        .li-item li.active::before {
            background-color: #7a0d0d !important;
            box-shadow: 0px 0px 5px 2px #ffffffb5;
        }
        li.sli_li span.parent {
            color: #7a7a7a;
        }
        .badge-primary {
            color: #013E6B !important;
            background-color: #D6E7FE;
            font-weight: 800;
            border: 0;
            padding: 5px;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .badge-warning{
            color: #D6E7FE !important;
            background-color: #013E6B;
            font-weight: 800;
            border: 0;
            padding: 5px;
            font-size: 13px;
            margin-bottom: 5px;
        }
       .dataTables_length, .dt-buttons {

            width: 20%;
        }
        .dt-buttons.btn-group {
            border: 1px solid #0000003d;
            border-radius: 20px;
            margin: 0 15px;
        }
        .dt-buttons button span {
            color: #000;
        }
        .buttons-print,.buttons-csv,.dt-buttons.btn-group .btn-secondary {
            background: none;
            border-left: 1px solid #00000045 !important;
            color: #000 !important;
            border-radius: 0;
            border: none;
        }
        button.btn.btn-secondary.buttons-collection.dropdown-toggle.buttons-colvis {
            border-left: none !important;
        }
        .dt-buttons {

            width: 45%;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #F6F5FD !important;
        }
        .name strong {
            font-size: 13px;
            color: #00000063 !important;
        }
        .sp-label {
            font-size: 13px;
        }
        .count-title .count-number {
            font-size: 18px !important;
        }
        .page {

            width: calc(100% - 220px);
        }
        .mCSB_scrollTools {
            width: 10px !important;
        }
        .mCSB_dragger_bar {
            width: 7px !important;
        }
        .mCS-dir-rtl > .mCSB_inside > .mCSB_scrollTools, .mCS-dir-rtl > .mCSB_outside + .mCSB_scrollTools {
            right: 0;
            left: auto;
        }
        ul.nav-menu.list-unstyled.d-flex.flex-md-row.align-items-md-center img {
            width: 30px;
        }
        nav.navbar .nav-item a i {
            font-size: 20px;
        }
        .next-active-children li, .next-active-children li.active, .side-navbar li.li-item.active ul a {
            padding-right: 10px !important;
            color: #013e6b !important;
            background-color: #f8f8f8 !important;
        }
        .next-active-children li:last-child {
            border-radius: 0 0 18px 0;
        }

        .li-item li::before{
            background-color: #013E6B;
        }
        .li-item li.active::before {
            background-color: #7a0d0d !important;
            box-shadow: 0px 0px 5px 2px #2b2b2bb5;
        }
    </style>
<style>

</style>
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
    @if(app()->getLocale() =="en")
        <style>
            .brand-big {
                right: auto !important;
                left: 10% !important;
            }
        </style>
    @endif
    @yield('styles')
</head>

<body onload="myFunction()">
    <div id="loader"></div>
    @include('back-end.layouts.partials.header')
    <div class="page">
        @include('back-end.layouts.partials.sidebar')
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
            @yield('content')
        </div>

        @include('back-end.layouts.partials.footer')


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

        @endphp

        <div id="closing_cash_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal text-left">
        </div>

        <!-- This will be printed -->
        <section class="invoice print_closing_cash print-only" id="print_closing_cash"> </section>
        <div class="modal view_modal no-print" role="dialog" aria-hidden="true"></div>

    </div>

    <script type="text/javascript">

        base_path = "{{ url('/') }}";
        current_url = "{{ url()->current() }}";
    </script>

    @include('back-end.layouts.partials.javascript')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        $('#side-main-menu .li-item.active').prev().addClass("previous-active");
        $('#side-main-menu .li-item.active').next().addClass("next-active");
        if($('#side-main-menu .li-item.active').hasClass('have-children')) {
            $('#side-main-menu .li-item.active ul').addClass('next-active-children');
        }
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

                $('#logout-form').submit();

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
