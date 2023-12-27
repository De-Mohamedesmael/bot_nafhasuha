@extends('back-end.layouts.app')
@section('title', __('lang.general_settings'))
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
    <style>
        button.btn.btn-xs.btn-danger.delete-btn.remove_image {
            display: none;
        }
        .preview-logo-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-header-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview-footer-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }
        .preview {
            position: relative;
            width: 150px;
            height: 150px;
            padding: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin: 30px 0px;
            border: 1px solid #ddd;
        }
        .preview img {
            width: 100%;
            height: 100%;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            object-fit: cover;

        }

        .delete-btn {
            position: absolute;
            top: 156px;
            right: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: transparent;
            color: rgba(235, 32, 38, 0.97);
        }

        .crop-btn {
            position: absolute;
            top: 156px;
            left: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
            background: transparent;
            color: #007bff;
        }
    </style>

    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants>div {
            margin-right: 5px;
        }

        .variants>div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>input[type='file'] {
            display: none
        }

        .file>label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file>label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file>label>i {
            padding-right: 5px;
        }

        .file--upload>label {
            color: hsl(204, 86%, 53%);
            border-color: hsl(204, 86%, 53%);
        }

        .file--upload>label:hover {
            border-color: hsl(204, 86%, 53%);
            background-color: hsl(204, 86%, 96%);
        }

        .file--upload>label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading>label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        .file--uploading>label>i {
            animation: pulse 5s infinite;
        }

        .file--uploading>label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading>label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success>label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success>label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success>label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger>label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger>label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger>label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled>label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }
        .preview {
            position: relative !important;
            width: 250px !important;
            height: auto !important;
        }

        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }
    </style>
