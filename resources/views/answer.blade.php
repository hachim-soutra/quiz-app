@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0" id="answer">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/logo-question.jpg') }}" alt="" width="300px" class="profil">
            <div class="">
                <div class="d-flex flex-column align-items-center justify-content-between px-2 mb-5">
                    @if ($answer->email)
                        <h2 class="text-deco">User email : {{ $answer->email }}</h2>
                    @endif
                    @if ($answer->score < 75)
                        <p class="text-review">
                            Thank you for completing the quiz, unfortunately your score is below target 😟, which is 75% of
                            correct answers.<br />
                            Here below a quick summary of your assessment
                        </p>
                    @else
                        <p class="text-review">
                            Thank you for completing the quiz, Well done 👍 your score is above target, which is 75% of
                            correct answers.<br />
                            Here below a quick summary of your assessment
                        </p>
                    @endif
                    <p class="fw-bold">Score :
                        <span
                            class="{{ $answer->score > 75 ? 'text-success' : 'text-danger' }}">{{ round($answer->score, 2) }}%
                            correct ({{ $answer->nbr_of_correct }} / {{ count($answer->answers) }})</span>
                    </p>
                </div>
                @csrf
                @foreach ($answer->quiz->questions as $question)
                    @if ($question->question && isset($answer->answers[$question->question->id]))
                        <div class="question bg-white p-3 border-bottom">
                            <div class="d-flex flex-row align-items-center question-title">
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
                                            @if (isset($answer->answers[$question->question->id][$optionl->id]))
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
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @dd($question->question->options(), $answer->answers)
                                @if (
                                    !Helper::compareArray(
                                        $question->question->options()->pluck('question_id', 'value')->toArray(),
                                        $answer->answers[$question->question->id]))
                                    <br>
                                    <strong class="text-danger ms-3">
                                        {{ $question->question->error }}
                                    </strong>
                                    <br>
                                @endif
                            @else
                                @foreach ($question->question->options as $option)
                                    @isset($answer->answers[$question->question->id])
                                        <div class="ans ml-2">

                                            <label
                                                class="radio {{ in_array($option->id, $answer->answers[$question->question->id]) && $option->is_correct == 0 ? 'text-danger' : '' }} {{ $option->is_correct == 1 ? 'text-success' : '' }} ">
                                                <input type="radio" name="question[{{ $option->id }}]" value="1"
                                                    {{ in_array($option->id, $answer->answers[$question->question->id]) ? 'checked' : '' }}>
                                                <span>{{ $option->name }}</span>
                                            </label>
                                        </div>
                                    @endisset ()
                                @endforeach
                                @isset($answer->answers[$question->question->id])
                                    @if (count(array_diff(
                                                $answer->answers[$question->question->id],
                                                $question->question->options()->where('is_correct', 1)->pluck('id')->toArray())) > 0 ||
                                            count(array_diff(
                                                    $question->question->options()->where('is_correct', 1)->pluck('id')->toArray(),
                                                    $answer->answers[$question->question->id])) > 0)
                                        <br>
                                        <strong class="text-danger ms-3">
                                            {{ $question->question->error }}
                                        </strong>
                                        <br>
                                    @endif
                                @endisset
                            @endif

                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
@endsection
