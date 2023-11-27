<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
<style>
    .preview-category-container {
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

        {!! Form::open(['url' => action('CategoryController@store'), 'method' => 'post', 'id' => $quick_add ? 'quick_add_category_form' : 'category_add_form', 'files' => true]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.add_category' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __('lang.name') . ':*') !!}
                <div class="input-group my-group">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('lang.name'), 'required']) !!}
                    <span class="input-group-btn">
                        <button class="btn btn-default bg-white btn-flat translation_btn" type="button" data-type="category"><i
                                class="dripicons-web text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
            @include('layouts.partials.translation_inputs', [
                'attribute' => 'name',
                'translations' => [],
                'type' => 'category',
            ])
            <div class="form-group">
                {!! Form::label('description', __('lang.description') . ':') !!}
                {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => __('lang.description')]) !!}
            </div>
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            @if ($type == 'category')
                <div class="form-group">
                    <label for="product_class_id">{{ __('lang.class') }}:</label>
                    <select class="form-control" data-live-search="true" style="width: 100%" placeholder="{{ __('lang.please_select') }}" required id="cat_product_class_id" name="product_class_id">
                        <option value="" selected disabled>{{ __('lang.please_select') }}</option>
                        @foreach($product_classes as $product_class_id => $product_class)
                            <option value="{{ $product_class_id }}">{{ $product_class }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger hide required-class">Tis field is required</span>
                </div>
            @endif
            @if ($type == 'sub_category')
                <div class="form-group ">
                    {!! Form::label('parent_id', __('lang.parent_category') . ':') !!}
                    {!! Form::select('parent_id', $categories, false, ['class' => 'form-control', 'data-live-search' => 'true', 'style' => 'width: 100%', 'placeholder' => __('lang.please_select'), 'id' => 'parent_id']) !!}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="file-input-category"> {{ __('lang.image') }}</label>
                        <div class="container mt-3">
                            <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                                <div class="col-12">
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10 offset-1">
                                                <div class="variants">
                                                    <div class='file file--upload w-100'>
                                                        <label for='file-input-category' class="w-100">
                                                            <i class="fas fa-cloud-upload-alt"></i>Upload
                                                        </label>
                                                        <!-- <input  id="file-input" multiple type='file' /> -->
                                                        <input type="file" id="file-input-category">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 offset-1">
                                    <div class="preview-category-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
{{--            @include('layouts.partials.image_crop')--}}
        </div>

        <div class="modal-footer">
            <button id="add-category-btn"  class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>
        <div id="cropped_add_category_images"></div>
        {!! Form::close() !!}
    <!-- Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <div id="croppie-category-modal" style="display:none">
                            <div id="croppie-category-container"></div>
                            <button data-dismiss="modal" id="croppie-category-cancel-btn" type="button" class="btn btn-secondary"><i
                                    class="fas fa-times"></i></button>
                            <button id="croppie-category-submit-btn" type="button" class="btn btn-primary"><i
                                    class="fas fa-crop"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $('#cat_product_class_id').selectpicker('render');
    $('#parent_id').selectpicker('render');

    @if ($type == 'category')
        $('.view_modal').on('shown.bs.modal', function () {
        $("#cat_product_class_id").selectpicker("val", $('#product_class_id').val());
        })
    @endif
    @if ($type == 'sub_category')
        $('.view_modal').on('shown.bs.modal', function () {
        $("#parent_id").selectpicker("val", $('#category_id').val());
        })
    @endif
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    $("#add-category-btn").on("click",function (e){
        e.preventDefault();
         // Check if a value is selected
         var selectedValue = $("#cat_product_class_id").val();
        if (!selectedValue) {
            // Display an error message or perform any other desired action
            $(".required-class").removeClass("hide");
            return; // Prevent form submission
        }
        setTimeout(()=>{
            getAddCategoryImages();
            $("#category_add_form").submit();
        },500)
    });

    var fileAddCategoryInput = document.querySelector('#file-input-category');
    var previewAddCategoryContainer = document.querySelector('.preview-category-container');
    var croppieAddCategoryModal = document.querySelector('#croppie-category-modal');
    var croppieAddCategoryContainer = document.querySelector('#croppie-category-container');
    var croppieAddCategoryCancelBtn = document.querySelector('#croppie-category-cancel-btn');
    var croppieAddCategorySubmitBtn = document.querySelector('#croppie-category-submit-btn');
    // let currentFiles = [];
    fileAddCategoryInput.addEventListener('change', () => {
        previewAddCategoryContainer.innerHTML = '';
        let files = Array.from(fileAddCategoryInput.files)
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
                                getAddCategoryImages()
                            }
                        });
                    });
                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#addCategoryModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                        setTimeout(() => {
                            launchAddCategoryCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewAddCategoryContainer.appendChild(preview);
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

        getAddCategoryImages()
    });
    function launchAddCategoryCropTool(img) {
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
        const croppie = new Croppie(croppieAddCategoryContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieAddCategoryModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieAddCategoryCancelBtn.addEventListener('click', () => {
            croppieAddCategoryModal.style.display = 'none';
            $('#addCategoryModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieAddCategorySubmitBtn.addEventListener('click', () => {
            croppie.result('base64').then((croppedImg) => {
                img.src = croppedImg;
                croppieAddCategoryModal.style.display = 'none';
                $('#addCategoryModal').modal('hide');
                croppie.destroy();
                getAddCategoryImages()
            });
        });
    }
    function getAddCategoryImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-category-container');
            let images = [];
            $("#cropped_add_category_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "cropImages[]").val(container[0].children[i].children[0].src);
                $("#cropped_add_category_images").append(newInput);
            }
            return images
        }, 300);
    }

</script>



