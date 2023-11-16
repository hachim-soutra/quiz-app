@extends('layouts.master')
@section('content')
    <div class="d-flex justify-content-center row w-100 m-0">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">
            <div class="mt-3">

                @if ($break)
                    <div class="d-flex flex-column justify-content-between px-2">
                        <h2 class="text-deco">Take break</h2>
                        <p class="sous-title">xxxxxxxx</p>
                        <div class="countdown"></div>
                    </div>
                @else
                    <form method="POST"
                        action="{{ route('quiz.store-answer', ['id' => $answer->id, 'question_id' => $question->id]) }}">
                        <div class="d-flex flex-column justify-content-between px-2">
                            <h2 class="text-deco">{{ $answer->quiz->name }}</h2>
                            <p class="sous-title">{{ $answer->quiz->description }}</p>
                        </div>

                        @csrf
                        <div class="question bg-white my-3">
                            <div class="d-flex flex-row align-items-start question-title flex-column">
                                @if ($answer->timer)
                                    <div class="my-2"
                                        style="
                                            display: flex;
                                            align-self: end;
                                            justify-content: center;
                                            align-items: center;
                                            gap: 10px;
                                        ">
                                        <span class="font-semibold ">Time left :</span>
                                        <div class="countdown"
                                            style="
                                                display: inline;
                                                background-color: green;
                                                color: white;
                                                padding: 5px 10px;
                                                border-radius: 5px;
                                            ">
                                        </div>
                                        <button class="btn btn btn-primary" type="button" id="stopTimer">Stop</button>
                                    </div>
                                    <input type="hidden" name="timer" id="timer">
                                @endif
                                <h3 class="mt-1 ml-2 d-block">{{ $question->name }}</h3>
                                @if ($question->question_type && $question->question_type->name === 'multiple answer')
                                    <small class="text-danger">multiple answers possible</small>
                                    <br>
                                @endif
                                @if ($question->image)
                                    <img src="{{ asset('images/question/' . $question->image) }}" width="40%"
                                        height="auto" class="mt-3 rounded" alt="imgg">
                                    <br>
                                    <br>
                                @endif
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
                                                                {{ isset($answer->answers[$question->id]) && $option->value == $answer->answers[$question->id][$optionl->id] ? 'checked' : '' }}
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
                                                    {{ isset($answer->answers[$question->id]) && in_array($option->id, $answer->answers[$question->id]) ? 'checked' : '' }}
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
                        <div class="d-flex flex-row justify-content-end align-items-center p-3 bg-white gap-5">
                            @if ($questionPreview)
                                <a href="{{ route('questions', ['token' => $answer->token, 'id' => $questionPreview->id]) }}"
                                    class="btn btn-primary border-primary align-items-center btn-primary"
                                    type="submit">Previous<i class="fa fa-angle-right ml-2"></i>
                                </a>
                            @endif
                            <button class="btn btn-primary border-success align-items-center btn-success"
                                type="submit">Next<i class="fa fa-angle-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="stop-modal" data-bs-backdrop='static'>
            <div class="modal-dialog" role="document" style="top: 30%">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Votre test est mis en attente</h3>
                        <p>......</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="start-timer">Reprendre le test</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        window.onload = function() {
            var timer2 = "{{ $answer->timer }}";
            var breakQuestion = "{{ $break }}";
            var timerReminer = "{{ $answer->quiz->quiz_time_remind }}";
            var timerBreak = "{{ $answer->quiz->break_time }}";

            if (timer2 && !breakQuestion) {

                function countdown() {
                    var timer = timer2.split(':');
                    var timerReminder = timerReminer.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var hours = parseInt(timer[0], 10);
                    var minutes = parseInt(timer[1], 10);
                    var seconds = parseInt(timer[2], 10);
                    --seconds;
                    hours = (hours > 0 && minutes == 0) ? --hours : hours;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    console.log(hours, minutes, seconds);
                    if (hours == 0 && minutes == 0 && seconds == 0) {
                        clearInterval(interval);
                        window.location = "{{ route('quiz.expired', ['token' => $answer->token]) }}";
                    } else {
                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        minutes = (minutes < 0) ? 59 : minutes;
                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                        $('.countdown').html(hours + ':' + minutes + ':' + seconds);
                        if (hours <= timerReminder[0] && minutes < timerReminder[1]) {
                            $('.countdown').css('background-color', 'red');
                        }
                        timer2 = hours + ':' + minutes + ':' + seconds;
                        $('#timer').val(timer2);
                    }
                }

                var interval = setInterval(countdown, 1000);

                $('#stopTimer').click(function() {
                    clearInterval(interval);
                    $('#stop-modal').modal('show');
                });
                $('#start-timer').click(function() {
                    $('#stop-modal').modal('hide');
                    interval = setInterval(countdown, 1000);
                });
            }

            if (breakQuestion) {
                function countdown() {
                    var timer = timerBreak.split(':');
                    var hours = parseInt(timer[0], 10);
                    var minutes = parseInt(timer[1], 10);
                    var seconds = parseInt(timer[2], 10);
                    --seconds;
                    hours = (hours > 0 && minutes == 0) ? --hours : hours;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    if (hours + minutes + seconds == 0) {

                        clearInterval(interval);
                        window.location =
                            "{{ route('questions', ['token' => $answer->token, 'id' => $question->id, 'pass' => true]) }}";
                    } else {

                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        minutes = (minutes < 0) ? 59 : minutes;
                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                        $('.countdown').html(hours + ':' + minutes + ':' + seconds);
                        timerBreak = hours + ':' + minutes + ':' + seconds;
                        $('#timer').val(timerBreak);
                    }
                }
                var interval = setInterval(countdown, 1000);
            }
        }
    </script>
@endsection
