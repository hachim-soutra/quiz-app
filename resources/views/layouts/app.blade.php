<x-laravel-ui-adminlte::adminlte-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link
        href="https://cdn.datatables.net/v/bs4-4.6.0/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/rg-1.4.1/datatables.min.css"
        rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/1.7.0/jquery-confirm.min.css"
        integrity="sha512-aSZhdO9qRbI5Yvk2tJciP+L7R++CSmyZE3vekxHiW55tQb7dgXxpX0PXr188QfzThNGC8Nb7Wrn9fUCAD/KpyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.jqueryui.css">
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

        .dropdown-menu {
            border: none !important;
            border-radius: 0.25rem !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid !important;
            border-radius: 0 !important;
            padding: 0 !important;
            background-color: transparent;
            margin-left: 3px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }

        .dataTables_wrapper {
            padding: 0;
            padding-top: 10px;
            background-color: rgba(0, 0, 0, .03);
        }

        table.dataTable thead th {
            border: none !important;
        }

        div#myTable_length {
            padding-left: 20px !important;
            padding-top: 10px !important;
        }

        .dataTables_filter {
            padding-right: 20px;
            padding-top: 10px;
        }
        .card .overlay {
            width: 100%;
            height: 230px;
            border-radius: 10px 10px 0 0;
            top: 0;
            left: 0;
            opacity: 0;
            transition: 0.3s;
            background-color: rgb(34 33 33 / 75%);
        }

        .card:hover .overlay {
            opacity: 1;
        }

        .button-color {
            background-color: #051036;
            color: white;
        }

        .button-access {
            border-radius: 25px;
            margin-left: -7px;
            font-weight: 600;
        }

        .card-title {
            margin-bottom: 0.75rem;
            font-weight: 600;
            font-size: 17px;
        }

        .next-button
    {
        background-color: #cfe2f3;
        width: 12%;
        font-weight: 600;
    }

        .price-desc {
            font-size: 16px;
            color: #f2bb13;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .description {
            color: white;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .card-rounded {
            border-radius: 10px !important;
            width: 100%;
            margin: 10px;
        }

        .card-img,
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 230px;
            width: 100%;
            object-fit: cover;
        }

        .card-body::after {
            display: none;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .btn-light:hover {
            background-color: #051036;
            color: white;
        }

        .filter-input {
            display: flex;
            align-items: center;
            gap: 35px;
            height: 33px;
            background-color: #051036;
            color: white;
        }

        .btn-light:not(:disabled):not(.disabled):active,
        .show>.btn-light.dropdown-toggle {
            background-color: #051036;
            color: white;
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
                        <img src="{{ asset(Auth::user()->image ? 'images/' . Auth::user()->image : 'images/user (1).png') }}"
                            class="img-circle elevation-2" alt="User Image">
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
                            <a href="{{ auth()->user()->userable_type == 'client' ? route('client.edit') : route('admin.edit') }}"
                                class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.edit') ? 'active' : '' }}">
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                <p>Edit Profile</p>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ auth()->user()->userable_type == 'client' ? route('client.update-password') : route('admin.update-password') }}"
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
        {{-- <script
            src="https://cdn.datatables.net/v/bs4-4.6.0/dt-1.13.8/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/rg-1.4.1/datatables.min.js">
        </script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        {{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
        {{-- <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/1.7.0/jquery-confirm.min.js"
            integrity="sha512-3rO4uA/MW2+0ttYBRkgnI8teWs5ZFT3jwFZksUnrr9ViTEQ6fSrxHARcJ/WTM8VLcMP/FFyBuEYihtFEwtFczw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        {{-- <!-- MDB -->
<script
type="text/javascript"
src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"
></script> --}}
        @yield('js')

    </body>
</x-laravel-ui-adminlte::adminlte-layout>
