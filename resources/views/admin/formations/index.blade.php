@extends('layouts.app')

@section('style')
    <style>
        table.dataTable thead th {
            padding: 8px 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formations</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Formations</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Formations
                                </div>
                                <div class="card-tools">
                                    <a id="btn-add" type="button" class="btn btn-success"
                                        href="{{ route('formation.create') }}">Add</a>
                                </div>
                            </div>
                            <div class="card-body table-responsive px-2">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Quizzes</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($formations as $formation)
                                            <tr>
                                                <td class="text-capitalize">
                                                    {!! Str::limit($formation->title, 70, '...') !!}
                                                </td>
                                                <td class="text-capitalize">{!! Str::limit($formation->description, 70, '...') !!}</td>
                                                <td class="text-capitalize">{{ count($formation->quizzes) }}</td>
                                                <td>
                                                    @if ($formation->video)
                                                        <a target="_blank"
                                                            href="{{ route('formation.show', ['formation' => $formation ]) }}"
                                                            class="btn btn-success">
                                                            <i class="fas fa-eye" aria-hidden="true"></i> show</a>
                                                    @else
                                                        <a target="_blank"
                                                            href="{{ route('formation.quiz', ['id' => $formation->id ]) }}"
                                                            class="btn btn-success">
                                                            <i class="fas fa-eye" aria-hidden="true"></i> show</a>
                                                    @endif


                                                    <a href="{{ route('formation.edit', ['formation' => $formation]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i> Update</a>

                                                    <a data-toggle="modal" data-target="#modal-delete-{{ $formation->id }}"
                                                        class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete</a>
                                                    <div class="modal fade" id="modal-delete-{{ $formation->id }}"
                                                        aria-modal="true" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete formation</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST"
                                                                        action="{{ route('formation.destroy', ['formation' => $formation]) }}"
                                                                        enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            @csrf
                                                                            @method('delete')

                                                                            <div class="col-sm-12">
                                                                                Are you sure you want delete
                                                                                {{ $formation->title }} ?
                                                                            </div>
                                                                            <div
                                                                                class="col-12 d-flex justify-content-end gap-5">
                                                                                <button type="button"
                                                                                    class="btn btn-default mr-3"
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

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
