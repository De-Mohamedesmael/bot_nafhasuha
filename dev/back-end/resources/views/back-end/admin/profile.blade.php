@extends('back-end.layouts.app')
@section('title', __('lang.profile'))

@section('content')
    <div class="row p-3">
        {!! Form::open(['url' => route('admin.updateProfile'), 'method' => 'put', 'enctype' =>
        'multipart/form-data']) !!}
        <div class="row">
            <div class="col-md-6  no-print">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.profile')</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('email', __('lang.email'), []) !!}
                                        {!! Form::text('email', $admin->email, ['class' =>
                                        'form-control', 'readonly'])
                                        !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('phone', __('lang.phone'), []) !!}
                                        {!! Form::text('phone', $admin->phone, ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6  no-print">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.change_password')</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('current_password', __('lang.current_password'), []) !!}
                                        {!! Form::password('current_password', ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('password', __('lang.new_password'), []) !!}
                                        {!! Form::password('password', ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('password_confirmation', __('lang.confirm_password'), []) !!}
                                        {!! Form::password('password_confirmation', ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">@lang('lang.update')</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('javascript')
<script>
    $('.selectpicker').selectpicker()
</script>
@endsection
