@extends('layouts.app')

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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Quizzes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @foreach ($quizzes as $quiz)
                        <div class="col-md-4">
                            <div class="card w-100 rounded">
                                <div class="card-body">
                                    <h6><span
                                            class="badge float-right {{ $quiz->payement_type == 'payed' ? 'badge-warning' : 'badge-secondary' }} px-2 py-1">{{ $quiz->payement_type }}</span>
                                        </h5>
                                        <h2 class="card-title decor-text text-capitalize font-weight-bold">
                                            {{ $quiz->name }}</h2>
                                        <p class="card-text font-weight-light mt-5">{{ $quiz->description }}</p>
                                        @if ($quiz->price)
                                            <p class="card-text text-secondary text-bold">{{ $quiz->price }}$</p>
                                        @endif
                                        <a @if ($quiz->price) data-toggle="modal" data-target="#exampleModal" @else href="{{ route('quiz', ['slug' => $quiz->slug]) }}" @endif
                                            class="btn btn-info rounded-pill px-4">Check Quiz</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h3 class="text-center">You don't have access to this quiz ! </h3>
                                </div>
                                <div class="d-flex justify-content-end pt-0 px-3 pb-3">
                                    <button type="button" class="btn btn-secondary rounded-pill mr-2 px-4" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-warning rounded-pill px-4">Buy It</button>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
