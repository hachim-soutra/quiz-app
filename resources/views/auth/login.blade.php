<x-laravel-ui-adminlte::adminlte-layout>
    <style>
        * {
            font-family: 'Century Gothic Paneuropean', sans-serif !important;
        }

        .fa-classic,
        .fa-regular,
        .fa-solid,
        .far,
        .fas {
            font-family: "Font Awesome 6 Free" !important;
        }
    </style>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/home') }}"><img src="{{ asset('images/' . $logo_home->value) }}" width="100%"
                        alt="" style="height: auto;object-fit: cover;"></a>
            </div>
            <!-- /.login-logo -->

            <!-- /.login-box-body -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form method="post" action="{{ url('/login') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                class="form-control @error('email') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                            </div>
                            @error('email')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password" placeholder="Password" id="myInput"
                                class="form-control @error('password') is-invalid @enderror">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="showpass" onclick="myFunction()">
                                    <label for="showpass">Show Password</label>
                                </div>
                            </div>


                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>

                        </div>
                    </form>

                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                    </p>

                </div>
                <!-- /.login-card-body -->
            </div>

        </div>
        <!-- /.login-box -->
    </body>
    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</x-laravel-ui-adminlte::adminlte-layout>
