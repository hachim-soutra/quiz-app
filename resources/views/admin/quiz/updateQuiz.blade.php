@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quiz</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item "><a href="{{ route('quiz.index') }}">Quiz</a></li>
                            <li class="breadcrumb-item active">update Quiz</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Update Quiz
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('quiz.update', ['quiz' => $item->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Quiz type
                                                    {{ old('quiz_type') ? $item->quiz_type : old('quiz_type') }}</label>
                                                <select name="quiz_type" id="quiz_type"
                                                    class="form-control @error('quiz_type') is-invalid @enderror">
                                                    <option value="1"
                                                        {{ (!old('quiz_type') ? $item->quiz_type : old('quiz_type')) == 1 ? 'selected' : '' }}>
                                                        simple quiz</option>
                                                    <option value="2"
                                                        {{ (!old('quiz_type') ? $item->quiz_type : old('quiz_type')) == 2 ? 'selected' : '' }}>
                                                        test quiz</option>
                                                    <option value="3"
                                                        {{ (!old('quiz_type') ? $item->quiz_type : old('quiz_type')) == 3 ? 'selected' : '' }}>
                                                        simuler quiz</option>
                                                </select>
                                                @error('quiz_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="control-form">
                                                <label for="">Folder</label>
                                                <select name="folder" class="form-control">
                                                    @foreach ($folders as $folder)
                                                        <option value="{{$folder->id}}"
                                                            {{$folder->id == $item->folder_id ? 'selected' : '' }}>{{$folder->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Text <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('name') is-invalid @enderror" name="name" rows="3">{{ old('name') ? old('name') : $item->name }}</textarea>
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description" rows="3">{{ old('description') ? old('description') : $item->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">

                                            <div class="form-group">
                                                <label>upload image</label>
                                                <input type="file" class="form-control" name="image">
                                                <img src="{{ asset('images/' . $item->image) }}" width="150"
                                                    class="mt-3 rounded">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 quiz_time_group">
                                            <div class="form-group">
                                                <label>Select timer</label>
                                                <input type="time" name="quiz_time"
                                                    class="form-control @error('quiz_time') is-invalid @enderror"
                                                    value="{{ old('quiz_time') || !$item->quiz_time ? old('quiz_time') : Carbon\Carbon::parse($item->quiz_time)->format('H:i') }}">
                                                @error('quiz_time')
                                                    <div class="text-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 quiz_time_group">
                                            <div class="form-group">
                                                <label for="">Warning select
                                                    timer</label>
                                                <input type="time" name="quiz_time_remind"
                                                    value="{{ old('quiz_time_remind') || !$item->quiz_time_remind ? old('quiz_time_remind') : Carbon\Carbon::parse($item->quiz_time_remind)->format('H:i') }}"
                                                    class="form-control @error('quiz_time_remind') is-invalid @enderror" />
                                                @error('quiz_time_remind')
                                                    <div class="text-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 quiz_break_grp">
                                            <div class="form-group">
                                                <label for="">number of questions before break</label>
                                                <input type="number"
                                                    class="form-control @error('nbr_questions_sequance') is-invalid @enderror"
                                                    name="nbr_questions_sequance"
                                                    value="{{ old('nbr_questions_sequance') ? old('nbr_questions_sequance') : $item->nbr_questions_sequance }}">
                                                @error('nbr_questions_sequance')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 quiz_break_grp">
                                            <div class="form-group">
                                                <label for="">Define break</label>
                                                <input type="time" name="break_time"
                                                    value="{{ old('break_time') || !$item->break_time ? old('break_time') : Carbon\Carbon::parse($item->break_time)->format('H:i') }}"
                                                    class="form-control @error('break_time') is-invalid @enderror">
                                                @error('break_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end gap-5">
                                            <a href="{{ route('quiz.index') }}" class="btn btn-default mr-3">Retour</a>
                                            @if ($item->quiz_time)
                                                <a href="{{ route('quiz.timer', ['id' => $item->id]) }}" type="submit"
                                                    class="btn btn-danger mr-3">Remove Timer</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            function quizShow() {
                if ($('#quiz_type').val() == 1) {
                    $('.quiz_time_group').hide();
                    $('.quiz_break_grp').hide();
                } else if ($('#quiz_type').val() == 2) {
                    $('.quiz_time_group').show();
                    $('.quiz_break_grp').hide();
                } else if ($('#quiz_type').val() == 3) {
                    $('.quiz_time_group').show();
                    $('.quiz_break_grp').show();
                }
            }
            quizShow();
            $('#quiz_type').on('change', function() {
                quizShow();
            });
        });
    </script>
@endsection
