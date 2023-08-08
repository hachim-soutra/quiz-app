@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10 col-lg-10">
                <div class="border">
                    <form method="POST" action="{{ route('quiz.store-answer', ['id' => $quiz->id]) }}">

                        <div class="question bg-white p-3 border-bottom">
                            <div class="d-flex flex-column justify-content-between align-items-center">
                                <h4>{{ $quiz->name }}</h4>
                                <p>{{ $quiz->description }}</p>
                                <div>
                                    <label for="email">email :</label>
                                    <input type="email" id="email" name="email">
                                </div>
                            </div>
                        </div>
                        @csrf
                        @foreach ($quiz->questions as $question)
                            @if ($question->question)
                                <div class="question bg-white p-3 border-bottom">
                                    <div class="d-flex flex-row align-items-center question-title">
                                        <h3 class="text-danger">Q.</h3>
                                        <h5 class="mt-1 ml-2">{{ $question->question->name }}</h5>
                                    </div>
                                    @foreach ($question->question->options as $option)
                                        <div class="ans ml-2">
                                            <label class="radio">
                                                <input type="radio" name="question[{{ $question->question->id }}]"
                                                    value="{{ $option->id }}">
                                                <span>{{ $option->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                        <div class="d-flex flex-row justify-content-end align-items-center p-3 bg-white">
                            <button class="btn btn-primary border-success align-items-center btn-success"
                                type="submit">Next<i class="fa fa-angle-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
