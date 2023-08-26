<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        `@font-face {
            font-family: CenturyGothic;
            src: url('/assets/fonts/CenturyGothic.tff');
        }

        ` * {
            font-family: 'CenturyGothic', sans-serif;
        }

        .cover {
            height: auto;
            object-fit: cover;
        }

        .profil {
            height: 150px;
            width: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: -75px;
            position: relative;
            border: 1px solid #d7d5d5;
            margin-left: 4rem;
        }

        .sous-title {
            font-size: 20px;
            margin-left: 14px;
            font-weight: 400;
        }

        .text-deco {
            text-decoration: underline;
            text-underline-offset: 7px;
        }

        .user-info {
            position: relative;
            top: 2px;
            left: 94px;
            width: calc(100% - 200px);
        }

        .text-review {
            margin: 1rem 14rem;
            font-size: 19px;
            text-align: center;
        }
        .bg-success-1 {
            background-color: #c2e8b3f7 !important;
        }

        .bg-danger-1 {
            background-color: #ff00004d !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <main>
            <div class="d-flex align-items-center">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>
