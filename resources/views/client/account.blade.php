@extends('layouts.app')

@section('style')
    <style>
        a.collapse-button {
            color: #343b7c !important;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        a.collapse-button.active {
            background-color: #343b7c;
            color: white !important;
        }

        .nav-tabs a.collapse-button.active,
        .save-button, .btn-access {
            background-color: #343b7c;
            color: white !important;
        }

        .nav-tabs {
            border-bottom: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profil Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid">
            <div class="row bg-white mx-4 py-3">
                <div class="col-md-3 d-flex justify-content-center pl-0">
                    <img id="selectedAvatar" src="{{ asset('images/' . Auth::user()->image) }}" class="rounded-circle"
                        style="width: 145px; height: 145px; object-fit: cover;" alt="example placeholder" />
                </div>
                <div class="col-md-9">
                    <h3 class="text-capitalize mt-4 mb-3 text-bold" style=" font-family: 'Oswald', sans-serif !important;">
                        {{ Auth::user()->name }}</h3>
                    <div class="d-flex align-items-center" style="color: #7b82c9;">
                        <i class="fa-solid fa-envelope mr-3" style="font-size: 31px;"></i>
                        <h4 class="m-0" style="font-family: Oswald !important;">{{ Auth::user()->email }}</h4>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center panel with-nav-tabs panel-default">
                <div class="col-md-2 panel-heading bg-white d-flex flex-column mr-4 py-4 rounded">
                    <ul class="nav nav-tabs justify-content-center w-100">
                        <li class="w-100">
                            <a class="btn collapse-button mb-2 rounded active" data-toggle="tab" href="#transactions"
                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                Latest Transactions
                            </a>
                        </li>
                        <li class="w-100">
                            <a class="btn collapse-button rounded" data-toggle="tab" href="#purchases" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                My purchases
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-9 panel-body bg-white p-4 rounded">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="transactions">
                            <div class="card border-0 m-0">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th>Quiz</th>
                                                <th>Price</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $order->quiz->image == 'blank.png' ? asset('images/quiz-938x675.png') : asset('images/'.$order->quiz->image) }}"
                                                            class="img-circle img-size-32 mr-2">
                                                        {{ $order->quiz->name }}
                                                    </td>
                                                    <td><span class="text-success mr-1">{{ $order->amount_stripe }}</span> {{ $order->currency }}</td>
                                                    <td>{{ $order->updated_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="purchases">
                            <div class="card border-0 m-0">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th>Quiz</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $order->quiz->image == 'blank.png' ? asset('images/quiz-938x675.png') : asset('images/'.$order->quiz->image) }}"
                                                            class="img-circle img-size-32 mr-2">
                                                        {{ Str::limit($order->quiz->name, 50, '...') }}
                                                    </td>
                                                    <td> {{ Str::limit($order->quiz->description, 30, '...') }}</td>
                                                    <td><span class="text-success mr-1">{{ $order->amount_stripe }}</span> {{ $order->currency }}</td>
                                                    <td><a href="{{ route('quiz', ['slug' => $order->quiz->slug]) }}"
                                                        class="btn btn-access">Access Now</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
