@extends('layouts.app')

@section('content')
    <div class="container-fluid text-center d-flex flex-column justify-content-center align-items-center">
        <h2 class="text-bold text-secondary mt-5">Your Order was successfully processed</h2>
        <h2 class="text-bold text-secondary">Thank You!</h2>
        <img class="w-50 h-50" src="{{ asset('assets/images/3d-hand-holding-bank-card-near-payment-terminal-removebg-preview.png')}}" alt="">
        <a href="{{ route('client.quiz', ['slug' => $quiz->slug ]) }}" class="btn btn-primary d-block w-25 mt-4" >Access Quiz Now</a>
    </div>
@endsection
