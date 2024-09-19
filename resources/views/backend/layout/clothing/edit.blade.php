@extends('backend.app')

@section('title', 'Add new clothes')

@push('styles')
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 220px;
        }

        .custom-file-input {
            position: relative;
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-align: center;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-label {
            position: absolute;
            pointer-events: none;
            color: #999;
        }

        .file-preview img {
            width: 100%;
            height: 150px;
            display: none;
        }
    </style>
@endpush


@section('content')
    <div class="relative min-h-screen group-data-[sidebar-size=sm]:min-h-sm">
        <div
            class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
            <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

                <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                    <div class="grow">
                        <h5 class="text-16">Update Product</h5>
                    </div>
                    <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                        <li
                            class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                            <a href="{{ route('clothing.index') }}" class="text-slate-400 dark:text-zink-200">Products</a>
                        </li>
                        <li class="text-slate-700 dark:text-zink-100">
                            Update Product
                        </li>
                    </ul>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
                    <div class="xl:col-span-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('clothing.update', ['id' => $globalclothing->id]) }}" method="POST"
                                    enctype="multipart/form-data" id="productForm">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 xl:grid-cols-12">

                                        <div class="xl:col-span-6">
                                            <label for="productNameInput"
                                                class="inline-block mb-2 text-base font-medium">Item Title</label>
                                            <input type="text" id="productNameInput"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                name="title" placeholder="Item title" value="{{ $globalclothing->title }}"
                                                required>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="xl:col-span-6">
                                            <label for="productCodeInput"
                                                class="inline-block mb-2 text-base font-medium">Product Number</label>
                                            <input type="text" id="productCodeInput"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                name="product_number" placeholder="TWT145015"
                                                value="{{ $globalclothing->product_number }}" required>
                                            @error('product_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="xl:col-span-6">
                                            <label for="qualityInput" class="inline-block mb-2 text-base font-medium">TOG
                                                Value</label>
                                            <input type="text"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                name="tog_value" placeholder="Thermal Overall Grade Value"
                                                value="{{ $globalclothing->tog_value }}" required>
                                            @error('tog_value')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="xl:col-span-6">
                                            <label for="qualityInput"
                                                class="inline-block mb-2 text-base font-medium">Brand</label>
                                            <input type="text"
                                                class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                                name="brand" placeholder="Brand Name" value="{{ $globalclothing->brand }}"
                                                required>
                                            @error('brand')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="lg:col-span-2 xl:col-span-12">
                                            <label for="imageInput" class="inline-block mb-2 text-base font-medium">Product
                                                Images</label>
                                            <div class="custom-file-input">
                                                <!-- File input for selecting new image -->
                                                <input type="file" id="fileInput" class="file-input"
                                                    onchange="previewFile()" name="image" />

                                                <!-- Label for file input -->
                                                <label id="fileLabel" for="fileInput" class="file-label">Drag and drop your
                                                    product images or <a href="#!">browse</a>
                                                    your product images</label>

                                                <!-- Image preview section -->
                                                <div id="filePreview" class="file-preview">
                                                    @if ($globalclothing->image)
                                                        <!-- Display existing image if available -->
                                                        <img src="{{ asset($globalclothing->image) }}" alt="Existing Image"
                                                            style="display: block;">
                                                    @endif
                                                </div>
                                            </div>
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>



                                        <div class="lg:col-span-2 xl:col-span-12">
                                            <div>
                                                <label for="productDescription"
                                                    class="inline-block mb-2 text-base font-medium">Product
                                                    Description</label>
                                                <textarea id="productDescription" name="product_dsc"
                                                    class="form-input border-slate-200 dark:border-zinc-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zinc-600 disabled:border-slate-300 dark:disabled:border-zinc-500 dark:disabled:text-zinc-200 disabled:text-slate-500 dark:text-zinc-100 dark:bg-zinc-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zinc-200"
                                                    rows="6" placeholder="Enter Description">{{ $globalclothing->product_dsc }}</textarea>
                                            </div>
                                            @error('product_dsc')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div><!--end grid-->
                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="reset"
                                            class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-700 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">Reset</button>
                                        <button type="submit"
                                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update
                                            Product</button>
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

@push('scripts')
    <script>
        function previewFile() {
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('filePreview');
            const fileLabel = document.getElementById('fileLabel'); 
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                const img = document.createElement('img');
                img.src = reader.result;
                filePreview.innerHTML = ''; 
                filePreview.appendChild(img);
                img.style.display = 'block';

                fileLabel.style.display = 'none';
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                filePreview.innerHTML = '';

                fileLabel.style.display = 'block';
            }
        }
    </script>
@endpush
