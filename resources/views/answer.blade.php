@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0" id="answer">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">
            <div class="">
                <div class="d-flex flex-column align-items-center justify-content-between px-2 mb-5">
                    @if ($answer->email)
                        <h2 class="text-deco">User email : {{ $answer->email }}</h2>
                    @endif
                    @if ($answer->score < intval($answer->target))
                        <p class="text-review">
                            Thank you for completing the quiz, unfortunately your score is below target 😟, which is
                            {{ $answer->target }}% of
                            correct answers.<br />
                            Here below a quick summary of your assessment
                        </p>
                    @else
                        <p class="text-review">
                            Thank you for completing the quiz, Well done 👍 your score is above target, which is
                            {{ $answer->target }}% of
                            correct answers.<br />
                            Here below a quick summary of your assessment
                        </p>
                    @endif
                    <p class="fw-bold mb-0">Score :
                        <span
                            class="{{ $answer->score >= intval($answer->target) ? 'text-success' : 'text-danger' }}">{{ round($answer->score, 2) }}%
                            correct ({{ $answer->nbr_of_correct }} / {{ count($answer->answers) }})</span>
                    </p>
                    @if ($answer->status && $answer->status !== 'good')
                        <p class="status-text mb-0 mt-2">Status : {{ $answer->status }}</p>
                    @endif
                </div>
                @csrf
                @foreach ($answer->getQuestions()->sortBy('sort') as $question)
                    <div class="question bg-white p-3 border-bottom">
                        <div class="d-flex flex-row align-items-center question-title">
                            <h3 class="mt-1 ml-2">{{ $question['name'] }}</h3>
                        </div>
                        @if ($question['image'])
                            <img src="{{ asset('images/question/' . $question['image']) }}" width="40%" height="auto"
                                class="mt-3 rounded" alt="imgg">
                            <br>
                            <br>
                        @endif
                        @if ($question['type'] === 'row answers')
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach ($question['options'] as $option)
                                            <th> {{ $option['name'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($question['options'] as $optionl)
                                        <tr
                                            class=" {{ $question['value'][$question['id']][$optionl['id']] === $optionl['value'] ? 'bg-success-1' : 'bg-danger-1' }}">
                                            @foreach ($question['options'] as $k => $option)
                                                @if ($loop->first)
                                                    <td>{{ $optionl['value'] }}</td>
                                                @endif
                                                <td>
                                                    <input required type="radio" value="{{ $option['value'] }}"
                                                        {{ $question['value'][$question['id']][$optionl['id']] === $option['value'] ? 'checked' : '' }}>
                                                    {{ $option['value'] }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if (count(array_diff($question['value'][$question['id']], $question['corrects'])) > 0)
                                <br>
                                <strong class="text-danger ms-3">
                                    {{ $question['error'] }}
                                </strong>
                                <br>
                            @endif
                        @else
                            @foreach ($question['options'] as $option)
                                <div class="ans ml-2">
                                    <label
                                        class="radio {{ is_array($question['value']) && in_array($option['id'], $question['value']) && $option['is_correct'] == 0 ? 'text-danger' : '' }}
                                        {{ $option['is_correct'] == 1 ? 'text-success' : '' }} ">
                                        <input disabled type="radio" name="question[{{ $option['id'] }}]" value="1"
                                            {{ is_array($question['value']) && in_array($option['id'], $question['value']) ? 'checked' : '' }}>
                                        <span>{{ $option['name'] }}</span>
                                    </label>
                                </div>
                            @endforeach
                            @if (!is_array($question['value']) || count(array_diff($question['value'], $question['corrects'])) > 0)
                                <br>
                                <strong class="text-danger ms-3">
                                    {{ $question['error'] }}
                                </strong>
                                <br>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
