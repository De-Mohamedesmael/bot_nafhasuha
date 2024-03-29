@extends('back-end.layouts.app')
@section('title', __('lang.add_icon'))
@section('styles')
    <style>
        div#content {
            padding-top: 0;
        }
        .sp-label.new-des {
            top: -1px !important;
        }
        .sp-label.new-des.back-e9 {
            background: linear-gradient( to top, #e9ecef 0%, #e9ecef 50%, #ffffff00 50%, #ffffff00 100% ) !important;
        }

    </style>
@endsection
@section('sli_li')
    <span class="parent"> <  <a href="{{route("admin.icons.index")}}"> {{__('lang.icons')}} </a> / </span>  @lang('lang.add_icon')
@endsection
@section('content')
    <section class="forms p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>@lang('lang.add_icon')</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>@lang('lang.required_fields_info')</small></p>
                            {!! Form::open(['url' => route('admin.icons.store'), 'id' => 'icons-form', 'method' => 'POST', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                            @include('back-end.icons.partial.create_form')


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group justify-cont">
                                        <input type="submit" value="{{ trans('lang.save') }}" id="submit-btn"
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
    <!-- Modal -->

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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    {{-- <script  src="{{ asset('js/sweetalert2.min.js') }}"></script> --}}

    <script>
        const fileInput = document.querySelector('#file-input');
        const previewContainer = document.querySelector('.preview-container');
        const croppieModal = document.querySelector('#croppie-modal');
        const croppieContainer = document.querySelector('#croppie-container');
        const croppieCancelBtn = document.querySelector('#croppie-cancel-btn');
        const croppieSubmitBtn = document.querySelector('#croppie-submit-btn');

        // let currentFiles = [];
        fileInput.addEventListener('change', () => {
            // let files = fileInput.files;
            previewContainer.innerHTML = '';
            let files = Array.from(fileInput.files)
            // files.concat(currentFiles)
            // currentFiles.push(...files)
            // currentFiles && (files = currentFiles)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg","JPG","jpeg","JPEG","png","PNG","BMP","bmp"];
                if (FileAccept.includes(fileType))  {
                    const reader = new FileReader();
                    reader.addEventListener('load', (e) => {
                        // e.preventDefault();
                        const preview = document.createElement('div');
                        preview.classList.add('preview');
                        const img = document.createElement('img');
                        const actions = document.createElement('div');
                        actions.classList.add('action_div');
                        img.src = reader.result;
                        preview.appendChild(img);
                        preview.appendChild(actions);

                        const container = document.createElement('div');
                        const deleteBtn = document.createElement('span');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                        deleteBtn.addEventListener('click', (e) => {
                            swal({
                                title: '{{ __("Are you sure?") }}',
                                text: "{{ __("You will not be able to delete!") }}",
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                                buttons: ["Cancel", "Yes, delete it!"],
                            }).then((result) => {
                                console.log(result);
                                if (result) {
                                    swal(
                                        'Deleted!',
                                        '{{ __("site.Your Image has been deleted.") }}',
                                        'success'
                                    )
                                    files.splice(file, 1)
                                    preview.remove();
                                    getImages()
                                }
                            });
                        });

                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#exampleModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        // cropBtn.innerHTML = '';
                        cropBtn.addEventListener('click', (e) => {
                            // e.preventDefault();
                            setTimeout(() => {
                                launchCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                }else{
                    swal({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }

            getImages()
        });
        function launchCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 100,
                    height: 100,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 100,
                    height: 100,
                },
                enableOrientation: true
            };

            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieCancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                croppieModal.style.display = 'none';
                $('#exampleModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieSubmitBtn.addEventListener('click', (e) => {
                // e.preventDefault();
                croppie.result('base64').then((croppedImg) => {
                    img.src = croppedImg;
                    croppieModal.style.display = 'none';
                    $('#exampleModal').modal('hide');
                    croppie.destroy();
                    getImages()
                });
            });
        }

        function getImages() {
            console.log("tees");
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-container');
                let images = [];
                $("#cropped_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                    $("#cropped_images").append(newInput);
                }
                console.log(images);
                return images
            }, 300);
        }

    </script>




    <script type="text/javascript">



        $(document).ready(function () {

        });

        $('.datepicker').datepicker({
            language: '{{ session('language') }}',
            todayHighlight: true,
        });

        $("#submit-btn").on("click", function (e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            if ($("#icons-form").valid()) {
                tinyMCE.triggerSave();
                document.getElementById("loader").style.display = "block";
                document.getElementById("content").style.display = "none";
                $.ajax({
                    type: "POST",
                    url: $("form#icons-form").attr("action"),
                    data: $("#icons-form").serialize(),
                    success: function (response) {
                        myFunction();
                        if (response.code == 200) {
                            swal("Success", response.msg, "success");
                            $(".translations").val("").change();
                           $("input:not([type='submit'])").val("").change();
                            const previewContainer = document.querySelector('.preview-container');
                            previewContainer.innerHTML = '';
                        }else if(response.code == 405){
                            swal("Error", response.error, "error");

                        } else {
                            swal("Error", response.msg, "error");
                        }
                    },
                    error: function (response) {
                        myFunction();
                        if (!response.success) {
                            swal("Error", response.msg, "error");
                        }
                    },
                });
            }
        }
    </script>
@endsection
