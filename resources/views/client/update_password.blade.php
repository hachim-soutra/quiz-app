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
            right: 31px;
            top: 26%;
            z-index: 9;
        }

        .fa-eye {
            font-size: 20px;
            display: inline-block;
            color: #858282;
        }

        .button-save {
            height: 44px;
            font-size: 18px;
            letter-spacing: 1px;
            width: 140px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Password</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Update Password</li>
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
                            <form class="col-md-10" method="POST" action="{{ route('update-account', auth()->id()) }}" enctype="multipart/form-data">
                                <div class="form-group row align-items-center">
                                    <label for="exampleInputPassword1" class="text-secondary col-md-3"
                                        style="font-family: Lato, Arial, sans-serif;">New Password</label>
                                    <div class="input-group mb-3 col-md-9">
                                        <input type="password" id="password1" name="password"
                                            class="form-control password pl-4 text-secondary @error('password') is-invalid @enderror"
                                            placeholder="Enter The New Password">
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <span class="icon-field togglePassword"
                                            onclick="togglePasswordVisibility('password1')"><i
                                                class="fa-solid fa-eye"></i></span>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="exampleInputPassword1" class="text-secondary col-md-3">Confirm Password</label>
                                    <div class="input-group col-md-9 mb-3">
                                        <input type="password" id="password2" name="password_confirmation"
                                            class="form-control password pl-4 text-secondary  @error('password_confirmation') is-invalid @enderror"
                                            placeholder="Confirm Password">
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <span class="icon-field togglePassword"
                                            onclick="togglePasswordVisibility('password2')"><i
                                                class="fa-solid fa-eye"></i></span>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="btn btn-info button-save rounded-pill text-bold mt-3">Save</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script>
        function togglePasswordVisibility(passwordId) {
            var passwordInput = document.getElementById(passwordId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
@endsection
