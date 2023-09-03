@extends('layouts.master')
@section('content')
    <div class="d-flex justify-content-center row w-100 m-0">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/logo.png') }}" alt="" width="300px" class="profil">
            <div class="ms-5 mt-5">
                <form method="POST"
                    action="{{ route('quiz.store-answer', ['id' => $answer->id, 'question_id' => $question->id]) }}">
                    <div class="d-flex flex-column justify-content-between px-2 mb-5">
                        <h2 class="text-deco">{{ $answer->quiz->name }}</h2>
                        <p class="sous-title">{{ $answer->quiz->description }}</p>
                    </div>

                    @csrf
                    <div class="question bg-white p-3">
                        <div class="d-flex flex-row align-items-center question-title">
                            <h2 class="text-danger">Q.</h2>
                            <h3 class="mt-1 ml-2">{{ $question->name }}</h3>
                        </div>

                        @if ($question->question_type)
                            @if ($question->question_type->name === 'row answers')
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach ($question->options as $option)
                                                <th> {{ $option->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($question->options as $optionl)
                                            <tr>
                                                @foreach ($question->options as $k => $option)
                                                    @if ($loop->first)
                                                        <td> {{ $optionl->value }}</td>
                                                    @endif
                                                    <td>
                                                        <input required type="radio"
                                                            name="question[{{ $question->id }}][{{ $optionl->id }}]"
                                                            value="{{ $option->value }}"
                                                            class="@error('question') is-invalid @enderror">

                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @foreach ($question->options as $option)
                                    <div class="ans ml-2 lh-lg">
                                        <label class="radio">
                                            <input
                                                type="{{ $question->question_type && $question->question_type->name === 'one answer' ? 'radio' : 'checkbox' }}"
                                                name="question[]" value="{{ $option->id }}"
                                                class="@error('question') is-invalid @enderror">

                                            <span>{{ $option->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                        <div class="text-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br />
                            @endforeach
                            
                        </div>

                    </div>
                    <div class="d-flex flex-row justify-content-end align-items-center p-3 bg-white">
                        <button class="btn btn-primary border-success align-items-center btn-success" type="submit">Next<i
                                class="fa fa-angle-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
