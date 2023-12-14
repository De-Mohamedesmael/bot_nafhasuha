@extends('back-end.layouts.app')
@section('title', __('lang.edit_info'))

@section('styles')
    <style>

    </style>
@endsection
@section('content')
    @php
        $config_langs = config('constants.langs');
    @endphp

    <section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_info')</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.infos.update', $info->id), 'id' =>
                        'info-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">
                            @csrf
                            @php($default_lang = 'ar')

                            <ul class="nav nav-tabs mb-4">
                                @foreach ($config_langs as  $localeCode => $lang)
                                    <li class="nav-item">
                                        <a class="nav-link @if($localeCode == 'ar') active @endif "  href="#tab-{{$localeCode}}" data-toggle="tab">
                                            <span class="d-none d-sm-block">{{ $lang['full_name'] .'('.strtoupper($localeCode).')'}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content p-3 text-muted">

                                @foreach ($config_langs as $localeCode => $lang)
                                    @php( $info_t=  $info->translateOrDefault($localeCode))
                                    <div class="tab-pane @if($localeCode == 'ar') active @endif" id="tab-{{$localeCode}}" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="title">{{ \App\CPU\translate('name')}} ({{strtoupper($localeCode)}})</label>
                                                <input type="text" name="{{$localeCode}}[title]" class="form-control" id="title" value="{{old('title',$info_t?$info_t["title"]:null)}}" placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('LUX')}}" {{$localeCode == $default_lang? 'required':''}}>

                                                <div class="form-group pt-2">
                                                    <label class="input-label"
                                                           for="{{$localeCode}}_description">{{\App\CPU\translate('description')}}
                                                        ({{strtoupper($localeCode)}})</label>
                                                    <textarea name="{{$localeCode}}[description]" class="editor textarea" cols="30"
                                                              rows="10" >{!!old('description',$info_t?$info_t["description"]:null) !!}</textarea>
                                                </div>
                                                <label for="video">{{ \App\CPU\translate('video') . ' - embed'}} ({{strtoupper($localeCode)}})</label>
                                                <input type="text" name="{{$localeCode}}[video]" class="form-control" id="video" value="{{old('video',$info_t?$info_t["video"]:null)}}" placeholder="https://www.youtube.com/embed/id-video" >

                                                @if($localeCode == $default_lang)
                                                    <label for="sort">{{ \App\CPU\translate('sort')}} </label>
                                                    <input type="number" name="sort" class="form-control" id="sort" value="{{old('sort',$info->sort)}}"  >

                                                @endif
                                            </div>
                                            <div class="col-md-6 row m-0 p-0">
                                                <div class="card-header col-md-12">
                                                    <h4>{{\App\CPU\translate('seo_section')}}</h4>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="input-label"
                                                           for="{{$localeCode}}_meta_keywords">{{\App\CPU\translate('meta_keywords')}}
                                                        ({{strtoupper($localeCode)}})</label>
                                                    <textarea class="w-100" name="{{$localeCode}}[meta_keywords]"

                                                              rows="5" {{$localeCode == $default_lang? 'required':''}}>{{old('meta_keywords',$info_t?$info_t["meta_keywords"]:null)}}</textarea>
                                                </div>
                                                <div class="form-group  col-md-6" >
                                                    <label class="input-label"
                                                           for="{{$localeCode}}_meta_description">{{\App\CPU\translate('meta_description')}}
                                                        ({{strtoupper($localeCode)}})</label>
                                                    <textarea class="w-100" name="{{$localeCode}}[meta_description]"

                                                              rows="5" {{$localeCode == $default_lang? 'required':''}}>{{old('meta_description',$info_t?$info_t["meta_description"]:null)}}</textarea>
                                                </div>
                                                @php( $img=  $info->translateOrDefault($localeCode)? asset('assets/images/'.$info->translateOrDefault($localeCode) ->img) : null)
                                                <div class="col-md-6 mt-2 mb-3">
                                                    <label for="image">{{\App\CPU\translate('image')}}<span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" id="image" name="{{$localeCode}}[img]">
                                                    @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mt-2 mb-3">
                                                    @if($img)
                                                        <div class="img-old">
                                                            <img src="{{$img}}" >
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$localeCode}}">


                                @endforeach

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" value="{{trans('lang.save')}}" id="submit-btn"
                                        class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>


</section>

@endsection

@section('javascript')
   <script>
        tinymce.init({
            selector: ".editor",
            height: 250,
            plugins: [
                "advlist autolink lists link charmap print preview anchor textcolor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime table contextmenu paste code wordcount",
            ],
            toolbar:
                "insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat",
            branding: false,
        });
        // $("#submit-btn").on("click", function (e) {
        //
        //     e.preventDefault();
        //     setTimeout(() => {
        //         if ($("#info-form").valid()) {
        //             tinyMCE.triggerSave();
        //             $.ajax({
        //                 type: "POST",
        //                 url: $("#info-form").attr("action"),
        //                 data: $("#info-form").serialize(),
        //                 success: function (response) {
        //                     if (response.code == 200) {
        //                         swal("Success", response.msg, "success");
        //                         setTimeout(() => {
        //                             location.reload() ;
        //                         }, 1000);
        //                     }else if(response.code == 405){
        //                         swal("Error", response.error, "error");
        //
        //                     } else {
        //                         swal("Error", response.msg, "error");
        //                     }
        //                 },
        //                 error: function (response) {
        //                     if (!response.success) {
        //                         swal("Error", response.msg, "error");
        //                     }
        //                 },
        //             });
        //         }
        //     });
        // });

    </script>


@endsection
