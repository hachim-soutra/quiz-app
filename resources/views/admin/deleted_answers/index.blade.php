@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Deleted Answers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Deleted Answers</li>
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
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <form action="{{ route('answer.deleted-answers') }}" method="GET" class="d-flex">
                                            <input type="text" name="search" class="form-control float-right"
                                                placeholder="Search" value="{{ isset($search) ? $search : '' }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Quiz name</th>
                                        <th>Email</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                        <th>Answers</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($answers as $answer)
                                        <tr>
                                            <td class="text-capitalize">{{ $answer->quiz?->name }}</td>
                                            <td>{{ $answer->email }}</td>
                                            <td>{{ round($answer->score, 2) }}%</td>
                                            <td>{{ $answer->created_at }}</td>
                                            <td><a href="{{ route('answer', ['token' => $answer->token]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>Show</a></td>
                                                    <td>
                                                        <a href="{{ route('answer.restore-answer', ['id'=> $answer->id ]) }}"
                                                            class="btn btn-success">Restore</a>
                                                    </td>
                                                    <td>
                                                        <a data-toggle="modal" data-target="#modal-delete-{{ $answer->id }}"
                                                            class="btn btn-danger">Delete</a>
                                                    </td>
                                                    <div class="modal fade" id="modal-delete-{{ $answer->id }}"
                                                        aria-modal="true" role="dialog">
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
                                                                        action="{{ route('answer.permanent-delete', ['id' => $answer->id]) }}"
                                                                        enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            @csrf
                                                                            @method('delete')

                                                                            <div class="col-sm-12 mb-2">
                                                                                Are you sure you want permanently delete
                                                                                {{ $answer->email }} ?
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

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <div class="d-flex align-items-center justify-content-end p-5">
                                {{ $answers->links() }}
                            </div>


                        </div>

                    </div>

                </div>
            </div>
    </div>
    </section>

    </div>
@endsection
