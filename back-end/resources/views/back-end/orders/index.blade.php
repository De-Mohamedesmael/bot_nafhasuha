@extends('back-end.layouts.app')
@section('title', __('lang.provider'))
@section('styles')
<style>
    input[type="checkbox"] {
        -webkit-appearance: none;
        appearance: none;
        visibility: hidden;
        display: none;
    }

    .check {
        position: relative;
        display: block;
        width: 70px;
        height: 30px;
        background-color: #f46a6a;
        cursor: pointer;
        border-radius: 20px;
        overflow: hidden;
        transition: ease-in 0.5s;
    }

    input:checked[type="checkbox"] ~ .check {
        background-color: #34c38f;
        /*   box-shadow: 0 0 0 1200px #092c3e; */
    }

    .check:before {
        content: '';
        position: absolute;
        top: 3px;
        left: 4px;
        background-color: #eff2f7;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        transition: all 0.5s;
    }

    input:checked[type="checkbox"] ~ .check:before {
        transform: translateX(-50px);
    }

    .check:after {
        content: '';
        position: absolute;
        top: 3px;
        right: 4px;
        background-color: #eff2f7;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        transform: translateX(50px);
        transition: all 0.5s;

    }

    input:checked[type="checkbox"] ~ .check:after {
        transform: translateX(0px);
    }
    .btn-modal {
        cursor: pointer;
    }
    td{
        text-align: center;
    }
