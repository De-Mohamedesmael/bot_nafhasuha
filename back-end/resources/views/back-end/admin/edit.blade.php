@extends('back-end.layouts.app')
@section('title', __('lang.employee'))

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_employee')</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['url' => route('admin.admins.update', $admin->id), 'method' => 'put', 'id' => 'edit_employee_form', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">

                            <div class="col-sm-4">
                                <label for="fname">@lang('lang.name'):*</label>
                                <input type="text" class="form-control" name="name" value="{{ $admin->name }}" @if($admin->name == 'Admin') readonly @endif
                                    id="name" required placeholder="Name">
                            </div>
                            <div class="col-sm-4">
                                <label for="email">@lang('lang.email'):*<small>(@lang('lang.it_will_be_used_for_login'))</small></label>
                                <input type="email" class="form-control" name="email" value="{{ $admin->email }}"
                                       id="email" required placeholder="Email">
                            </div>
                            <div class="col-sm-4">
                                <label for="phone">@lang('lang.phone')</label>
                                <input type="phone" class="form-control" name="phone" value="{{ $admin->phone }}"
                                    id="email" required placeholder="Phone">
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label for="photo">@lang('lang.profile_photo')</label>
                                <input type="file" name="photo" id="photo" class="form-control" />
                            </div>
                            <div class="col-sm-4">
                                <label for="password">@lang('lang.password')</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Create New Password">
                            </div>
                            <div class="col-sm-4">
                                <label for="pass">@lang('lang.confirm_password')</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Conform Password">
                            </div>

                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12 text-center">
                                <h3>@lang('lang.user_rights')</h3>
                            </div>
                            <div class="col-md-12">
                                @include('back-end.admin.partial.permission')
                            </div>
                        </div>

                        <div class="row mt-4">

                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">@lang('lang.update_employee')</button>
                            </div>

                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('javascript')
    <script>
        $('#date_of_start_working').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });
        $('#date_of_birth').datepicker({
            language: '{{ session('language') }}',
        });


        $('#fixed_wage').change(function() {
            console.log($(this).prop('checked'));
        })

        $('.checked_all').change(function() {
            tr = $(this).closest('tr');
            var checked_all = $(this).prop('checked');
            console.log(checked_all);

            tr.find('.check_box').each(function(item) {
                if (checked_all === true) {
                    $(this).prop('checked', true)
                } else {
                    $(this).prop('checked', false)
                }
            })
        })
        $('.all_module_check_all').change(function() {
            var all_module_check_all = $(this).prop('checked');
            $('#permission_table > tbody > tr').each((i, tr) => {
                $(tr).find('.check_box').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.module_check_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.checked_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })

            })
        })
        $('.module_check_all').change(function() {
            let moudle_id = $(this).closest('tr').data('moudle');
            if ($(this).prop('checked')) {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', true);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', true);
            } else {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', false);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', false);
            }
        });
        $(document).on('change', '.view_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_view').prop('checked', true);
            } else {
                $('.check_box_view').prop('checked', false);
            }
        });
        $(document).on('change', '.create_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_create').prop('checked', true);
            } else {
                $('.check_box_create').prop('checked', false);
            }
        });
        $(document).on('change', '.delete_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_delete').prop('checked', true);
            } else {
                $('.check_box_delete').prop('checked', false);
            }
        });

        $(document).on('click', '#submit-btn', function(e) {
            jQuery('#edit_employee_form').validate({
                rules: {
                    password: {
                        minlength: function() {
                            return $('#password').val().length > 0 ? 6 : 0;
                        }
                    },
                    password_confirmation: {
                        minlength: function() {
                            return $('#password').val().length > 0 ? 6 : 0;
                        },
                        equalTo: {
                            depends: function() {
                                return $('#password').val().length > 0;
                            },
                            param: "#password"
                        }
                    }
                }
            });
            if ($('#edit_employee_form').valid()) {
                $('form#edit_employee_form').submit();
            }
        })
        $(document).on('focusout', '.check_in', function() {
            $('.check_in').val($(this).val())
        })
        $(document).on('focusout', '.check_out', function() {
            $('.check_out').val($(this).val())
        })
        $(document).on('click', '.salary_cancel', function() {
            $('.salary_fields').val('');
            $('.salary_select').val('');
            $('.salary_select').selectpicker('refresh');
            $('.salary_checkbox').prop('checked', false);

        })
    </script>
@endsection
