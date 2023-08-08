@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quiz : {{ $quiz->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Quiz</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @foreach ($quiz->questions as $question)
                        @if ($question->question)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title d-flex justify-content-between w-100">
                                            Question : {{ $question->question->name }}
                                            <form method="POST"
                                                action="{{ route('quiz.delete-question', ['id' => $question->question->id]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>

                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        @if ($question->question->options)
                                            <div class="card-body table-responsive p-0 mb-5">
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <th>title</th>
                                                        <th>coorect</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($question->question->options as $option)
                                                            <tr>
                                                                <td width="80%">{{ $option->name }}</td>
                                                                <td>{{ $option->is_correct ? 'Yes' : 'NO' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                        <form method="POST"
                                            action="{{ route('quiz.add-option', ['id' => $question->question->id]) }}">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>Correct answer</label>
                                                        <select name="is_correct" id="is_correct" class="form-control">
                                                            <option value="0" selected>No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="Enter ...">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label style="opacity: 0">xx</label>
                                                    <button type="submit" class="btn btn-primary form-control">Add</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Add new question :
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('quiz.add-question', ['id' => $quiz->id]) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Type</label>

                                                <select name="type" id="type" class="form-control">
                                                    <option value="" disabled selected></option>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter ...">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <label style="opacity: 0">xx</label>
                                            <button type="submit" class="btn btn-primary form-control">Add</button>
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
