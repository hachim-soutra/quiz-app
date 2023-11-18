<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Quiz App</title>
    <link href="https://fonts.cdnfonts.com/css/century-gothic-paneuropean" rel="stylesheet">

    <style>
        * {
            font-family: 'Century Gothic Paneuropean', sans-serif;

        }
    </style>
</head>

<body>
    <section>
        <div class="container">
            <div class="d-flex flex-column align-items-center p-5">
                <img src="{{ asset('images/' . $logo_home->value) }}" alt="" height="120px">
                <p class="text-bold text-center h2 font">Welcome to pminlife online quiz application</p>
                <p class="pb-3 text-center h5 font">Take assesment tests in very
                    reliable
                    and fast way</p>

                <img src="{{ asset('assets/images/Group171.svg') }}" alt="" height="30%">
                <a target="_blank" href="{{ route('home') }}" class="btn btn-success mt-5"
                    style="background-color: #343b7cff;
                        padding: 0.6rem 4rem;
                        border-radius: 0.5rem;
                        font-size: 1.3rem;
                        font-weight: 700;">
                    Acces now</a>
            </div>
        </div>
</body>

</html>
