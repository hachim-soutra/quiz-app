@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-6 col-md-8 col-lg-9">
                        <h1>Quiz : {!! Str::limit($quiz->name, 20, '...') !!}</h1>
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
                                    Add new question :
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('quiz.add-question', ['id' => $quiz->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select name="type" id="type" class="form-control" required>
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
                                                <input type="text" name="name" class="form-control" required
                                                    placeholder="Enter ...">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <label style="opacity: 0">xx</label>
                                            <button type="submit" class="btn btn-primary form-control">Add</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Comment if wrong answer</label>
                                                <input type="text" name="error" class="form-control "
                                                    placeholder="Enter ...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>upload image</label>
                                                <input name="image" type="file" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>categorie</label>
                                                <select name="categorie" class="form-control">
                                                    @foreach ($categories as $categorie)
                                                        <option value="{{ $categorie->id }}"
                                                            {{ $categorie->id == 1 ? 'selected' : '' }}>
                                                            {{ $categorie->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="col-lg-12 d-flex">
                                    <div class="col-lg-10">
                                        <form method="POST" action="{{ route('questions.import', ['id' => $quiz->id]) }}"
                                            enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Csv</label>
                                                        <input type="file" name="file"
                                                            class="form-control @error('file') is-invalid @enderror"
                                                            placeholder="Upload ...">
                                                        @error('file')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <a href="{{ url('/excel/questions.csv') }}">excel example file</a>
                                                    </div>

                                                </div>

                                                <div class="col-sm-2">
                                                    <label style="opacity: 0">xx</label>
                                                    <button type="submit"
                                                        class="btn btn-primary form-control">Import</button>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-lg-2">
                                        <form action="{{ route('quiz.delete-all', ['id' => $quiz->id]) }}" method="POST">
                                            @csrf
                                            <div class="col-sm-12">
                                                <label style="opacity: 0">xx</label>
                                                <button type="submit" class="btn btn-danger form-control">Remove
                                                    All</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="card-body p-3 bg-white">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Categorie</th>
                                    <th>Answers</th>
                                    <th>Comment if wrong answer</th>
                                    <th colspan="4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quiz->questions as $item)
                                    <tr>
                                        <td title="{{ $item->question->name }}">{!! Str::limit($item->question->name, 70, '...') !!}</td>
                                        <td>{{ $item->question->question_type?->name }}</td>
                                        <td>{{ $item->question->questions_categorization?->name }}</td>
                                        <td>
                                            <a href="{{ route('question.show', ['id' => $item->question->id]) }}"
                                                class=" text-nowrap">
                                                <i class="fas fa-eye"></i>
                                                {{ count($item->question->options) }} answers
                                            </a>
                                        </td>
                                        <td title="{{ $item->question->error }}">{!! Str::limit($item->question->error, 70, '...') !!}</td>
                                        <td>

                                            <form method="POST"
                                                action="{{ route('quiz.duplicate-question', ['id' => $quiz->id, 'qst_id' => $item->question->id]) }}"
                                                class="d-inline-block">
                                                @csrf
                                                <button class="btn btn-secondary">
                                                    <i class="fas fa-copy"></i>
                                                    Duplicate
                                                </button>
                                            </form>
                                        </td>

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
                                        <td>
                                            @if ($item->question->image)
                                                <form method="POST"
                                                    action="{{ route('quiz.remove-question-img', ['id' => $item->question->id]) }}"
                                                    class="d-inline-block">
                                                    @csrf
                                                    <button class="btn btn-secondary">
                                                        Remove image
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <div class="modal fade" id="modal-delete-{{ $item->id }}" aria-modal="true"
                                            role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Delete question</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('quiz.delete-question', ['id' => $item->question->id]) }}">
                                                            <div class="row">
                                                                @csrf
                                                                <div class="col-12">
                                                                    Are you sure delete {{ $item->question->name }}?
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
                                                        <h4 class="modal-title">Update quiz</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" enctype="multipart/form-data"
                                                            action="{{ route('quiz.update-question', ['id' => $item->question->id]) }}">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label>Type</label>

                                                                        <select name="type" id="type"
                                                                            class="form-control">
                                                                            <option value="" disabled selected>
                                                                            </option>
                                                                            @foreach ($types as $type)
                                                                                <option value="{{ $type->id }}"
                                                                                    {{ $item->question->question_type_id == $type->id ? 'selected' : '' }}>
                                                                                    {{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Answer</label>
                                                                        <textarea class="form-control" name="name" cols="30" rows="5">{{ $item->question->name }}</textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Comment if wrong answer</label>
                                                                        <textarea class="form-control" name="error" cols="30" rows="5">{{ $item->question->error }}</textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>upload image</label>
                                                                        <input type="file" class="form-control"
                                                                            name="image">
                                                                        <img src="{{ asset('images/question/' . $item->image) }}"
                                                                            width="150" class="mt-3 rounded"
                                                                            alt="imgg">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>categorie {{ $item->categorie_id }}</label>
                                                                        <select name="categorie" class="form-control">
                                                                            @foreach ($categories as $categorie)
                                                                                <option value="{{ $categorie->id }}"
                                                                                    {{ $categorie->id == $item->question->categorie_id ? 'selected' : '' }}>
                                                                                    {{ $categorie->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>



                                                                <div class="col-12 d-flex justify-content-end gap-5">
                                                                    <button type="button" class="btn btn-default mr-3"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Update</button>
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
