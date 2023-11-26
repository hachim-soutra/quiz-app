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
                                    <a href="{{ route('quiz.add') }}" type="button" class="btn btn-success"
                                        style="margin-right: 13px;">
                                        Add
                                    </a>

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
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Questions</th>
                                            <th colspan="4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>
                                                    @if (!$item->isFirstInOrder())
                                                        <a
                                                            href="{{ route('quiz.order', ['type' => 'up', 'id' => $item->id]) }}">
                                                            <i class="fas fa-arrow-up" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if (!$item->isLastInOrder())
                                                        <a
                                                            href="{{ route('quiz.order', ['type' => 'down', 'id' => $item->id]) }}">
                                                            <i class="fas fa-arrow-down" aria-hidden="true"></i>
                                                        </a>
                                                    @endif

                                                </td>
                                                <td title="{{ $item->name }}">{!! Str::limit($item->name, 70, '...') !!}</td>
                                                <td title="{{ $item->description }}">{!! Str::limit($item->description, 70, '...') !!}</td>
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

                                                    <a href="{{ route('quiz.edit', ['quiz' => $item]) }}"
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
                                        <a href="{{ url('/excel/quiz.csv') }}">excel example file</a>
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
