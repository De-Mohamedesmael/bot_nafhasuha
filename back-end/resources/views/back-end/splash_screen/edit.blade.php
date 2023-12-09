<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
    .preview-images-container {
        /* display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px; */
        display: grid;
        grid-template-columns: repeat(auto-fill, 170px);
    }

    .preview-certificates-container {
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

    .variants > div {
        margin-right: 5px;
    }

    .variants > div:last-of-type {
        margin-right: 0;
    }

    .file {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .file > input[type='file'] {
        display: none
    }

    .file > label {
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

    .file > label:hover {
        border-color: hsl(0, 0%, 21%);
    }

    .file > label:active {
        background-color: hsl(0, 0%, 96%);
    }

    .file > label > i {
        padding-right: 5px;
    }

    .file--upload > label {
        color: hsl(204, 86%, 53%);
        border-color: hsl(204, 86%, 53%);
    }

    .file--upload > label:hover {
        border-color: hsl(204, 86%, 53%);
        background-color: hsl(204, 86%, 96%);
    }

    .file--upload > label:active {
        background-color: hsl(204, 86%, 91%);
    }

    .file--uploading > label {
        color: hsl(48, 100%, 67%);
        border-color: hsl(48, 100%, 67%);
    }

    .file--uploading > label > i {
        animation: pulse 5s infinite;
    }

    .file--uploading > label:hover {
        border-color: hsl(48, 100%, 67%);
        background-color: hsl(48, 100%, 96%);
    }

    .file--uploading > label:active {
        background-color: hsl(48, 100%, 91%);
    }

    .file--success > label {
        color: hsl(141, 71%, 48%);
        border-color: hsl(141, 71%, 48%);
    }

    .file--success > label:hover {
        border-color: hsl(141, 71%, 48%);
        background-color: hsl(141, 71%, 96%);
    }

    .file--success > label:active {
        background-color: hsl(141, 71%, 91%);
    }

    .file--danger > label {
        color: hsl(348, 100%, 61%);
        border-color: hsl(348, 100%, 61%);
    }

    .file--danger > label:hover {
        border-color: hsl(348, 100%, 61%);
        background-color: hsl(348, 100%, 96%);
    }

    .file--danger > label:active {
        background-color: hsl(348, 100%, 91%);
    }

    .file--disabled {
        cursor: not-allowed;
    }

    .file--disabled > label {
        border-color: #e6e7ef;
        color: #e6e7ef;
        pointer-events: none;
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

<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => route('admin.splash_screen.update', $splash_screen->id), 'method' => 'put', 'id' => 'splash_screen_add_form', 'files' => true]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.edit_splash_screen' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('name', __('lang.name') . ':*') !!}
                    <div class="input-group my-group">
                        {!! Form::text('name', $splash_screen->title, ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                        <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="splash_screen"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
                    </div>
                </div>
                @include('back-end.layouts.partials.translation_inputs', [
                    'attribute' => 'title',
                    'translations' => $splash_screen->getTranslationsArray('title'),
                    'type' => 'splash_screen',
                ])
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('description', __('lang.description') . ':*') !!}
                    <div class="input-group my-group">
                        {!! Form::text('description', $splash_screen->title, ['class' => 'form-control', 'placeholder' => __('lang.description'), 'required']) !!}
                        <span class="input-group-btn">
                                <button class="btn btn-default bg-white btn-flat translation_btn" type="button"
                                        data-type="splash_screen_description"><i
                                        class="dripicons-web text-primary fa-lg"></i></button>
                            </span>
                    </div>
                </div>
                @include('back-end.layouts.partials.translation_inputs', [
                    'attribute' => 'description',
                    'translations' => $splash_screen->getTranslationsArray('description'),
                    'type' => 'splash_screen_description',
                ])
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('sort', __('lang.sort') . ':*') !!}
                    <div class="input-group my-group">
                        {!! Form::number('sort', $splash_screen->sort, ['class' => 'form-control', 'placeholder' => __('lang.sort'), 'required']) !!}

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="projectinput2">{{ __('lang.image') }}</label>
                    {{--                                                        <input type="file" id="projectinput2"  class="form-control img" name="image" accept="image/*" />--}}
                    <div class="container mt-3">
                        <div class="row mx-0"
                             style="border: 1px solid #ddd;padding: 30px 0px;">
                            <div class="col-12">
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-10 offset-1">
                                            <div class="variants">
                                                <div class='file file--upload w-100'>
                                                    <label for='file-input-edit'
                                                           class="w-100">
                                                        <i class="fas fa-cloud-upload-alt"></i>Upload
                                                    </label>
                                                    <!-- <input  id="file-input" multiple type='file' /> -->
                                                    <input type="file"
                                                           id="file-input-edit"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-10 offset-1">
                                <div class="preview-edit-container">
                                    @if($splash_screen)
                                        <div id="preview{{ $splash_screen->id }}" class="preview">
                                            @if ($splash_screen->image)
                                                <img src="{{asset('assets/images/'.$splash_screen->image) }}"
                                                     id="img{{  $splash_screen->id }}" alt="">
                                            @else
                                                <img src="{{ asset('/uploads/'.session('logo')) }}" alt=""
                                                     id="img{{  $splash_screen->id }}">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cropped_images"></div>
            <div class="modal-footer">
                <button id="sub-button-form" class="btn btn-primary">@lang( 'lang.update' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
            </div>

            {!! Form::close() !!}

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <div class="modal fade" id="imagesModal" tabindex="-1" role="dialog" aria-labelledby="imagesModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagesModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="croppie-modal-edit" style="display:none">
                        <div id="croppie-container-edit"></div>
                        <button data-dismiss="modal" id="croppie-cancel-btn-edit" type="button"
                                class="btn btn-secondary"><i
                                class="fas fa-times"></i></button>
                        <button id="croppie-submit-btn-edit" type="button" class="btn btn-primary"><i
                                class="fas fa-crop"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        $("#sub-button-form").click(function (e) {
            e.preventDefault();
            getImages()
            setTimeout(() => {
                $("#splash_screen_add_form").submit();
            }, 500)
        });
        const fileInputImages = document.querySelector('#file-input-edit');
        const previewImagesContainer = document.querySelector('.preview-edit-container');
        const croppieModal = document.querySelector('#croppie-modal-edit');
        const croppieContainer = document.querySelector('#croppie-container-edit');
        const croppieCancelBtn = document.querySelector('#croppie-cancel-btn-edit');
        const croppieSubmitBtn = document.querySelector('#croppie-submit-btn-edit');

        fileInputImages.addEventListener('change', () => {
            previewImagesContainer.innerHTML = '';
            let files = Array.from(fileInputImages.files)
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                let fileType = file.type.slice(file.type.indexOf('/') + 1);
                let FileAccept = ["jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "BMP", "bmp"];
                // if (file.type.match('image.*')) {
                if (FileAccept.includes(fileType)) {
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
                                    getImages()
                                }
                            });
                        });
                        preview.appendChild(deleteBtn);
                        const cropBtn = document.createElement('span');
                        cropBtn.setAttribute("data-toggle", "modal")
                        cropBtn.setAttribute("data-target", "#imagesModal")
                        cropBtn.classList.add('crop-btn');
                        cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                        cropBtn.addEventListener('click', () => {
                            setTimeout(() => {
                                launchImagesCropTool(img);
                            }, 500);
                        });
                        preview.appendChild(cropBtn);
                        previewImagesContainer.appendChild(preview);
                    });
                    reader.readAsDataURL(file);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("site.Oops...") }}',
                        text: '{{ __("site.Sorry , You Should Upload Valid Image") }}',
                    })
                }
            }
            getImages()
        });
        function launchImagesCropTool(img) {
            // Set up Croppie options
            const croppieOptions = {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square' // or 'square'
                },
                boundary: {
                    width: 200,
                    height: 200,
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
            croppieCancelBtn.addEventListener('click', () => {
                croppieModal.style.display = 'none';
                $('#imagesModal').modal('hide');
                croppie.destroy();
            });

            // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
            croppieSubmitBtn.addEventListener('click', () => {
                croppie.result('base64').then((croppedImg) => {
                    img.src = croppedImg;
                    croppieModal.style.display = 'none';
                    $('#imagesModal').modal('hide');
                    croppie.destroy();
                });
            });
        }
        function getImages() {
            $("#cropped_images").empty();
            setTimeout(() => {
                const container = document.querySelectorAll('.preview-edit-container');
                let images = [];
                for (let i = 0; i < container[0].children.length; i++) {
                    images.push(container[0].children[i].children[0].src)
                    var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                    $("#cropped_images").append(newInput);
                }
                console.log(images)
                return images
            }, 300);
        }
    </script>