</style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center">
            <h3 class="print-title">
               @if(!isset($status))  @lang('lang.view_all_orders')@else @lang('lang.view_all_orders_'.$status) @endif

            </h3>
        </div>


        @php
            $url=  route("admin.order.index",$status);

        @endphp
    </div>
    <div class="card mt-3 pt-2 pb-1">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('category_id',  __('lang.category') , []) !!}
                        {!! Form::select('category_id', $categories, request()->category_id, [
'class' => 'form-control filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                    </div>
                </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('city_id', __('lang.city') . ':', []) !!}
                            {!! Form::select('city_id', $cities, request()->city_id, [
'class' => 'form-control filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('area_id', __('lang.area') . ':', []) !!}
                            {!! Form::select('area_id', $areas, request()->area_id, [
'class' => 'form-control filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('user_id', __('lang.client') . ':', []) !!}
                            {!! Form::select('user_id', $users, request()->user_id, [
'class' => 'form-control
                    filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                        </div>
                    </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('provider_id', __('lang.provider') . ':', []) !!}
                        {!! Form::select('provider_id', $providers, request()->provider_id, [
'class' => 'form-control
                    filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('payment_method', __('lang.payment_method') . ':', []) !!}
                        {!! Form::select('payment_method', ['Online'=>__('lang.Online'),'Cash'=>__('lang.Cash'),'Wallet'=>__('lang.Wallet')], request()->payment_method, [
'class' => 'form-control
                    filter_product
                    selectpicker',
'data-live-search' => 'true',
'placeholder' => __('lang.all'),
]) !!}
                    </div>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('start_date', __('lang.generation_start_date'), []) !!}
                        {!! Form::text('start_date', request()->start_date, ['class' => 'form-control filter_product']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('start_time', __('lang.generation_start_time'), []) !!}
                        {!! Form::text('start_time', null, ['class' => 'form-control time_picker filter_product']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('end_date', __('lang.generation_end_date'), []) !!}
                        {!! Form::text('end_date', request()->end_date, ['class' => 'form-control filter_product']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('end_time', __('lang.generation_end_time'), []) !!}
                        {!! Form::text('end_time', null, ['class' => 'form-control time_picker filter_product']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('complete_start_date', __('lang.complete_start_date'), []) !!}
                        {!! Form::text('complete_start_date', request()->complete_start_date, ['class' => 'form-control datepicker filter_product', 'id' => 'complete_start_date']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('complete_start_time', __('lang.complete_start_time'), []) !!}
                        {!! Form::text('complete_start_time', null, ['class' => 'form-control time_picker filter_product']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('complete_end_date', __('lang.complete_end_date'), []) !!}
                        {!! Form::text('complete_end_date', request()->complete_end_date, ['class' => 'form-control filter_product', 'id' => 'complete_end_date']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('complete_end_time', __('lang.complete_end_time'), []) !!}
                        {!! Form::text('complete_end_time', null, ['class' => 'form-control time_picker filter_product']) !!}
                    </div>
                </div>

                <input type="hidden" name="product_id" id="product_id" value="">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success mt-4 ml-2" id="submit-filter">@lang('lang.filter')</button>

                    <button class="btn btn-danger mt-4 clear_filters">@lang('lang.clear_filters')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-3 mb-0">
        <div class="col-md-12">
            <button type="button" value="0"
                    class="badge badge-pill badge-primary column-toggle">@lang('lang.invoice_no')</button>

            <button type="button" value="1" class="badge badge-pill badge-primary column-toggle">@lang('lang.service_title')</button>
            <button type="button" value="2" class="badge badge-pill badge-primary column-toggle">@lang('lang.client_name')</button>
            <button type="button" value="3" class="badge badge-pill badge-primary column-toggle">@lang('lang.client_phone')</button>
            <button type="button" value="4" class="badge badge-pill badge-primary column-toggle">@lang('lang.provider_name')</button>
            <button type="button" value="5" class="badge badge-pill badge-primary column-toggle">@lang('lang.provider_phone')</button>
            <button type="button" value="6" class="badge badge-pill badge-primary column-toggle">@lang('lang.suggested_price')</button>
            <button type="button" value="7" class="badge badge-pill badge-primary column-toggle">@lang('lang.grand_total')</button>
            <button type="button" value="8" class="badge badge-pill badge-primary column-toggle">@lang('lang.discount_amount')</button>
            <button type="button" value="9" class="badge badge-pill badge-primary column-toggle">@lang('lang.final_total')</button>
            <button type="button" value="10" class="badge badge-pill badge-primary column-toggle">@lang('lang.payment_method')</button>
            <button type="button" value="11" class="badge badge-pill badge-primary column-toggle">@lang('lang.status')</button>
            <button type="button" value="12" class="badge badge-pill badge-primary column-toggle">@lang('lang.image')</button>
            <button type="button" value="13" class="badge badge-pill badge-primary column-toggle">@lang('lang.address')</button>
            <button type="button" value="14" class="badge badge-pill badge-primary column-toggle">@lang('lang.cancel_reason')</button>
            <button type="button" value="15" class="badge badge-pill badge-primary column-toggle">@lang('lang.canceled_by')</button>
            <button type="button" value="16" class="badge badge-pill badge-primary column-toggle">@lang('lang.updated_at')</button>
            <button type="button" value="17" class="badge badge-pill badge-primary column-toggle">@lang('lang.created_at')</button>
            <button type="button" value="18" class="badge badge-pill badge-primary column-toggle">@lang('lang.action')</button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="order_table" class="table">
            <thead>
                <tr>
                    <th>@lang('lang.invoice_no')</th>

                    <th>@lang('lang.service_title')</th>

                    <th >@lang('lang.client_name')</th>
                    <th >@lang('lang.client_phone')</th>
                    <th >@lang('lang.provider_name')</th>
                    <th >@lang('lang.provider_phone')</th>
                    <th class="sum">@lang('lang.suggested_price')</th>
                    <th class="sum">@lang('lang.grand_total')</th>
                    <th class="sum">@lang('lang.discount_amount')</th>
                    <th class="sum">@lang('lang.final_total')</th>
                    <th >@lang('lang.payment_method')</th>
                    <th>@lang('lang.status')</th>
                    <th >@lang('lang.image')</th>
                    <th >@lang('lang.address')</th>
                    <th>@lang('lang.cancel_reason')</th>
                    <th>@lang('lang.canceled_by')</th>
                    <th>@lang('lang.updated_at')</th>
                    <th>@lang('lang.created_at')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">@lang('lang.total')</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('javascript')
    <script>

        order_table = $("#order_table").DataTable({
            lengthChange: true,
            paging: true,
            info: false,
            bAutoWidth: false,
            language: {
                url: dt_lang_url,
            },
            lengthMenu: [
                [10, 25, 50, 75, 100, 200, 500, -1],
                [10, 25, 50, 75, 100, 200, 500, "All"],
            ],
            dom: "lBfrtip",
            stateSave: true,
            buttons: buttons,
            processing: true,
            serverSide: true,
            aaSorting: [[0, "desc"]],
            initComplete: function () {
                $(this.api().table().container())
                    .find("input")
                    .parent()
                    .wrap("<form>")
                    .parent()
                    .attr("autocomplete", "off");
            },
            ajax: {
                url: '{{$url}}',
                data: function (d) {
                    d.category_id = $("#category_id").val();
                    d.city_id = $("#city_id").val();
                    d.area_id = $("#area_id").val();
                    d.user_id = $("#user_id").val();
                    d.provider_id = $("#provider_id").val();
                    d.payment_method = $("#payment_method").val();
                    d.start_date = $("#start_date").val();
                    d.start_time = $("#start_time").val();
                    d.end_date = $("#end_date").val();
                    d.end_time = $("#end_time").val();
                    d.complete_start_date = $("#complete_start_date").val();
                    d.complete_start_time = $("#complete_start_time").val();
                    d.complete_end_date = $("#complete_end_date").val();
                    d.complete_end_time = $("#complete_end_time").val();
                },
            },
            columnDefs: [
                {
                    targets: [7],
                    orderable: false,
                    searchable: false,
                },
            ],
            columns: [
                { data: "invoice_no", name: "transactions.invoice_no" },
                { data: "service_title", name: "service_title" },
                { data: "client_name", name: "users.name" },
                { data: "client_phone", name: "users.phone" },
                { data: "provider_name", name: "providers.name" },
                { data: "provider_phone", name: "providers.phone" },
                { data: "suggested_price", name: "transactions.suggested_price" },
                { data: "grand_total", name: "transactions.grand_total" },
                { data: "discount_amount", name: "transactions.discount_amount" },
                { data: "final_total", name: "transactions.final_total" },
                { data: "payment_method", name: "payment_method" },
                { data: "status", name: "status" },
                { data: "image", name: "image" },
                { data: "address", name: "address" },
                { data: "canceled_by", name: "canceled_by" },
                { data: "cancel_reason", name: "cancel_reason" },
                { data: "updated_at", name: "updated_at" },
                { data: "created_at", name: "created_at" },
                { data: "action", name: "action" },
            ],
            createdRow: function (row, data, dataIndex) {},
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
        $(document).on('click', '#submit-filter', function() {
            order_table.ajax.reload();
        });
        $(document).on('click', '.clear_filters', function() {
            $('.filter_product').val('');
            $('.filter_product').selectpicker('refresh');

            order_table.ajax.reload();
        });
        $(document).ready(function() {
            var hiddenColumnArray = JSON.parse('{!! addslashes(json_encode(Cache::get("key_" . auth()->id(), []))) !!}');

            $.each(hiddenColumnArray, function(index, value) {
                $('.column-toggle').each(function() {
                    if ($(this).val() == value) {
                        toggleColumnVisibility(value, $(this));
                    }
                });
            });

            $(document).on('click', '.column-toggle', function() {
                var column_index = parseInt($(this).val());
                toggleColumnVisibility(column_index, $(this));

                if (hiddenColumnArray.includes(column_index)) {
                    hiddenColumnArray.splice(hiddenColumnArray.indexOf(column_index), 1);
                } else {
                    hiddenColumnArray.push(column_index);
                }

                hiddenColumnArray = [...new Set(hiddenColumnArray)]; // Remove duplicates

                // Update the columnVisibility cache data
                $.ajax({
                    url: '/update-column-visibility', // Replace with your route or endpoint for updating cache data
                    method: 'POST',
                    data: { columnVisibility: hiddenColumnArray },
                    success: function() {
                        console.log('Column visibility updated successfully.');
                    }
                });
            });

            function toggleColumnVisibility(column_index, this_btn) {
                var column = order_table.column(column_index);
                column.visible(!column.visible());

                if (column.visible()) {
                    $(this_btn).addClass('badge-primary').removeClass('badge-warning');
                } else {
                    $(this_btn).removeClass('badge-primary').addClass('badge-warning');
                }
            }
        });
        $(document).on('click', '.delete_order', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_order_transactions_will_be_deleted')",
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
        $('#city_id').on('change', function(){
            var city_id =$(this).val();

            $.get( "{{url('/admin/city/areas')}}", { city_id: city_id })
                .done(function( data ) {
                    if(data.code == 200){
                        var e=data.data;
                        $('#area_id')
                            .find('option')
                            .remove();
                        $.each(e, function (key, val) {
                            $("#area_id").append('<option value="'+val.id+'" selected="">'+val.title+'</option>');
                        });
                        $("#area_id").selectpicker("refresh");
                    }

                });

        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.min.js"></script>
    <script>



        function send_offer() {
            var formData = $("#send_offer_price_form").serialize();
            var url = $("#send_offer_price_form").attr('action');
            console.log(url);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    if(response.success){
                        var socket = io('http://135.181.122.201:3100?type=Provider&id=4');
                        socket.emit('borad',{
                            order_id:response.order_id,
                            price:response.price,
                            type_provider:response.type_provider,
                            image:response.image,
                            name:response.name,
                            number_phone:response.number_phone,
                        });
                        socket.close();
                    }

                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>

@endsection
