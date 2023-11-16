@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-6 col-md-8 col-lg-9">
                        <h1>{!! Str::limit($question->name, 120, ' ...') !!}</h1>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('quiz.index') }}">Quiz</a></li>
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
                                    Add new answer :
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('quiz.add-option', ['id' => $question->id]) }}">
                                    <div class="row">
                                        @if ($question->question_type->name !== 'row answers')
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Correct answer</label>
                                                    <select name="is_correct" id="is_correct" class="form-control">
                                                        <option value="0" selected>No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div
                                            class="{{ $question->question_type->name !== 'row answers' ? 'col-sm-8' : 'col-sm-5' }}">
                                            @csrf
                                            <div class="form-group">
                                                <label>Answer</label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter ...">
                                            </div>
                                        </div>
                                        @if ($question->question_type->name === 'row answers')
                                            <div class="col-sm-5">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Response</label>
                                                    <input type="text" name="value" class="form-control"
                                                        placeholder="Enter ...">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-sm-2">
                                            <label style="opacity: 0">xx</label>
                                            <button type="submit" class="btn btn-primary form-control">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 bg-white">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Answer</th>
                                    <th>{{ $question->question_type->name === 'row answers' ? 'Response' : 'Correct' }}</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($question->options as $item)
                                    <tr>
                                        <td title="{{ $item->name }}">{!! Str::limit($item->name, 100, '...') !!}</td>
                                        @if ($question->question_type->name !== 'row answers')
                                            <td>{{ $item->is_correct ? 'Yes' : 'NO' }}</td>
                                        @else
                                            <td>{{ $item->value }}</td>
                                        @endif
                                        <td>
                                            <a data-toggle="modal" data-target="#modal-update-{{ $item->id }}"
                                                class="btn btn-primary"><i class="fas fa-edit"></i>Update</a>
                                        </td>
                                        <td>
                                            <a data-toggle="modal" data-target="#modal-delete-{{ $item->id }}"
                                                class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                        <div class="modal fade" id="modal-delete-{{ $item->id }}" aria-modal="true"
                                            role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Delete answer</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('quiz.delete-option', ['id' => $item->id]) }}">
                                                            <div class="row">
                                                                @csrf
                                                                <div class="col-12">
                                                                    Are you sure delete {{ $item->name }}?
                                                                </div>
                                                                <div class="col-12 d-flex justify-content-end gap-5">
                                                                    <button type="button" class="btn btn-default mr-3"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Delete</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="modal fade" id="modal-update-{{ $item->id }}" aria-modal="true"
                                            role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update option</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('quiz.update-option', ['id' => $item->id]) }}">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label>Answer</label>
                                                                        <textarea name="name" class="form-control" cols="30" rows="3">{{ $item->name }}</textarea>
                                                                    </div>
                                                                    @if ($question->question_type->name !== 'row answers')
                                                                        <div class="form-group">
                                                                            <label>Correct answer</label>
                                                                            <select name="is_correct" id="is_correct"
                                                                                class="form-control">
                                                                                <option value="0" selected>No</option>
                                                                                <option value="1">Yes</option>
                                                                            </select>
                                                                        </div>
                                                                    @else
                                                                        <div class="form-group">
                                                                            <label>Value</label>
                                                                            <input type="text" name="value"
                                                                                value="{{ $item->value }}"
                                                                                class="form-control">
                                                                        </div>
                                                                    @endif
                                                                </div>


                                                                <div class="col-12 d-flex justify-content-end gap-5">
                                                                    <button type="button" class="btn btn-default mr-3"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Add</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- @foreach ($quiz->questions as $question)
                        @if ($question->question)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title d-flex align-items-center justify-content-between w-100">
                                            Question : {{ $question->question->name }}
                                            <form method="POST"
                                                action="{{ route('quiz.delete-question', ['id' => $question->question->id]) }}">
                                                @csrf
                                                <button type="button" class="btn btn-success">Update</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>

                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        @if ($question->question->options)
                                            <div class="card-body table-responsive p-0 mb-5">
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <th>answers</th>
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
                                                        <label>Answer</label>
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
                    @endforeach --}}
                </div>
            </div>
        </section>
    </div>
@endsection
