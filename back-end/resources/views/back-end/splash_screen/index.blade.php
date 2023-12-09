@extends('back-end.layouts.app')
@section('title', __('lang.splash_screen'))
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


    </div>
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center">
            <h3 class="print-title">@lang('lang.splash_screens')</h3>
        </div>
        <a style="color: white" href="{{ route('admin.splash_screen.create') }}" class="btn btn-info"><i
                class="dripicons-plus"></i>
            @lang('lang.add_splash_screen')</a>
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="splash_screen_table" class="table dataTable">
                            <thead>
                            <tr>
                                <th>@lang('lang.image')</th>
                                <th>@lang('lang.name')</th>
                                <th>@lang('lang.description')</th>
                                <th>@lang('lang.status')</th>
                                <th class="notexport">@lang('lang.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($splash_screens as $splash_screen)
                                <tr>
                                    <td><img src="@if($splash_screen->getFirstMedia('images')){{$splash_screen->getFirstMedia('images')->getUrl()}}@else{{asset('/uploads/'.session('logo'))}}@endif"
                                             alt="photo" width="50" height="50">
                                    </td>
                                    <td>{{$splash_screen->title}}</td>
                                    <td>
                                        {!! $splash_screen->description !!}
                                    </td>
                                    <td>
                                        <label>
                                            <input class="update_status check" type="checkbox" id="switch{{$splash_screen->id}}" data-id="{{$splash_screen->id}}" switch="bool" {{$splash_screen->is_active?'checked':''}} />
                                            <label for="switch{{$splash_screen->id}}" data-on-label="{{__('translation.active')}}" ></label>
                                            <span class="check"></span>
                                        </label>
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">@lang('lang.action')
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default"
                                                user="menu">
                                                {{--                                            @can('product_module.splash_screen.edit')--}}
                                                <li>

                                                    <a data-href="{{route('admin.splash_screen.edit', $splash_screen->id)}}?type=splash_screen"
                                                       data-container=".view_modal" class="btn btn-modal"><i
                                                            class="dripicons-document-edit"></i> @lang('lang.edit')</a>
                                                </li>
                                                <li class="divider"></li>
                                                {{--                                            @endcan--}}
                                                {{--                                            @can('product_module.splash_screen.delete')--}}

                                                <li>
                                                    <a data-href="{{route('admin.splash_screen.delete', $splash_screen->id)}}"
                                                       data-check_password="{{route('admin.checkPassword', Auth::id()) }}"
                                                       class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                                        {{ __('lang.delete') }}</a>
                                                </li>
                                                {{--                                            @endcan--}}

                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).on('click', '.update_status', function(e) {
            var id=$(this).data('id');
            $.ajax({
                method: 'Post',
                url: "{{route('admin.splash_screen.update_status')}}",
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
