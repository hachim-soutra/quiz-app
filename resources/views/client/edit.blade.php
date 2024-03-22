@extends('layouts.app')

@section('style')
    <style>
        .form-control {
            height: 45px;
            color: black;
            border: 1px solid #d1cfcfc7;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 40px !important;
        }

        .icon-field {
            position: absolute;
            right: 35px;
            top: 27%;
            z-index: 9;
        }

        .fa-user,
        .fa-envelope {
            font-size: 20px;
            color: #6c6b6b;
        }

        .button-save {
            height: 44px;
            width: 98px;
            font-size: 17px;
        }

        .custom-file-input:lang(en)~.custom-file-label::after {
            content: "Browse";
            height: 100%;
            line-height: 37px;
        }

        .input-image {
            height: 45px;
            border-radius: 43px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Profil</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Profil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-5 bg-white">
                            <form class="col-md-10" method="POST" action="{{ route('client.save-profil') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row align-items-center justify-content-center">
                                    {{-- <label for="exampleInputPassword1" class="text-secondary col-md-3">Profil Image</label> --}}
                                    <img id="selectedAvatar"
                                        src="{{ asset(Auth::user()->image ? 'images/' . Auth::user()->image : 'images/user (1).png') }}"
                                        class="rounded-circle mr-5" style="width: 200px; height: 200px; object-fit: cover;"
                                        alt="example placeholder" />

                                    <label for="customFile2" class="btn rounded-pill mr-3"
                                        style="background-color: #343b7c; font-size: 17px;">
                                        <i class="fas fa-edit text-white" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" class="d-none" id="customFile2"
                                        onchange="displaySelectedImage(event, 'selectedAvatar')" name="image"
                                        value="{{ asset(Auth::user()->image ? 'images/' . Auth::user()->image : 'images/user (1).png') }}" />

                                </div>

                                <div class="form-group row align-items-center">
                                    <label for="exampleInputPassword1" class="text-secondary col-md-3">Full Name</label>
                                    <div class="input-group col-md-9 mb-3">
                                        <input type="text" name="name" value="{{ Auth::user()->name }}"
                                            class="form-control pl-4 text-secondary @error('name') is-invalid @enderror"
                                            placeholder="Enter Your Name ">
                                        <span class="icon-field"><i class="fa-regular fa-user"></i></span>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="exampleInputPassword1" class="text-secondary col-md-3">Email</label>
                                    <div class="input-group col-md-9 mb-3">
                                        <input type="email" name="email" value="{{ Auth::user()->email }}"
                                            class="form-control pl-4 text-secondary @error('email') is-invalid @enderror"
                                            placeholder="Enter Your Email">
                                        <span class="icon-field"><i class="fa-solid fa-envelope"></i></span>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <div class="d-flex col-md-9 align-items-center justify-content-between">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="submit"
                                                class="btn btn-info button-save rounded-pill font-weight-normal">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     bsCustomFileInput.init()
        // });
        // window.onload = function() {
        // displaySelectedImage({ target: document.getElementById('customFile2') }, 'selectedAvatar');
        // };
        function displaySelectedImage(event, elementId) {
            console.log(elementId);
            const selectedImage = document.getElementById(elementId);
            const fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    selectedImage.src = e.target.result;
                };
                reader.readAsDataURL(fileInput.files[0]);
            } else if (fileInput.value) {
                selectedImage.src = fileInput.value;
            } else {
                selectedImage.src = "https://mdbootstrap.com/img/Photos/Others/placeholder-avatar.jpg";
                // selectedImage.src = "{{ asset('images/user (1).png') }}";
            }
        }
    </script>
@endsection
