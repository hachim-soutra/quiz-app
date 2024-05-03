@extends('layouts.app')

@section('style')
    <style>
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
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quizzes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Quizzes</li>
                        </ol>
                    </div>
                </div>
                <div class="row m-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <p class="text-bold mb-0">{{ count($quizzes) }} of {{ $total_quizzes }} quizzes</p>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle filter-input" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter By Status
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <a href="{{ route('client.quizzes') }}"
                                    class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.edit') ? 'active' : '' }}">
                                    <p>All</p>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('client.quizzes') . '?type=' . App\Enum\PayementTypeEnum::FREE->value }}"
                                    class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.update-password') ? 'active' : '' }}">
                                    <p>Free</p>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('client.quizzes') . '?type=' . App\Enum\PayementTypeEnum::PAYED->value }}"
                                    class="dropdown-item d-flex text-dark px-3 {{ Request::is('client.update-password') ? 'active' : '' }}">
                                    <p>Paid</p>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($quizzes as $quiz)
                        <div class="col-md-4 px-0 d-flex justify-content-center">
                            <div class="card card-rounded">
                                <img class="card-img-top"
                                    src="{{ $quiz->image == 'blank.png' ? asset('images/quiz-938x675.png') : asset('images/' . $quiz->image) }}"
                                    alt="Card image cap">
                                <div class="ribbon-wrapper ribbon-lg">
                                    @if ($quiz->payement_type == App\Enum\PayementTypeEnum::FREE->value)
                                        <div class="ribbon text-lg text-capitalize bg-info"> {{ $quiz->payement_type }}
                                        </div>
                                    @elseif($quiz->payement_type == App\Enum\PayementTypeEnum::PAYED->value)
                                        @if ($quiz->orders->count())
                                            @if ($quiz->orders[0]->status == 'paid')
                                                <div class="ribbon text-lg text-capitalize bg-success"> Payed </div>
                                            @endif
                                        @else
                                            <div class="ribbon text-lg text-capitalize bg-secondary">
                                                {{ $quiz->payement_type }} </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="card-title text-center">{{ Str::limit($quiz->name, 100, '...') }}</h5>
                                    @if ($quiz->payement_type == App\Enum\PayementTypeEnum::PAYED->value)
                                        <div class="price-desc">
                                            <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            {{ $quiz->price }} $
                                        </div>
                                    @endif

                                    @if ($quiz->payement_type == App\Enum\PayementTypeEnum::FREE->value)
                                        <a href="{{ route('client.quiz', ['slug' => $quiz->slug]) }}" target="_blank"
                                            class="btn d-block button-access button-color">Access now</a>
                                    @elseif($quiz->payement_type == App\Enum\PayementTypeEnum::PAYED->value)
                                        @if ($quiz->orders->count())
                                            @if ($quiz->orders[0]->status == 'paid')
                                                <a href="{{ route('client.quiz', ['slug' => $quiz->slug]) }}"
                                                    target="_blank" class="btn d-block  button-access button-color">Access
                                                    now</a>
                                            @endif
                                        @else
                                            <a @if ($quiz->price_token) href="{{ route('checkout', ['price_token' => $quiz->price_token, 'quiz_id' => $quiz->id]) }}" target="_blank" @endif
                                                class="btn d-block button-access button-color">Buy now</a>
                                        @endif
                                    @endif
                                </div>
                                <div class="overlay justify-content-start align-items-start px-3 pt-3">
                                    <div class="xx">
                                        <div class="description"> {{ Str::limit($quiz->description, 200, '...') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </section>
        <a id="back-to-top" href="#" class="btn button-color back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
@endsection
