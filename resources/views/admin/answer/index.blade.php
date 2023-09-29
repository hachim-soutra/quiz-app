@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Answers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Answers</li>
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
                                        <form action="{{ route('admin.answer') }}" method="GET" class="d-flex">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($answers as $answer)
                                        <tr>
                                            <td>{{ $answer->quiz?->name }}</td>
                                            <td>{{ $answer->email }}</td>
                                            <td>{{ round($answer->score, 2) }}%</td>
                                            <td>{{ $answer->created_at }}</td>
                                            <td><a href="{{ route('answer', ['token' => $answer->token]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>Show</a></td>
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
