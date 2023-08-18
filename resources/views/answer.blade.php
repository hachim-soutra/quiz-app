@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10 col-lg-10">
                <div class="border">
                    <div class="question bg-white p-3 border-bottom">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h4>User email : {{ $answer->email }}</h4>
                            <p>Score {{ $answer->score }}</p>
                        </div>
                    </div>

                    @foreach ($answer->quiz->questions as $question)
                        @if ($question->question)
                            <div class="question bg-white p-3 border-bottom">
                                <div class="d-flex flex-row align-items-center question-title">
                                    <h3 class="text-danger">Q.</h3>
                                    <h5 class="mt-1 ml-2">{{ $question->question->name }}</h5>
                                </div>
                                @foreach ($question->question->options as $option)
                                    <div class="ans ml-2">
                                        <label class="radio {{ $option->is_correct == 1 ? 'text-success' : '' }}">
                                            <input type="radio" name="question[{{ $option->id }}]" value="1"
                                                {{ $option->is_correct == 1 ? 'checked' : '' }}>
                                            <span>{{ $option->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
