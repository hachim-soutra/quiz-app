@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10 col-lg-10">
                <div class="border">
                    <div class="question bg-white border-bottom quiz-info">
                        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%"
                            class="cover">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="300px" class="profil">
                        <div class="d-flex flex-column justify-content-between px-2 user-info my-3">
                            <h2 class="text-deco">{{ $answer->quiz->name }}</h2>
                            <p class="sous-title">{{ $answer->quiz->description }}</p>
                        </div>
                    </div>

                    <div class="question bg-white p-3 border-bottom">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h2>User email : {{ $answer->email }}</h2>
                            @if($answer->score < 75)
                            <p class="text-review">
                                Thank you for completing the quiz, unfortunatly your score is below target 😟, which is 75% of correct answers, here below a quick summarize of your assessment
                            </p>
                            @else
                            <p class="text-review">
                                Thank you for completing the quiz, Well done 👍  your score is above target, which is 75% of correct answers, here below a quick summarize of your assessment
                            </p>

                            @endif
                            <p class="fw-bold">Score :
                                <span class="{{ $answer->score > 75 ? 'text-success' : 'text-danger' }}">{{ $answer->score }}%</span>
                                </p>
                        </div>
                    </div>

                    @foreach ($answer->quiz->questions as $question)
                        @if ($question->question)
                            <div class="question bg-white p-3 border-bottom">
                                <div class="d-flex flex-row align-items-center question-title">
                                    <h2 class="text-danger">Q.</h2>
                                    <h3 class="mt-1 ml-2">{{ $question->question->name }}</h3>
                                </div>
                                @if ($question->question->question_type->name === 'row answers')
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @foreach ($question->question->options as $option)
                                                    <th> {{ $option->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($question->question->options as $optionl)
                                                <tr
                                                    class="{{ $answer->answers[$question->question->id][$optionl->id] === $optionl->value ? 'bg-success-1' : 'bg-danger-1' }}">
                                                    @foreach ($question->question->options as $k => $option)
                                                        @if ($loop->first)
                                                            <td>{{ $optionl->value }}</td>
                                                        @endif
                                                        <td>
                                                            <input required type="radio"
                                                                {{ $answer->answers[$question->question->id][$optionl->id] == $option->value ? 'checked' : '' }}
                                                                name="question[{{ $question->question->id }}][{{ $optionl->id }}]"
                                                                value="{{ $optionl->value }}">

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if (count(array_diff(
                                                $question->question->options()->pluck('value')->toArray(),
                                                array_values($answer->answers[$question->question->id]))) > 0)
                                        <br>
                                        <strong class="text-danger ms-3">
                                            {{ $question->question->error }}
                                        </strong>
                                        <br>
                                    @endif
                                @else
                                    @foreach ($question->question->options as $option)
                                        <div class="ans ml-2">

                                            <label
                                                class="radio {{ in_array($option->id, $answer->answers[$question->question->id]) && $option->is_correct == 0 ? 'text-danger' : '' }} {{ $option->is_correct == 1 ? 'text-success' : '' }} ">
                                                <input type="radio" name="question[{{ $option->id }}]" value="1"
                                                    {{ in_array($option->id, $answer->answers[$question->question->id]) ? 'checked' : '' }}>
                                                <span>{{ $option->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                    @if (count(array_diff(
                                                $answer->answers[$question->question->id],
                                                $question->question->options()->where('is_correct', 1)->pluck('id')->toArray())) > 0)
                                        <br>
                                        <strong class="text-danger ms-3">
                                            {{ $question->question->error }}
                                        </strong>
                                        <br>
                                    @endif
                                @endif

                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
