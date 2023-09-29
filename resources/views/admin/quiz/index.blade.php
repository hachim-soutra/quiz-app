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
                            <li class="breadcrumb-item active">Quiz</li>
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
                                    Quiz list
                                </h3>
                                <div class="card-tools">
                                    <form action="{{ route('quiz.index') }}" method="GET" class="d-flex ms-3">
                                        <input type="text" name="search" class="form-control" placeholder="Search"
                                            value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default" style="margin-right: 13px;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                    </form>
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-default" style="margin-right: 13px;">
                                        Add
                                    </button>

                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-import">
                                        import
                                    </button>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Questions</th>
                                            <th colspan="4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    <a href="{{ route('quiz.show', ['quiz' => $item]) }}">

                                                        {{ $item->questions_count }}</a>
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{ route('quiz', ['slug' => $item->slug]) }}"
                                                        class="btn btn-success">
                                                        <i class="fas fa-eye"></i>
                                                        Show</a>
                                                </td>

                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('quiz.duplicate-quiz', ['id' => $item->id]) }}"
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
                                            </tr>
                                            <div class="modal fade" id="modal-delete-{{ $item->id }}" aria-modal="true"
                                                role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete quiz</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="{{ route('quiz.destroy', ['quiz' => $item]) }}">
                                                                <div class="row">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="col-12">
                                                                        Are you sure {{ $item->name }}?
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
                                                            <form method="POST"
                                                                action="{{ route('quiz.update', ['quiz' => $item]) }}"
                                                                enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <label>Text</label>
                                                                            <input type="text" name="name"
                                                                                value="{{ $item->name }}"
                                                                                class="form-control"
                                                                                placeholder="Enter ...">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-12">

                                                                        <div class="form-group">
                                                                            <label>Description</label>
                                                                            <textarea class="form-control" name="description" rows="3">{{ $item->description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">

                                                                        <div class="form-group">
                                                                            <label>upload image</label>
                                                                            <input type="file" class="form-control"
                                                                                name="image">
                                                                            <img src="{{ asset('images/' . $item->image) }}"
                                                                                width="150" class="mt-3 rounded">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 d-flex justify-content-end gap-5">
                                                                        <button type="button"
                                                                            class="btn btn-default mr-3"
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
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex align-items-center justify-content-end p-5">
                                    {{ $data->links() }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modal-default" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add quiz</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('quiz.store') }}" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12">
                                    @csrf
                                    <div class="form-group">
                                        <label>Text</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter ...">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>upload image</label>
                                        <input class="form-control" name="image" type="file">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-5">
                                    <button type="button" class="btn btn-default mr-3"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
        <div class="modal fade" id="modal-import" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add quiz</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('quiz.import') }}" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12">
                                    @csrf
                                    <div class="form-group">
                                        <label>Csv</label>
                                        <input type="file" name="file" class="form-control"
                                            placeholder="Upload ...">
                                        <a href="{{ url('/excel/quiz.xlsx') }}">excel exmeple file</a>
                                    </div>

                                </div>

                                <div class="col-12 d-flex justify-content-end gap-5">
                                    <button type="button" class="btn btn-default mr-3"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
