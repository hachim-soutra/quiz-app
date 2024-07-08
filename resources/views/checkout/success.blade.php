@extends('layouts.app')

@section('content')
    <div class="container-fluid text-center d-flex flex-column justify-content-center align-items-center">
        <h2 class="text-bold text-secondary mt-5">Your order was successfully processed</h2>
        <h2 class="text-bold text-secondary">Thank You!</h2>
        <img class="w-50 h-50"
            src="{{ asset('assets/images/3d-hand-holding-bank-card-near-payment-terminal-removebg-preview.png') }}"
            alt="">
        @if (isset($quiz))
            <a href="{{ route('client.quiz', ['slug' => $quiz->slug]) }}" class="btn btn-primary d-block w-25 mt-4">Access
                Quiz Now</a>
        @else
            <a href="{{ route('client.quizzes') }}?{{ $query }}"
            class="btn d-block button-access button-color">Access Promotion Now</a>
        @endif
    </div>
@endsection
