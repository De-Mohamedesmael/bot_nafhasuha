<!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow" />

        <title> - {{ config('app.name', 'POS') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <style>
            .table-bordered td, .table-bordered th {
                border: 1px solid #dee2e6;
                text-align: center;
            }
            .table-bordered thead{
                display: none !important;
            }
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
            span.span-report {
                width: 100%;
                margin-bottom: 5px;

            }
            .th-next{
                display: none !important;
            }
            span.span-report span {
                float: right;
            }
            .strong-date-number {
                border: 1px solid #013e6b;
                padding: 5px 8px;
                border-radius: 10px;
                color: #013e6b;
            }
            .strong-date-number.this-day {
                border: 1px solid #bf0000;
                padding: 5px 8px;
                border-radius: 10px;
                color: #bf0000;
            }
            td {
                padding: 0;
            }
            td.td-title {
                width: 200px;
                padding: 0;
            }
            .table-data-calender{
                width: 100%;
            }


            tr.tr-data{
                color: #013e6b;
                font-size: 15px;
                font-weight: 500;
            }
            tr.tr-data-s {
                background-color: #f6f5fd;

            }
            td.td-data {
                text-align: center;
                padding: 7px;

            }


            .data-yearly input.daterangepicker-field.form-control {
                direction: ltr !important;
            }
            .data-yearly table {
                width: 100%;
            }
            .data-yearly.table-order {
                border: 1px solid #e5e5e5;
                border-radius: 15px;
                padding: 0;
            }
            .data-yearly td {
                font-size: 16px;
                font-weight: 500;
                color: #144d79;
                padding: 15px 0;
            }
            .data-yearly td.title-table {
                font-size: 18px;
                font-weight: 700;
                color: #25364F;
                padding: 15px 0;
            }
            .data-yearly tr.tr-sp {
                background-color: #f6f5fd;
            }
        </style>
        @if(app()->getLocale() =="ar")

            <style>
                span.span-report span {
                    float: left;
                }
            </style>
        @endif
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

    <body >


    <div class="">
        {!! $content_html !!}

    </div>

    </body>

    </html>