@endsection
@section('content')
    <div class="col-md-12  no-print">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4>@lang('lang.general_settings')</h4>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => route('admin.settings.updateGeneralSetting'), 'method' => 'post','id'=>'setting_form', 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('site_title', __('lang.site_title'), []) !!}
                        {!! Form::text('site_title', \Settings::get('site_title','Nafhasuha'), ['class' => 'form-control']) !!}
                    </div>

                    <div class="col-md-3">
                        {!! Form::label('JoiningBonusValue', __('lang.JoiningBonusValue'), []) !!}
                        {!! Form::number('JoiningBonusValue', \Settings::get('JoiningBonusValue') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('InvitationBonusValue', __('lang.InvitationBonusValue'), []) !!}
                        {!! Form::number('InvitationBonusValue', \Settings::get('InvitationBonusValue') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('change_price', __('lang.change_price'), []) !!}
                        {!! Form::number('change_price', \Settings::get('change_price') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('subscription_price', __('lang.subscription_price'), []) !!}
                        {!! Form::number('subscription_price', \Settings::get('subscription_price') , ['class' => 'form-control']) !!}
                    </div>

                    @foreach ($arr_types as $arr_type)
                        <div class="col-md-3">
                            {!! Form::label('percent_'.$arr_type, \App\CPU\translate('percent_'.$arr_type), []) !!}
                            {!! Form::number('percent_'.$arr_type, \Settings::get('percent_'.$arr_type,10) , ['class' => 'form-control']) !!}
                        </div>
                    @endforeach
                    <div class="col-md-3">
                        {!! Form::label('limit_cancel', __('lang.limit_cancel'), []) !!}
                        {!! Form::number('limit_cancel', \Settings::get('limit_cancel') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('max_distance', __('lang.max_distance'), []) !!}
                        {!! Form::number('max_distance', \Settings::get('max_distance') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('IFTrueHome', __('lang.IFTrueHome'), []) !!}
                        {!! Form::select('IFTrueHome', [ '1' => __('lang.yes'),'0' => __('lang.yes')],  \Settings::get('IFTrueHome',1), ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('IFTrueCenter', __('lang.IFTrueCenter'), []) !!}
                        {!! Form::select('IFTrueCenter', [ '1' => __('lang.yes'),'0' => __('lang.yes')],  \Settings::get('IFTrueCenter',1), ['class' => 'form-control selectpicker', 'data-live-search' => 'true']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('update_version_IOS', __('lang.update_version_IOS'), []) !!}
                        {!! Form::text('update_version_IOS', \Settings::get('update_version_IOS','1.0'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('update_version_Android', __('lang.update_version_Android'), []) !!}
                        {!! Form::text('update_version_Android', \Settings::get('update_version_Android','1.0'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('update_version_Provider_IOS', __('lang.update_version_Provider_IOS'), []) !!}
                        {!! Form::text('update_version_Provider_IOS', \Settings::get('update_version_Provider_IOS','1.0'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('update_version_Provider_Android', __('lang.update_version_Provider_Android'), []) !!}
                        {!! Form::text('update_version_Provider_Android', \Settings::get('update_version_Provider_Android','1.0'), ['class' => 'form-control']) !!}
                    </div>


                </div>
                <br>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="projectinput2"> {{ __('lang.logo') }}</label>
                            <div class="container mt-3">
                                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                    <div class="col-12">
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-10 offset-1">
                                                    <div class="variants">
                                                        <div class='file file--upload w-100'>
                                                            <label for='file-input-logo' class="w-100">
                                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                                            </label>
                                                            <!-- <input  id="file-input" multiple type='file' /> -->
                                                            <input type="file" id="file-input-logo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-10 offset-1">
                                        <div class="preview-logo-container">
                                          @if ( \Settings::get('logo'))
                                              <div class="preview">
                                              <img src="{{ asset('assets\images\settings\\'.\Settings::get('logo')) }}"
                                                   id="img_logo_footer" alt="">
                                              <button class="btn btn-xs btn-danger delete-btn remove_image" data-type="logo"><i style="font-size: 25px;"
                                                                                                                                         class="fa fa-trash"></i></button>
                                              </div>
                                          @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <br>
                <br>

                <div class="col-md-12">
                    <button id="submit-btn" class="btn btn-primary">@lang('lang.save')</button>
                </div>
                <div id="cropped_logo_images"></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="logoModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <div id="croppie-logo-modal" style="display:none">
                        <div id="croppie-logo-container"></div>
                        <button data-dismiss="modal" id="croppie-logo-cancel-btn" type="button" class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-logo-submit-btn" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('.selectpicker').selectpicker();
        $(document).ready(function() {
            tinymce.init({
                selector: "#help_page_content",
                height: 130,
                plugins: [
                    "advlist autolink lists link charmap print preview anchor textcolor image",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime table contextmenu paste code wordcount",
                ],
                toolbar: "insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat",
                branding: false,
            });
        });
        $(document).on('click', '.remove_image', function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            console.log(type)
            Swal.fire({
                title: '{{ __("site.Are you sure?") }}',
                text: "{{ __("site.You won't be able to delete!") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/settings/remove-image/" + type,
                        type: "POST",
                        success: function(response) {
                            if (response.success) {

                                    const previewLogoContainer = document.querySelector('.preview-logo-container');
                                    previewLogoContainer.innerHTML = '';
                                Swal.fire(
                                    'Deleted!',
                                    '{{ __("site.Your Image has been deleted.") }}',
                                    'success'
                                );

                            }
                        }
                    });
                }
            });

        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        $("#submit-btn").on("click",function (e){
            e.preventDefault();
            setTimeout(()=>{
                getLogoImages();
                $("#setting_form").submit();
            },1000)
        });
    </script>

    <script>
        const fileLogoInput = document.querySelector('#file-input-logo');
        const previewLogoContainer = document.querySelector('.preview-logo-container');
        const croppieLogoModal = document.querySelector('#croppie-logo-modal');
        const croppieLogoContainer = document.querySelector('#croppie-logo-container');
        const croppieLogoCancelBtn = document.querySelector('#croppie-logo-cancel-btn');
        const croppieLogoSubmitBtn = document.querySelector('#croppie-logo-submit-btn');
        // let currentFiles = [];
        fileLogoInput.addEventListener('change', () => {
            previewLogoContainer.innerHTML = '';
            let files = Array.from(fileLogoInput.files)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg","JPG","jpeg","JPEG","png","PNG","BMP","bmp"];
                // if (file.type.match('image.*')) {
                if (FileAccept.includes(fileType)) {
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
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
                        deleteBtn.addEventListener('click', () => {
                            Swal.fire({
                                title: '{{ __("site.Are you sure?") }}',
                                text: "{{ __("site.You won't be able to delete!") }}",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire(
                                        'Deleted!',
                                        '{{ __("site.Your Image has been deleted.") }}',
                                        'success'
                                    )
                                    files.splice(file, 1)
                                    preview.remove();
                                    getLogoImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#logoModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchLogoCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewLogoContainer.appendChild(preview);
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

            getLogoImages()
        });
        function launchLogoCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 177,
                    height: 52,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 177,
                    height: 52,
                },
                enableOrientation: true
            };

            // Create a new Croppie instance with the selected image and options
            const croppie = new Croppie(croppieLogoContainer, croppieOptions);
            croppie.bind({
                url: img.src,
                orientation: 1,
            });

            // Show the Croppie modal
            croppieLogoModal.style.display = 'block';

            // When the user clicks the "Cancel" button, hide the modal
            croppieLogoCancelBtn.addEventListener('click', () => {
                croppieLogoModal.style.display = 'none';
                $('#logoModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieLogoSubmitBtn.addEventListener('click', () => {
                croppie.result('base64').then((croppedImg) => {
                    img.src = croppedImg;
                    croppieLogoModal.style.display = 'none';
                    $('#logoModal').modal('hide');
                    croppie.destroy();
                    getLogoImages()
                });
            });
        }
        function getLogoImages() {
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-logo-container');
                let images = [];
                $("#cropped_logo_images").empty();
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "logo").val(container[0].children[i].children[0].src);
                    $("#cropped_logo_images").append(newInput);
                }
                return images
            }, 300);
        }

    </script>
@endsection
