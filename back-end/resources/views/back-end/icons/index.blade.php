@extends('back-end.layouts.app')
@section('title', __('lang.icons'))
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
</style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center">
            <h3 class="print-title">@lang('lang.all_icons')</h3>
        </div>
        <a style="color: white" href="{{ route('admin.icons.create') }}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.add_icon')</a>

    </div>
    <div class="table-responsive">
        <table id="icon_table" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('lang.photo')</th>
                    <th>@lang('lang.title')</th>
                    <th>@lang('lang.link')</th>
                    <th>@lang('lang.joining_date')</th>
                    <th class="notexport">@lang('lang.action')</th>
                </tr>
            </thead>
            <tbody>

            </tbody>

        </table>
    </div>
@endsection

@section('javascript')
    <script>
        icon_sales_table = $("#icon_table").DataTable({
            lengthChange: true,
            paging: true,
            info: false,
            bAutoWidth: false,
            language: {
                search: "",
                entries: "{{\App\CPU\translate('entries')}}",
                Show: "{{\App\CPU\translate('entries')}}",
                searchPlaceholder:"{{\App\CPU\translate('Look for...')}}",

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
                url: '{{route("admin.icons.index")}}',
                data: function (d) {
                    d.icon_id = $("#icon_id").val();
                },
            },
            columnDefs: [
                {
                    targets: [5],
                    orderable: false,
                    searchable: false,
                },
            ],
            columns: [
                { data: "id", name: "id" },
                { data: "image", name: "image" },
                { data: "title", name: "title" },
                { data: "link", name: "link" },
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

        $(document).on('click', '.delete_icon', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_icon_transactions_will_be_deleted')",
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

    </script>
@endsection
