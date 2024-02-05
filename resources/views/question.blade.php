@extends('layouts.master')
@section('content')
    <div class="d-flex justify-content-center row w-100 m-0">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">
            <div class="mt-3">
                @if ($break)
                    <div class="d-flex flex-column justify-content-between px-2">
                        <h2 class="text-deco">Take break <span class="countdown"></span>
                        </h2>
                        <p class="sous-title">{{ $break_text->value }}</p>

                    </div>
                    <a href="{{ route('questions', ['token' => $answer->token, 'id' => $id, 'pass' => true]) }}"
                        class="btn btn-primary float-end">Back to quiz</a>
                @else
                    <div class="d-flex flex-column justify-content-between px-2">
                        <h2 class="text-deco">
                            {{ $answer->quiz->name }}
                        </h2>
                        <p class="sous-title">{{ $answer->quiz->description }}</p>

                    </div>
                    @if ($answer->getQuestionsIgnored() || $answer->getQuestionsReview())
                        <div class="w-100 d-flex flex-row justify-content-end align-items-center py-3 bg-white gap-2">

                            @if (count($answer->getQuestionsIgnored()) > 0)
                                <form action="{{ route('question.preview', ['token' => $answer->token]) }}" method="POST"
                                    id="ignoredForm">
                                    @csrf
                                    <input type="hidden" name="timer" class="timer3">
                                    <select name="question_id" id="ignoredInput" class="form-control">
                                        <option disabled selected>ignored ({{ count($answer->getQuestionsIgnored()) }})
                                        </option>
                                        @foreach ($answer->getQuestionsIgnored() as $q)
                                            <option value=" {{ $q['id'] }}" title="{{ $q['name'] }}">
                                                {{ Str::limit($q['name'], 40, '...') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                            @if (count($answer->getQuestionsReview()) > 0)
                                <form action="{{ route('question.preview', ['token' => $answer->token]) }}"
                                    id="reviewedForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="timer" class="timer3">
                                    <select name="question_id" id="reviewedInput" class="form-control">
                                        <option disabled selected>marked for review
                                            ({{ count($answer->getQuestionsReview()) }})</option>
                                        @foreach ($answer->getQuestionsReview() as $q)
                                            <option value=" {{ $q['id'] }}" title="{{ $q['name'] }}">
                                                {{ Str::limit($q['name'], 40, '...') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif

                        </div>
                    @endif
                    <div class="d-flex flex-row justify-content-between align-items-center py-3 bg-white gap-2">
                        <div class="d-flex flex-row  justify-content-start align-items-center">
                            @if ($answer->timer)
                                <div class="my-2"
                                    style="
                                display: flex;
                                align-self: end;
                                justify-content: center;
                                align-items: center;
                                gap: 10px;
                                font-size: 1.3rem;
                                color: white;
                            ">
                                    <button class="btn btn-outline-success countdown-btn" disabled>
                                        <i class="fa fa-regular fa-clock"></i>
                                        <span class="countdown"></span>
                                    </button>
                                    <button class="btn btn-outline-dark" id="stopTimer" type="button">
                                        <i class="fa-regular fa-circle-pause"></i> Pause
                                    </button>
                                </div>
                                <input type="hidden" name="timer" id="timer">
                            @endif
                        </div>
                        <div class="d-flex flex-row justify-content-end align-items-center py-3 bg-white gap-2">
                            @if ($answer->getQuestion($id)['sort'] > 1)
                                <form action="{{ route('question.prev', ['id' => $id, 'token' => $answer->token]) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="timer" id="timer3">
                                    <button type="submit" class="btn btn-outline-dark align-items-center"><i
                                            class="fa fa-angle-left mr-3"></i> Previous
                                    </button>
                                </form>
                            @endif
                            <form method="POST"
                                action="{{ route('question.review', ['id' => $id, 'token' => $answer->token]) }}">
                                @csrf
                                <input type="hidden" name="timer" id="timer1">
                                <button class="btn btn-outline-dark align-items-center">
                                    <i class="fa fa-solid fa-rotate-right"></i> Mark for review
                                </button>
                            </form>
                            <form method="POST"
                                action="{{ route('question.ignore', ['id' => $id, 'token' => $answer->token]) }}">
                                @csrf
                                <input type="hidden" name="timer" id="timer2">
                                <button class="btn btn-outline-dark align-items-center">
                                    <i class="fa-regular fa-circle-xmark"></i> Ignore
                                </button>
                            </form>
                            <a href="{{ route('quiz.expired', ['token' => $answer->token, 'status' => 'Terminate test']) }}"
                                class="btn btn-outline-danger"><i class="fa-solid fa-fire"></i> Terminate Test</a>

                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('quiz.next', ['token' => $answer->token, 'question_id' => $id]) }}">

                        @csrf
                        <div class="question bg-white my-3">
                            <div class="d-flex flex-row align-items-start question-title flex-column">
                                @if ($answer->getQuestion($id)['value'] === 'review')
                                    <small class="text-primary"><i class="fa fa-solid fa-rotate-right"></i> Review
                                        Question</small>
                                @endif
                                @if ($answer->getQuestion($id)['value'] === null)
                                    <small class="text-primary"><i class="fa fa-solid fa-rotate-right"></i> ignored
                                        Question</small>
                                @endif
                                <h3 class="ml-2 d-block"> <i class="fa fa-question-circle" aria-hidden="true"></i>
                                    {{ $answer->getQuestion($id)['name'] }}</h3>
                                @if ($answer->getQuestion($id)['type'] === 'multiple answer')
                                    <small class="text-danger">multiple answers possible</small>
                                    <br>
                                @endif

                                @if ($answer->getQuestion($id)['image'])
                                    <img src="{{ asset('images/question/' . $answer->getQuestion($id)['image']) }}"
                                        width="40%" height="auto" class="mt-3 rounded" alt="img">
                                    <br>
                                    <br>
                                @endif
                            </div>

                            @if ($answer->getQuestion($id)['type'])
                                @if ($answer->getQuestion($id)['type'] === 'row answers')
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @foreach ($answer->getQuestion($id)['options'] as $option)
                                                    <th> {{ $option['name'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($answer->getQuestion($id)['options'] as $optionl)
                                                <tr>
                                                    @foreach ($answer->getQuestion($id)['options'] as $k => $option)
                                                        @if ($loop->first)
                                                            <td> {{ $optionl['value'] }}</td>
                                                        @endif
                                                        <td>
                                                            <input required type="radio"
                                                                name="question[{{ $id }}][{{ $optionl['id'] }}]"
                                                                value="{{ $option['value'] }}"
                                                                {{ isset($answer->answers[$id]) && $option['value'] == $answer->answers[$id][$optionl['id']] ? 'checked' : '' }}
                                                                class="@error('question') is-invalid @enderror">

                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    @foreach ($answer->getQuestion($id)['options'] as $option)
                                        <div class="ans ml-2 lh-lg">
                                            <label class="radio">
                                                <input
                                                    {{ isset($answer->getQuestion($id)['value']) && is_array($answer->getQuestion($id)['value']) && in_array($option['id'], $answer->getQuestion($id)['value']) ? 'checked' : '' }}
                                                    type="{{ $answer->getQuestion($id)['type'] === 'one answer' ? 'radio' : 'checkbox' }}"
                                                    name="question[]" value="{{ $option['id'] }}"
                                                    class="@error('question') is-invalid @enderror">

                                                <span>{{ $option['name'] }}</span>
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
                        <div class="d-flex flex-row justify-content-between align-items-center py-3 bg-white gap-2">
                            <button class="btn btn-primary border-success align-items-center btn-success"
                                type="submit"><i class="fa fa-check ml-3" aria-hidden="true"></i> Next
                            </button>
                            <input type="hidden" name="timer" id="timer4">
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

@if ($break)
    @section('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            window.onload = function() {

                var timerBreak = "{{ $answer->quiz->break_time }}";

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
                            "{{ route('questions', ['token' => $answer->token, 'id' => $id, 'pass' => true]) }}";
                    } else {

                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        minutes = (minutes < 0) ? 59 : minutes;
                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                        $('.countdown').html(hours + ':' + minutes + ':' + seconds);
                        timerBreak = hours + ':' + minutes + ':' + seconds;
                        $('#timer').val(timerBreak);
                    }
                    if (timer2 === "0:00:10") {
                        $('.countdown').addClass(" zoom-in-out");
                    }
                }
                var interval = setInterval(countdown, 1000);

            }
        </script>
    @endsection
@endif
@if ($answer->timer && !$break)
    @section('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            window.onload = function() {
                var timer2 = "{{ $answer->timer }}";
                var breakQuestion = "{{ $break }}";
                var timerReminer = "{{ $answer->quiz->quiz_time_remind }}";

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
                    if (hours == 0 && minutes == 0 && seconds == 0) {
                        clearInterval(interval);
                        window.location =
                            "{{ route('quiz.expired', ['token' => $answer->token, 'status' => 'Time out']) }}";
                    } else {
                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        minutes = (minutes < 0) ? 59 : minutes;
                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                        $('.countdown').html(hours + ':' + minutes + ':' + seconds);

                        let x1 = parseInt(timerReminder[0]) * 3600 + parseInt(timerReminder[1]) * 60 + parseInt(
                            timerReminder[2]);
                        let x2 = parseInt(timer[0]) * 3600 + parseInt(timer[1]) * 60 + parseInt(timer[2]);
                        if (x1 >= x2) {
                            $('.countdown-btn').addClass('btn-outline-danger');
                        }
                        timer2 = hours + ':' + minutes + ':' + seconds;
                        $('#timer').val(timer2);
                        $('#timer1').val(timer2);
                        $('#timer2').val(timer2);
                        $('#timer3').val(timer2);
                        $('#timer4').val(timer2);
                        $('.timer3').val(timer2);
                    }
                    if (timer2 === "0:00:10") {
                        $('.countdown-btn').addClass(" zoom-in-out");
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

                $('#reviewedInput').change(function() {
                    $('#reviewedForm').submit();
                });

                $('#ignoredInput').change(function() {
                    $('#ignoredForm').submit();
                });
            }
            window.scrollTo({
                left: 0,
                top: document.body.scrollHeight,
                behavior: "smooth"
            });
        </script>
    @endsection

@endif
