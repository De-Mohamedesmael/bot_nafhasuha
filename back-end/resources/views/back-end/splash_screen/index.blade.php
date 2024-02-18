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
        a.btn.a-image {
            margin: 0 10px;
        }
    </style>
@endsection
@section('sli_li')
    <span class="parent"> < {{__('lang.splash_screens')}} / </span>    @lang('lang.all_splash_screens')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card-header d-flex align-items-center" style="justify-content: center">
            <div class="print-title">
                @lang('lang.all_splash_screens')

            </div>
            <div  class="dev-create">


                <a  href="{{ route('admin.splash_screen.create') }}" class="btn btn-create"><i
                        class="dripicons-plus"></i>
                    @lang('lang.add_splash_screen')
                </a>
            </div>

        </div>



    </div>

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

                        <a data-href="{{route('admin.splash_screen.edit', $splash_screen->id)}}"
                           data-container=".view_modal" class="btn btn-modal a-image" title="@lang('lang.edit')">
                            <img class="icon-action" src="{{asset('assets/back-end/images/design/edit.svg')}}">

                        </a>

                        <a data-href="{{route('admin.splash_screen.delete', $splash_screen->id)}}"
                           data-check_password="{{route('admin.checkPassword', Auth::id()) }}"
                           class="btn text-red delete_item" title="{{ __('lang.delete') }}"><i class="dripicons-trash"></i>
                        </a>

                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
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
