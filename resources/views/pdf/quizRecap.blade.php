<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('recap-pdf.css') }}">
        <title>Quiz Recap</title>
</head>

<body>
    <div class="d-flex justify-content-center row w-100 m-0" id="answer">
        {{-- <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0"> --}}
        <table class="table-no-border">
            <tr>
                <td class="w-100">
                    {{-- <img src="{{ asset('images/' . $answer->quiz->image)}}" alt="" width="200" /> --}}
                    <img src="{{ base64_encode(file_get_contents(url('images/logo.png')))}}"  alt="" width="200" />
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="text-center">
                    @if ($answer->email)
                        <h2 class="text-deco">User email : {{ $answer->email }}</h2>
                    @endif
                    @if ($answer->score < intval($answer->target))
                        <p class="text-review">
                            {{ $below_target }}
                            <br />
                            Here below a quick summary of your assessment
                        </p>
                    @else
                        <p class="text-review">
                            {{ $above_target }}
                            <br />
                            Here below a quick summary of your assessment
                        </p>
                    @endif
                    <p class="fw-bold mb-0">Score :
                        <span
                            class="{{ $answer->score >= intval($answer->target) ? 'text-success' : 'text-danger' }}">{{ round($answer->score, 2) }}%
                            correct ({{ $answer->nbr_of_correct }} / {{ count($answer->questions_json) }})</span>
                    </p>
                    @if ($answer->status && $answer->status !== 'good')
                        <p class="status-text mb-0 mt-2">Status : {{ $answer->status }}</p>
                    @endif
                </td>
            </tr>
        </table>

        <table class="mt-5">
            <tr>
                <td>
                    <h4 class="ml-4"><strong class="text-deco border-bottom border-dark pb-1">Quiz </strong>:
                        {{ $answer->quiz->name }}</h4>
                </td>
            </tr>
            @foreach ($answer->getQuestions()->sortBy('sort') as $question)
                <tr>
                    <td>
                        <div class="question bg-white p-3 border-bottom">
                            <div class="d-flex flex-row align-items-center question-title">
                                <h3 class="mt-1 ml-2">{{ $question['name'] }}</h3>
                            </div>
                            @if ($question['image'])
                                <img src="{{ asset('images/question/' . $question['image']) }}" width="40%"
                                    height="auto" class="mt-3 rounded" alt="imgg">
                                <br>
                                <br>
                            @endif
                            @if ($question['type'] === 'row answers')
                                <table width="100%">
                                    <tr>
                                        <th></th>
                                        @foreach ($question['options'] as $option)
                                            <th> {{ $option['name'] }}</th>
                                        @endforeach
                                    </tr>
                                    @foreach ($question['options'] as $optionl)
                                        <tr
                                            class="{{ is_array($question['value']) && $question['value'][$question['id']][$optionl['id']] === $optionl['value'] ? 'bg-success-1' : 'bg-danger-1' }}">
                                            @foreach ($question['options'] as $k => $option)
                                                @if ($loop->first)
                                                    <td>{{ $optionl['value'] }}</td>
                                                @endif
                                                <td>
                                                    <input required type="radio" value="{{ $option['value'] }}"
                                                        {{ is_array($question['value']) && $question['value'][$question['id']][$optionl['id']] === $option['value'] ? 'checked' : '' }}>
                                                    {{ $option['value'] }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </table>
                                @if (!is_array($question['value']) || count(array_diff($question['value'][$question['id']], $question['corrects'])) > 0)
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
                                            <input disabled type="radio" name="question[{{ $option['id'] }}]"
                                                value="1"
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
                    </td>
                </tr>
            @endforeach
        </table>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
</body>

</html>
