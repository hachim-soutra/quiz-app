<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/century-gothic-paneuropean" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        * {
            font-family: 'Century Gothic Paneuropean', sans-serif;
        }

        h1 {
            font-weight: 900;
        }

        h2 {
            font-weight: 700;
        }

        h3 {
            font-weight: 500;
        }

        .cover {
            object-fit: cover;
            height: 33vh;
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
            margin: 1rem 0rem;
            font-size: 19px;
            text-align: center;
            width: 100%;
        }

        .bg-success-1 {
            background-color: #c2e8b3f7 !important;
        }

        .bg-danger-1 {
            background-color: #ff00004d !important;
        }

        .text-danger {
            color: red !important;
        }

        input[type="checkbox"],
        input[type="radio"] {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 1px solid currentColor;
        }

        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background: url("{{ url('check.png') }}") no-repeat left center;
            background-size: 20px;
            padding-left: 25px;
            border: none;
            filter: brightness(0);
        }

        #divToExport input[type="checkbox"]:checked,
        #divToExport input[type="radio"]:checked {
            filter: brightness(1);
        }

        #divToExport .bg-danger-1 input[type="radio"]:checked,
        #divToExport .text-danger input[type="checkbox"]:checked,
        #divToExport .text-danger input[type="radio"]:checked {
            filter: brightness(1) !important;
            background: url("{{ url('check-red.png') }}") no-repeat left center !important;
            background-size: 20px !important;
            padding-left: 25px !important;
            border: none !important;
        }

        .question-title h3 {
            font-size: 24px !important;
        }

        .question span {
            font-size: 16px !important;
        }

        .zoom-in-out {
            margin: 0 .5rem;
            animation: zoom-in-zoom-out 1s ease infinite;
        }

        @keyframes zoom-in-zoom-out {
            0% {
                transform: scale(1, 1);
            }

            50% {
                transform: scale(1.2, 1.2);
            }

            100% {
                transform: scale(1, 1);
            }
        }

        .status-text {
            font-weight: 900;
            color: red;
        }

        /* Styles for the loading spinner */
        .loading-spinner {
            display: none;
        }

        .loading-spinner.active {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Define the color change animation */
        @keyframes colorChange {
            0% {
                border-color: #ff0000;
                /* Red */
            }

            25% {
                border-color: green;
                /* Green */
            }

            50% {
                border-color: orange;
                /* Blue */
            }

            75% {
                border-color: green;
                /* Magenta */
            }

            100% {
                border-color: #ff0000;
                /* Red */
            }
        }

        /* Apply the rotation animation to the element */
        .rotating-image {
            position: relative;
            /* border: 4px solid #ff0000; */
            /* border: 4px solid #ff0000; */
            /* Initial border color */
            /* border-radius: 50%; */
            /* animation: rotate 5s infinite linear, colorChange 2s infinite linear; */
        }

        .next-button
    {
        background-color: #cfe2f3;
        width: 12%;
        font-weight: 600;
    }
    </style>
    @yield('style')
</head>

<body>
    <div id="app">
        <main>
            <div class="d-flex align-items-center">
                <!-- Loading Spinner -->
                <div class="loading-spinner active " id="loadingSpinner">
                    <img src="{{ asset('images/Loading_icon.gif') }}" alt="" width="150px" height="auto"
                        class="rotating-image">
                </div>
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script>
        setTimeout(function() {
            var element = document.getElementById("loadingSpinner");
            console.log("------------------------------");
            element.classList.remove("active");
        }, 2000);
    </script>
    @yield('js')
</body>

</html>
