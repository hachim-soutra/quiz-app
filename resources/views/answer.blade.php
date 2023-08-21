@extends('layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10 col-lg-10">
                <div class="border">
                    <div class="question bg-white p-3 border-bottom">
                        <div class="d-flex flex-column justify-content-between align-items-center">
                            <h4>User email : {{ $answer->email }}</h4>
                            <p>Score {{ $answer->score }}%</p>
                        </div>
                    </div>

                    @foreach ($answer->quiz->questions as $question)
                        @if ($question->question)
                            <div class="question bg-white p-3 border-bottom">
                                <div class="d-flex flex-row align-items-center question-title">
                                    <h3 class="text-danger">Q.</h3>
                                    <h5 class="mt-1 ml-2">{{ $question->question->name }}</h5>
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
                                                    bgcolor="{{ $answer->answers[$question->question->id][$optionl->id] === $optionl->value ? 'green' : 'red' }}">
                                                    @foreach ($question->question->options as $k => $option)
                                                        @if ($loop->first)
                                                            <td>{{ $optionl->value }}</td>
                                                        @endif
                                                        <td>
                                                            <input required type="radio"
                                                                {{ $answer->answers[$question->question->id][$option->id] == $optionl->value && $option->id === $optionl->id ? 'checked' : '' }}
                                                                name="question[{{ $question->question->id }}][{{ $optionl->id }}]"
                                                                value="{{ $optionl->value }}">
                                                            {{-- {{ $option->id }} --}}
                                                            {{-- {{ $optionl->id }} --}}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            {{-- {{ dd($answer->answers) }} --}}
                                        </tbody>
                                    </table>
                                    {{-- count(array_diff(array_values($answer->answers[$question->question->id]),
                                                $question->question->options()->pluck('value')->toArray())) > 0 || --}}
                                    @if (count(array_diff(array_values($answer->answers[$question->question->id]),
                                                $question->question->options()->pluck('value')->toArray())) > 0)
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
