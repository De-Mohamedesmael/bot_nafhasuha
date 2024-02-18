@extends('back-end.layouts.app')
@section('title', __('lang.employee'))
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
            /*display: block;*/
            width: 55px;
            height: 24px;
            background-color: #7a0d0d;
            cursor: pointer;
            border-radius: 20px;
            overflow: hidden;
            transition: ease-in 0.5s;
        }

        input:checked[type="checkbox"] ~ .check {
            background-color: #013e6b;
            /*   box-shadow: 0 0 0 1200px #092c3e; */
        }

        .check:before {
            content: '';
            position: absolute;
            top: 3px;
            left: 4px;
            background-color: #eff2f7;
            width: 18px;
            height: 18px;
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
            width: 18px;
            height: 18px;
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
        a.btn.edit_employee.a-image {
            margin: 0 5px;
        }
    </style>
@endsection
@section('sli_li')
    <span class="parent"> < {{__('lang.employees')}} / </span>    @lang('lang.all_employees')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center" style="justify-content: center">
            <div class="print-title">
                @lang('lang.all_employees')

            </div>
            <div  class="dev-create">

                @can('admin_module.admins.create')
                    <a  href="{{ route('admin.admins.create') }}" class="btn btn-create"><i
                            class="dripicons-plus"></i>
                        @lang('lang.add_new_employee')
                    </a>
                @endcan
            </div>

        </div>



    </div>

    <div class="table-responsive">
        <table class="table" id="employee_table">
            <thead>
            <tr>
                <th>@lang('lang.profile_photo')</th>
                <th>@lang('lang.employee_name')</th>
                <th>@lang('lang.email')</th>
                <th>@lang('lang.mobile')</th>
                @can('admin_module.admins.edit')
                    <th>@lang('lang.status')</th>
                @endcan
                <th>@lang('lang.created_at')</th>
                <th class="notexport">@lang('lang.action')</th>
            </tr>
            </thead>

            <tbody>



            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
@endsection

@section('javascript')
    <script>
        employee_sales_table = $("#employee_table").DataTable({
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
                url: '{{route("admin.admins.index")}}',
                data: function (d) {
                    d.employee_id = $("#employee_id").val();
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
                { data: "profile_photo", name: "profile_photo" },
                { data: "name", name: "name" },
                { data: "email", name: "email" },
                { data: "phone", name: "phone" },
                    @can('admin_module.admins.edit')
                        { data: "status", name: "status" },
                    @endcan
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

        $(document).on('click', '.delete_employee', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "@lang('lang.all_employee_transactions_will_be_deleted')",
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
        $(document).on('click', '.update_status', function(e) {
            var id=$(this).data('id');
            $.ajax({
                method: 'Post',
                url: "{{route('admin.admins.update_status')}}",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                success: function(result) {
                    if (result.success ==
                        true) {
                        swal(
                            'Success',
                            result.msg,
                            'success'
                        );
                    } else {
                        swal(
                            'Error',
                            result.msg,
                            'error'
                        );
                    }
                },
            });
        });
    </script>
@endsection
