@extends('back-end.layouts.app')
@section('title', __('lang.slider'))
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>@lang('lang.edit_slider')</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                        {!! Form::open(['url' => route('admin.slider.update', $slider->id), 'id' =>
                        'slider-form',
                        'method' =>
                        'PUT', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                        <div class="row">


                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('type', __('lang.type') . ':*') !!}
                                    {!! Form::select('type', ['OutSite'=>__('lang.OutSite'),'Service'=>__('lang.Service'),'Info'=>__('lang.Info'),], $slider->type, [
                            'class' => 'selectpicker
                                    form-control',
                            'data-live-search' => 'true',
                            'required',
                        ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4 @if($slider->type != 'Service' ) hide @endif div_type_id"id="div_Service">
                                <div class="form-group">
                                    {!! Form::label('service_id', __('lang.service') . ':*') !!}
                                    {!! Form::select('service_id', $categories, $slider->type_id, [
                            'class' => 'selectpicker
                                    form-control input_Service',
                            'data-live-search' => 'true',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4  @if($slider->type != 'Info' ) hide @endif div_type_id" id="div_Info">
                                <div class="form-group">
                                    {!! Form::label('info_id', __('lang.page') . ':*') !!}
                                    {!! Form::select('info_id', $infos, $type_id, [
                            'class' => 'selectpicker
                                    form-control input_Info',
                            'data-live-search' => 'true',
                            'placeholder' => __('lang.please_select'),
                        ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4 div_type_id  @if($slider->type != 'OutSite' ) hide @endif"id="div_OutSite">
                                <div class="form-group">
                                    {!! Form::label('url', __('lang.url') . ':*') !!}
                                    {!! Form::url('url', ($slider->type == 'OutSite' ? $slider->type_id :false), ['class' => 'form-control input_OutSite', 'required','placeholder' => __('lang.url')]) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('sort', __('lang.sort') . ':') !!}
                                    {!! Form::number('sort', $slider->sort, ['class' => 'form-control','required', 'placeholder' => __('lang.sort')]) !!}
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('start_at', __('lang.start_at'). ':', []) !!} <br>
                                        {!! Form::text('start_at',$slider->start_at!= null? @format_date($slider->start_at):false, ['class' => 'form-control datepicker',
                                         'required',
                                        'placeholder' => __('lang.payment_date')]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('end_at', __('lang.end_at'). ':', []) !!} <br>
                                        {!! Form::text('end_at',$slider->end_at!= null? @format_date($slider->end_at):false, ['class' => 'form-control datepicker',

                                        'placeholder' => __('lang.end_at')]) !!}
                                    </div>
                                </div>


                            </div>


                            <div class="col-md-4 " style="margin-top: 10px;">

                                <div class="container mt-3">
                                    <div class="red">(655px×320px)</div>
                                    <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                        <div class="col-12">
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-10 offset-1">
                                                        <div class="variants">
                                                            <div class='file file-upload w-100'>
                                                                <label for='file-product-edit-product' class="w-100">
                                                                    <i class="fas fa-cloud-upload-alt"></i>Upload
                                                                </label>
                                                                <!-- <input  id="file-input" multiple type='file' /> -->
                                                                <input type="file" id="file-product-edit-product">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-10 offset-1">

                                            <div class="preview-edit-container">
                                                @if(!empty($slider->getFirstMediaUrl('images')))
                                                    <div id="preview{{ $slider->id }}" class="preview">
                                                        <input type="hidden"  name="have_image"  value="1" id="have-image">
                                                        <img
                                                            src="{{  $slider->getFirstMediaUrl('images')  }}"
                                                            id="img{{  $slider->id }}" alt="">
                                                        <div class="action_div"></div>
                                                        <button type="button"
                                                                class="delete-btn"><i
                                                                style="font-size: 20px;"
                                                                data-href="{{ route('admin.slider.deleteImage', $slider->id) }}"
                                                                id="deleteBtn{{ $slider->id }}"
                                                                class="fas fa-trash"></i>
                                                        </button>

                                                    </div>
                                                @else
                                                    <input type="hidden" name="have_image" value="0" id="have-image">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


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
                        <div id="cropped_images"></div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal gift_card_modal no-print" role="dialog" aria-hidden="true"></div>

</section>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="croppie-modal" style="display:none">
                    <div id="croppie-container"></div>
                    <button data-dismiss="modal" id="croppie-cancel-btn" type="button" class="btn btn-secondary"><i
                            class="fas fa-times"></i></button>
                    <button id="croppie-submit-btn" type="button" class="btn btn-primary"><i
                            class="fas fa-crop"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script>
        $('#type').on('change', function(){
            var type =$(this).val();

            $('.div_type_id').hide();
            $('.div_type_id').removeClass('hide');
            $('#div_'+type).show();
            $('.input_OutSite').attr('required',false);
            $('.input_Info').attr('required',false);
            $('.input_Service').attr('required',false);
            $('.input_'+type).attr('required',true);

        });
        $("#submit-btn").on("click", function (e) {
            getEditProductImages()
            e.preventDefault();
            setTimeout(() => {
                if ($("#slider-form").valid()) {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: "POST",
                        url: $("#slider-form").attr("action"),
                        data: $("#slider-form").serialize(),
                        success: function (response) {
                            if (response.success) {
                                swal("Success", response.msg, "success");
                                setTimeout(() => {
                                    window.close();
                                }, 1000);
                            }
                        },
                        error: function (response) {
                            if (!response.success) {
                                swal("Error", response.msg, "error");
                            }
                        },
                    });
                }
            });
        });
        @if($slider)
        {{--document.getElementById("cropBtn{{ $slider->id }}").addEventListener('click', () => {--}}
        {{--    setTimeout(() => {--}}
        {{--        launchEditProductCropTool(document.getElementById("img{{ $slider->id }}"));--}}
        {{--    }, 500);--}}
        {{--});--}}
        document.getElementById("deleteBtn{{ $slider->id }}").addEventListener('click', () => {
            Swal.fire({
                title: '{{ __("Are you sure?") }}',
                text: "{{ __("You will not be able to delete!") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        '{{ __("Your Image has been deleted.") }}',
                        'success'
                    )
                    $("#preview{{ $slider->id }}").remove();
                }
            });
        });

        @endif
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        var fileEditProductInput = document.querySelector('#file-product-edit-product');
        var previewEditProductContainer = document.querySelector('.preview-edit-container');
        var croppieEditProductModal = document.querySelector('#croppie-modal');
        var croppieEditProductContainer = document.querySelector('#croppie-container');
        var croppieEditProductCancelBtn = document.querySelector('#croppie-cancel-btn');
        var croppieEditProductSubmitBtn = document.querySelector('#croppie-submit-btn');

        // let currentFiles = [];
        fileEditProductInput.addEventListener('change', () => {
            // let files = fileEditProductInput.files;
            previewEditProductContainer.innerHTML = '';
            let files = Array.from(fileEditProductInput.files)
            // files.concat(currentFiles)
            // currentFiles.push(...files)
            // currentFiles && (files = currentFiles)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        img.src = reader.result;
                        preview.appendChild(img);
                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('span');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', () => {
                            swal({
                                title: "Delete",
                                text: "Are you sure you want to delete this image ?",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                                buttons: ["Cancel", "Delete"],
                            }).then((addPO) => {
                                if (addPO) {
                                    files.splice(file, 1)

                                    preview.remove();
                                    getEditProductImages()
                                }
                                $('#have-image').val(0);
                            });
                        });

                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#exampleModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchEditProductCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewEditProductContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }

            getEditProductImages()
        });
        function launchEditProductCropTool(img) {
            getEditProductImages();
            // Set up Croppie options

            const croppieOptions = {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 300,
                    height: 300,
                },
                enableOrientation: true
            };
            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieEditProductContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal

            croppieEditProductModal.style.display = 'block';
            // When the user clicks the "Cancel" button, hide the modal
            croppieEditProductCancelBtn.addEventListener('click', () => {
                croppieEditProductModal.style.display = 'none';
                $('#exampleModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieEditProductSubmitBtn.addEventListener('click', () => {
                croppie.result({
                    type: 'canvas',
                    size: {
                        width: 800,
                        height: 600
                    },
                    quality: 1 // Set quality to 1 for maximum quality
                }).then((croppedImg) => {
                    img.src = croppedImg;
                    croppieEditProductModal.style.display = 'none';
                    $('#exampleModal').modal('hide');
                    croppie.destroy();
                    getEditProductImages()
                });
            });
        }
        function getEditProductImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-edit-container');
                let images = [];
                $("#cropped_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                    $("#cropped_images").append(newInput);
                    images.push(container[0].children[i].children[0].src)
                }
                return images
            }, 300);
        }


    </script>

@endsection
