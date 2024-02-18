@extends('back-end.layouts.app')
@section('title', __('lang.employee'))
@section('styles')
    <style>
        div#content {
            padding-top: 0;
        }

        .sp-label.new-des.back-e9 {
            background: linear-gradient( to top, #e9ecef 0%, #e9ecef 50%, #ffffff00 50%, #ffffff00 100% ) !important;
        }
        .sp-label.new-des {
            background: linear-gradient( to top, #fefefe00 0%, #fdfdff 50%, #ffffff00 50%, #ffffff00 100% ) !important;
            top: -10px !important;
        }
    </style>
@endsection
@section('sli_li')
    <span class="parent"> <  <a href="{{route("admin.admins.index")}}"> {{__('lang.employees')}} </a> / </span>  @lang('lang.add_new_employee')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="print-title">@lang('lang.add_new_employee')</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-group" id="new_employee_form"
                                    action="{{ route('admin.admins.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="sp-label new-des" for="fname">@lang('lang.name'):*</label>
                                            <input type="text" class="form-control" name="name" id="name" required
                                                placeholder="Name">
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="sp-label new-des" for="email">@lang('lang.email'):*
                                                <small>(@lang('lang.it_will_be_used_for_login'))</small></label>
                                            <input type="email" class="form-control" name="email" id="email" required
                                                placeholder="Email">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="sp-label new-des" for="email">@lang('lang.phone'):</label>
                                            <input type="phone" class="form-control" name="phone" id="phone"
                                                   placeholder="phone">
                                        </div>
                                    </div>
                                    <div class="row  mt-4">

                                        <div class="col-md-4">
                                            <label class="sp-label new-des" for="photo">@lang('lang.profile_photo')</label>
                                            <input type="file" name="photo" id="photo" class="form-control" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="sp-label new-des" for="password">@lang('lang.password'):*</label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                   required placeholder="Create New Password">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="sp-label new-des" for="pass">@lang('lang.confirm_password'):*</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   name="password_confirmation" required placeholder="Conform Password">
                                        </div>
                                    </div>









                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h3>@lang('lang.user_rights')</h3>
                                        </div>
                                        <div class="col-md-12">
                                            @include('back-end.admin.partial.permission')
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <div class="form-group justify-cont">
                                                <input type="submit" id="submit-btn" class="btn btn-primary"
                                                    value="@lang('lang.save')" name="submit">
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>





        $('.checked_all').change(function() {
            tr = $(this).closest('tr');
            var checked_all = $(this).prop('checked');

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
        })
        $(document).on('change', '.view_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_view').prop('checked', true);
            } else {
                $('.check_box_view').prop('checked', false);
            }
        });
        $(document).on('change', '.edit_check_all', function() {
            if ($(this).prop('checked')) {
                $('.check_box_edit').prop('checked', true);
            } else {
                $('.check_box_edit').prop('checked', false);
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

        $(document).on('click', '#submit-btn, #send-btn', function(e) {
            jQuery('#new_employee_form').validate({
                rules: {
                    password: {
                        minlength: 6
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: "#password"
                    }
                }
            });
            if ($('#new_employee_form').valid()) {
                $('form#new_employee_form').submit();
            }
        });

        $(document).on('focusout', '.check_in', function() {
            $('.check_in').val($(this).val())
        })
        $(document).on('focusout', '.check_out', function() {
            $('.check_out').val($(this).val())
        })
    </script>
@endsection
