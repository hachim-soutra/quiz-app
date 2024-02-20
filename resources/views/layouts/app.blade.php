<x-laravel-ui-adminlte::adminlte-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdn.datatables.net/v/bs4-4.6.0/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/rg-1.4.1/datatables.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/1.7.0/jquery-confirm.min.css"
        integrity="sha512-aSZhdO9qRbI5Yvk2tJciP+L7R++CSmyZE3vekxHiW55tQb7dgXxpX0PXr188QfzThNGC8Nb7Wrn9fUCAD/KpyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    {{-- <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css"
        rel="stylesheet"
      /> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200,500&display=swap');

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

        .btn {
            white-space: nowrap;
        }

        .text-danger {
            color: red !important;
        }

        .content-header h1 {
            font-size: 20px !important;
        }

        .white-space {
            white-space: nowrap;
        }

        .img-circle {
            border-radius: 50%;
            width: 36px;
            height: 36px;
        }

        .link-style {
            font-size: 17px;
            color: #343b7c;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol" !important;
            font-weight: 500;
        }

        .dropdown-menu{
            border: none !important;
            border-radius: 0.25rem !important;
        }
    </style>
    @yield('style')

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Main Header -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light pr-0">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown user-menu d-flex">
                        <img src="{{ asset('images/' . Auth::user()->image) }}" class="img-circle elevation-2"
                            alt="User Image">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <span class="d-none d-md-inline link-style">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            {{-- <div class="text-center">
                                <p>
                                    {{ Auth::user()->email }}<br />
                                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                                </p>
                            </div>
                            <div class="user-footer">
                                <a href="#" class="btn btn-default btn-flat float-right"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div> --}}
                            <a href="{{ route('client.edit-profil') }}"
                                class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.edit-profil') ? 'active' : '' }}">
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                <p>Edit Profile</p>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('client.update-password') }}"
                                class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.update-password') ? 'active' : '' }}">
                                <i class="fa-solid fa-unlock mr-2"></i>
                                <p>Update Password</p>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item d-flex align-items-center text-dark px-3"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i>
                                <p>Sign out</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </div>


        </div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script
            src="https://cdn.datatables.net/v/bs4-4.6.0/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/rg-1.4.1/datatables.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/1.7.0/jquery-confirm.min.js"
            integrity="sha512-3rO4uA/MW2+0ttYBRkgnI8teWs5ZFT3jwFZksUnrr9ViTEQ6fSrxHARcJ/WTM8VLcMP/FFyBuEYihtFEwtFczw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <!-- MDB -->
<script
type="text/javascript"
src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"
></script> --}}
        @yield('js')

    </body>
</x-laravel-ui-adminlte::adminlte-layout>
