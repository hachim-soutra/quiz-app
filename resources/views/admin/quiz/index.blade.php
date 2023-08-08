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
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-default">
                                        Add
                                    </button>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    @if ($item->is_published)
                                                        <span class="tag tag-success">Approved</span>
                                                    @else
                                                        <span class="tag tag-danger">Approved</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('quiz.show', ['quiz' => $item]) }}"
                                                        class="btn btn-success">Add
                                                        question</a>
                                                    <a href="{{ route('quiz.edit', ['quiz' => $item]) }}"
                                                        class="btn btn-primary">Update</a>
                                                    <button type="button" class="btn btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                        <form method="POST" action="{{ route('quiz.store') }}">
                            <div class="row">
                                <div class="col-sm-12">
                                    @csrf
                                    <div class="form-group">
                                        <label>Text</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter ...">
                                    </div>
                                </div>

                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label>Textarea</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
