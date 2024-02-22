<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $logo = \Settings::get('logo','logo.svg');
    $logo_footer = \Settings::get('logo_footer','footer_logo.svg');
@endphp
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
        body{
            background-color: #013e6b;
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
            .bootstrap-select.btn-group .dropdown-toggle .filter-option {
                padding-right: 10px  !important;
                text-align: right !important;
            }
            .dropdown-toggle::after {
                position: absolute !important;
                left: 0 !important;
                top: 49% !important;
            }
        </style>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

{{--    new di--}}
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #fff inset !important;
        }
        .dataTables_filter input.form-control.form-control-sm {
            background-color: #fff0;
            border-radius: 10px;
            padding: 5px 30px;
            color: #000;
            background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2ZXJzaW9uPSIxLjEiICAgaWQ9InN2ZzQ0ODUiICAgdmlld0JveD0iMCAwIDIxLjk5OTk5OSAyMS45OTk5OTkiICAgaGVpZ2h0PSIyMiIgICB3aWR0aD0iMjIiPiAgPGRlZnMgICAgIGlkPSJkZWZzNDQ4NyIgLz4gIDxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhNDQ5MCI+ICAgIDxyZGY6UkRGPiAgICAgIDxjYzpXb3JrICAgICAgICAgcmRmOmFib3V0PSIiPiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC9kYzpmb3JtYXQ+ICAgICAgICA8ZGM6dHlwZSAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4gICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPiAgICAgIDwvY2M6V29yaz4gICAgPC9yZGY6UkRGPiAgPC9tZXRhZGF0YT4gIDxnICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0xMDMwLjM2MjIpIiAgICAgaWQ9ImxheWVyMSI+ICAgIDxnICAgICAgIHN0eWxlPSJvcGFjaXR5OjAuNSIgICAgICAgaWQ9ImcxNyIgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjAuNCw4NjYuMjQxMzQpIj4gICAgICA8cGF0aCAgICAgICAgIGlkPSJwYXRoMTkiICAgICAgICAgZD0ibSAtNTAuNSwxNzkuMSBjIC0yLjcsMCAtNC45LC0yLjIgLTQuOSwtNC45IDAsLTIuNyAyLjIsLTQuOSA0LjksLTQuOSAyLjcsMCA0LjksMi4yIDQuOSw0LjkgMCwyLjcgLTIuMiw0LjkgLTQuOSw0LjkgeiBtIDAsLTguOCBjIC0yLjIsMCAtMy45LDEuNyAtMy45LDMuOSAwLDIuMiAxLjcsMy45IDMuOSwzLjkgMi4yLDAgMy45LC0xLjcgMy45LC0zLjkgMCwtMi4yIC0xLjcsLTMuOSAtMy45LC0zLjkgeiIgICAgICAgICBjbGFzcz0ic3Q0IiAvPiAgICAgIDxyZWN0ICAgICAgICAgaWQ9InJlY3QyMSIgICAgICAgICBoZWlnaHQ9IjUiICAgICAgICAgd2lkdGg9IjAuODk5OTk5OTgiICAgICAgICAgY2xhc3M9InN0NCIgICAgICAgICB0cmFuc2Zvcm09Im1hdHJpeCgwLjY5NjQsLTAuNzE3NiwwLjcxNzYsMC42OTY0LC0xNDIuMzkzOCwyMS41MDE1KSIgICAgICAgICB5PSIxNzYuNjAwMDEiICAgICAgICAgeD0iLTQ2LjIwMDAwMSIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==);
            background-repeat: no-repeat;
            direction: rtl;
            background-position: right !important
        }
        .dataTables_info, .dataTables_paginate{
            width: auto !important;
        }
        ul.pagination {
            border-radius: 15px !important;
            background-color: #fff;
            border: 1px solid #96aaaf26;
            padding: 0 !important;
        }
        li.paginate_button.page-item a.page-link {
            border-radius: 50%;
            padding: 4px 10px;
            border: none;
        }
        table.dataTable td a {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 4px 7px 2px;
            text-align: center;
        }
        table.dataTable td a i {
            color: #013e6bcf;
        }
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
        ul#side-main-menu {
            background-color: #ffffff00;
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
         .side-navbar li.active > a{
            color: #013e6b !important;
        }
         .side-navbar li.active > a i  {
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


        /*.main-menu .next-active-children li*/
        .main-menu  .active-children li  ,.main-menu  .next-active-children li {

            background-color: #f8f8f8 !important;

        }
        .main-menu  li {
            background-color: #013e6b !important;
        }
        nav.navbar {
            background: #f8f8f8!important;
            border-bottom: none;
        }
        .side-navbar li.li-item.active ul a {
            background: none;
        }
        .side-navbar li.li-item.active ul a:hover {
            background: #f8f8f8;
        }
        .mCS-dir-rtl > .mCSB_inside > .mCSB_container {
            margin-right: 0;
            margin-left: 0 !important;
        }
        div#content {
            background-color: #f8f8f8!important;
            padding-top: 20px;
        }
        .main-menu {
            background-color: #f8f8f8;
        }
        .main-menu ,li.li-item.active {
            background: linear-gradient( to right, #f8f8f8 0%, #f8f8f8 50%,  #013e6b 50%,  #013e6b 100% );
        }
        li.li-item.active a.a-itemOne {
            margin-right: 20px;
            border-radius: 25px;
        }
        li.li-item.active a.a-itemhavecheld {
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
        .active-children ,.next-active-children {
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

        .side-navbar li ul {


            margin: 0 ;
        }
        .next-active-children , .active-children {
            border-radius: 20px 0 0 !important;
        }

        .active-children li , .next-active-children li, .next-active-children li.active, .side-navbar li.li-item.active ul a {
            padding-right: 10px !important;
            color: #013e6b !important;
            background-color: #f8f8f8 !important;
        }
        .active-children li:last-child  ,.next-active-children li:last-child {
            border-radius: 0 0 18px 0;
        }

        .li-item li.active::before{
            background-color: #013E6B;
        }
        .li-item li.active::before {
            background-color: #7a0d0d !important;
            box-shadow: 0px 0px 5px 2px #013e6b91;
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
        .li-item li::before {
            background-color: #013e6a;
        }
        .li-item li.active::before {
            background-color: #7a0d0d !important;
            box-shadow: 0px 0px 5px 2px #013e6b91;
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
        span.title-collapse {
            padding: 0 5px;
        }

        li.li-item.active:hover > a:hover,li.li-item.active:focus > a:hover ,
        .side-navbar li.li-item.active li:hover > a:hover, .side-navbar li.li-item.active li:focus > a:hover{
            text-shadow: none !important;
        }
        .side-navbar li:hover::before, .side-navbar li:focus::before{
            background-color: #7a0d0d !important;
            box-shadow: 0px 0px 5px 2px #013e6b91;
        }
        {
            direction: ltr;
        }
        .side-navbar.overflow{
            overflow-y: auto !important;
        }
        .side-navbar-wrapper {
            direction: rtl;
        }
        a.a-itemhavecheld.collapsed {
            border-radius: 25px !important;
        }
        .logo-div.active {

            background-color: #f8f8f8;

            color: #013e6b;
        }
        .menu-btn.active {
            border: 1px solid #013e6b !important;
            color: #013e6b !important;
        }

        .form-group {
            margin: 10px;
        }
        .sp-label.new-des{
            top: 0 !important;
            right: 35px !important;
            background: linear-gradient( to top, #fdfdff 0%, #fdfdff 50%, #ffffff00 50%, #ffffff00 100% ) !important;
        }
        .bootstrap-select .dropdown-toggle:focus {
            outline: none !important;
        }
        .btn-link:hover ,.btn-link {
            text-decoration: none !important;
        }
        .title-page-item{
            margin: 5px 10px 15px;
        }
        a.btn.btn-create {
            color: #013E6B;
            font-size: 20px;
            font-weight: 500;
            border-radius: 15px;
            background-color: #D6E7FE;
        }
        .print-title {
            width: 80%;
            margin-top: 5px;
            /* justify-content: center; */
            font-size: 20px;
            font-weight: 500;
        }
        .dev-create {
            float: left;
        }
        .card-header.d-flex.align-items-center {
            border-radius: 15px;
        }
        .form-group.justify-cont{
            justify-content: center;
            display: flex;

        }
        .form-group.justify-cont input {
            width: 150px;
        }
        span.well {
            float: inline-end;
            padding: 0 20px;
            color: #134264;
            font-size: 18px;
            font-weight: 500;
        }
        table.dataTable td, table.dataTable th {
            text-align: center;
        }
        .dt-buttons.btn-group, button.btn.btn-secondary.buttons-collection.dropdown-toggle.buttons-colvis, .dt-buttons button span, button.btn.dropdown-toggle.btn-default.btn-light {
            background-color: #F9F9FB;
        }
        i.dripicons-trash {
            color: #b70c06 !important;
        }
        th.notexport.sorting ,th.notexport.sorting_disabled{
            min-width: 90px !important;
        }
        table.dataTable td a.a-image{
            padding: 4px 7px 4px !important;
        }
        .parent a {
            font-size: 17px !important;
        }
        .swal-footer {
            text-align: center;
        }
        div.dataTables_wrapper div.dataTables_filter {
            float: right !important;
            text-align: right;
        }
        button.close {
            float: left !important;
            margin: auto auto auto 0 !important;
            padding: 0 !important;
            color: #a30000 !important;
            font-weight: 800;
        }
        .modal-footer {
            width: 100%;
            justify-content: center;
        }
        .modal-footer button {
            margin: auto 10px;
            min-width: 100px;
        }
        td.dataTables_empty {
            padding: 30px 0;
        }
        div.dataTables_wrapper div.dataTables_processing {
            background-color: #fff0 !important;
            top: 10% !important;
        }
        ul.pagination {
            border-radius: 15px !important;
            background-color: #fff;
            border: 1px solid #96aaaf00;
            padding: 1px !important;
            margin: 5px !important;
            box-shadow: 1px 1px 8px 0px #00000026;
        }
        li.paginate_button.page-item a.page-link {
            border-radius: 50%;
            padding: 2px 8px;
            margin-top: 2px;
            border: none;
        }
        ul.pagination .paginate_button.page-item.previous a  ,ul.pagination .paginate_button.page-item.next a{
            color: #000 !important;
            font-weight: 800;
            font-size: 15px;
            padding: 2px 8px;
            margin: 2px 6px;
            border-radius: 50%;
            border: 1px solid #0000002e;
        }
        .input-group>.custom-file:focus, .input-group>.custom-select:focus, .input-group>.form-control:focus {
            z-index: 0;
        }
        .side-navbar.lodaing{
            z-index: 1!important;
        }
        #loader {
            position: absolute;
        }
        div#lay_out_loader {
            position: absolute;
            z-index: 100;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.54) !important;
        }
        body.overflow-h {
            overflow: hidden;
        }
        button, input, optgroup, select, textarea {
            font-family: "Nunito", sans-serif !important;
        }
        @media screen and ( min-width: 1600px) {
            .dt-buttons {
                width: 31%;
                margin: 0 6%  0 10%!important;
            }
            li.sli_li {
                right: 14%;
            }
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

    @yield('styles')
    @if(app()->getLocale() =="en")
        <style>
            button.close {
                float: right !important;
                margin: auto 0 auto auto !important;
                padding: 0 !important;
                color: #a30000 !important;
                font-weight: 800;
            }
            .brand-big {
                right: auto !important;
                left: 10% !important;
            }
            .brand-big {
                right: auto !important;
                left: 8% !important;
            }
            li.sli_li {
                left: 20% !important;
                right: auto !important;
            }
            .main-menu {
                direction: ltr !important;
            }
            .mCSB_scrollTools {
                left: 0 !important;
                right: auto !important;
            }
            .page {
                margin-right: auto !important;
                margin-left: 220px !important;
            }
            li.li-item.active a.a-itemhavecheld {
                margin-right: auto !important;
                margin-left: 20px;
                border-radius: 25px 0;
            }

            .next-active-children, .active-children {
                border-radius: 20px 0 0 !important;
            }
            .active-children, .next-active-children{
                margin-right: auto !important;
                margin-left: 20px !important;
            }
            .next-active {
                border-radius:  0 0 20px !important;
            }
            .previous-active {
                border-radius: 0 0 20px !important;
            }
            .active-children li, .next-active-children li, .next-active-children li.active, .side-navbar li.li-item.active ul a {
                padding-right: 0 !important;
                padding-left: 15px !important;
                margin-right: 0px !important;
            }
            .li-item li::before {
                right: 0;
                left: 5px;
            }
            .main-menu, li.li-item.active {
                background: linear-gradient( to right, #013e6b 0%, #013e6b 50%, #f8f8f8 50%, #f8f8f8 100% );
            }
            .next-active {
                border-radius: 0 20px !important;
            }
            .active-children li:last-child, .next-active-children li:last-child {
                border-radius: 0 0 0 18px;
            }
            @media screen and ( min-width: 1600px) {

                li.sli_li {
                    left: 14% !important;
                    right: auto !important;
                }
            }
        </style>
    @endif
</head>

<body class="overflow-h" onload="myFunction()">

    <div id="lay_out_loader">
        <div id="loader"></div>
    </div>
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
    <script type="text/javascript" src="{{asset('assets/back-end/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript"
            src="{{asset('assets/back-end/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script>


        $('#toggle-btn').click(function(){
            image="{{asset('assets/images/settings/'.$logo_footer)}}";
            if($(this).attr('is-active') == 'true'){
                $('.logo-div').removeClass('active');
                $(this).removeClass('active');
                $(this).attr('is-active','false');
            }else{
                image="{{asset('assets/images/settings/'.$logo)}}";
                $('.logo-div').addClass('active');
                $(this).addClass('active');
                $(this).attr('is-active','true');
            }
            $('.brand-big img').attr('src',image);
        });
        $('.a-itemhavecheld').click(function(){
            if($(this).parent().find('.active').length <= 0){
                $(this).parent().addClass('active');
                $(this).parent().removeClass('next-active');
                $(this).parent().prev().addClass("previous-active");
                $(this).parent().next().addClass("next-active");
                $(this).parent().children('ul').addClass("next-active-children");
                $('#side-main-menu li ul').removeClass('active-children');
                url = $(this).attr('d-hrf');
                window.location.href = url;
            }

        });
        $('#side-main-menu .li-item.active').prev().addClass("previous-active");
        $('#side-main-menu .li-item.active').next().addClass("next-active");
        $('#side-main-menu .li-item.active ul').addClass('next-active-children');
        $('#side-main-menu li ul').removeClass('active-children');
    </script>
    @include('back-end.layouts.partials.javascript')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        var channel = pusher.subscribe('notify-channel');
        channel.bind('new-notify', function(data) {
            if (data) {
                let badge_count = parseInt($('.online-order-badge').text()) + 1;
                $('.online-order-badge').text(badge_count);
                $('.online-order-badge').show();
                
                        $.ajax({
                            method: 'get',
                            url: "{{route('admin.admin-notifications.get-details','')}}/" + data.id,
                            data: {},
                            success: function(result) {
                                let notification_number = parseInt($('.notification-number').text());
                                console.log(notification_number, 'notification-number');
                                if (!isNaN(notification_number)) {
                                    notification_number = parseInt(notification_number) + 1;
                                } else {
                                    notification_number = 1;
                                }
                                $('.notification-list').empty().append(
                                    `<i class="dripicons-bell"></i><span class="badge badge-danger notification-number">${notification_number}</span>`
                                );
                                $('.notifications').prepend(result);
                                $('.no_new_notification_div').addClass('hide');

                                },
                            });
            }
        });



        var table = $(".dataTable").DataTable({
            lengthChange: true,
            paging: true,
            info: false,
            bAutoWidth: false,
            language: {
                search: "",
                searchPlaceholder:"{{\App\CPU\translate('Look for...')}}",
                "lengthMenu":     "{{\App\CPU\translate('Show')}} _MENU_ {{\App\CPU\translate('entries')}}",
                "paginate": {
                    "next":       ">",
                    "previous":   "<"
                },
                buttons: {
                    colvis:"{{\App\CPU\translate('Column visibility')}}"
                }
            },
            lengthMenu: [
                [10, 25, 50, 75, 100, 200, 500, -1],
                [10, 25, 50, 75, 100, 200, 500, "All"],
            ],

            columnDefs: [
                {
                    targets: "date",
                    type: "date-eu",
                },
            ],
            initComplete: function () {
                $(this.api().table().container())
                    .find("input")
                    .parent()
                    .wrap("<form>")
                    .parent()
                    .attr("autocomplete", "off");
            },
            dom: "lBfrtip",
            stateSave: true,
            buttons: buttons,
            footerCallback: function (row, data, start, end, display) {
                var intVal = function (i) {
                    return typeof i === "string"
                        ? i.replace(/[\$,]/g, "") * 1
                        : typeof i === "number"
                            ? i
                            : 0;
                };

                this.api()
                    .columns(".sum", { page: "current" })
                    .every(function () {
                        var column = this;
                        if (column.data().count()) {
                            var sum = column.data().reduce(function (a, b) {
                                a = intVal(a);
                                if (isNaN(a)) {
                                    a = 0;
                                }

                                b = intVal(b);
                                if (isNaN(b)) {
                                    b = 0;
                                }

                                return a + b;
                            });
                            $(column.footer()).html(
                                __currency_trans_from_en(sum, false)
                            );
                        }
                    });
            },
        });
        table.columns(".hidden").visible(false);
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
            document.getElementById("lay_out_loader").style.display = "none";
            $('.side-navbar').removeClass('lodaing');
            $('body').removeClass('overflow-h');

            document.getElementById("content").style.display = "block";
            document.addEventListener("DOMContentLoaded", function() {
                // Find the elements with the class name "side-navbar"
                var sideNavbars = document.getElementsByClassName("side-navbar");

                // Check if any elements were found
                if (sideNavbars.length > 0) {
                    // Add the "overflow" class to each found element
                    for (var i = 0; i < sideNavbars.length; i++) {
                        sideNavbars[i].classList.add("overflow");
                    }
                } else {
                    // Log an error if no elements were found
                    console.error("No elements with class 'side-navbar' found.");
                }
            });
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
