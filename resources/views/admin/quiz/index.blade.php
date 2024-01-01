@extends('layouts.app')
@section('style')
    <style>
        .hide {
            display: none;
        }

        .bg-tr {
            background-color: #ccccccff;
        }

        thead {
            background-color: #cfe2f3;
            color: black;
        }

        input[type="checkbox"]:checked {
            appearance: none;
            background: url("{{ url('collapse.png') }}") no-repeat left center;
            background-size: 20px;
            padding-left: 25px;
            border: none;
            filter: brightness(0);
            transform: rotate(180deg);
        }

        input[type="checkbox"] {
            appearance: none;
            background: url("{{ url('collapse.png') }}") no-repeat left center;
            background-size: 20px;
            padding-left: 25px;
            border: none;
            filter: brightness(0);
            transform: rotate(0deg);
            width: 20px;
            height: 20px;
        }
    </style>
@endsection

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
                                <div class="card-tools d-flex">
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

                            <div class="card-body table-responsive px-2">
                                <table class="table w-100" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Folder</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($folders as $folder)
                                            <tr class="bg-tr">
                                                <td>
                                                    <input type="checkbox" name="accounting" id="accounting"
                                                        data-toggle="toggle" data-id="{{ $folder->id }}">
                                                    {{ $folder->label }}

                                                    <table width="100%" class="hide hide-{{ $folder->id }}">
                                                        <thead>
                                                            <tr bg="red">
                                                                <th>Title</th>
                                                                <th>Description</th>
                                                                <th>Questions</th>
                                                                <th colspan="4">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($folder->quizzes as $item)
                                                                <tr>
                                                                    <td title="{{ $item->name }}">{!! Str::limit($item->name, 70, '...') !!}
                                                                    </td>
                                                                    <td title="{{ $item->description }}">
                                                                        {!! Str::limit($item->description, 70, '...') !!}</td>
                                                                    <td>
                                                                        <a
                                                                            href="{{ route('quiz.show', ['quiz' => $item]) }}">

                                                                            {{ count($item->questions) }}</a>
                                                                    </td>

                                                                    <td class="white-space">
                                                                        <a target="_blank"
                                                                            href="{{ route('quiz', ['slug' => $item->slug]) }}"
                                                                            class="btn btn-success">
                                                                            <i class="fas fa-eye"></i>
                                                                            Show</a>


                                                                        <form method="POST"
                                                                            action="{{ route('quiz.duplicate-quiz', ['id' => $item->id]) }}"
                                                                            class="d-inline-block">
                                                                            @csrf
                                                                            <button class="btn btn-secondary">
                                                                                <i class="fas fa-copy"></i>
                                                                                Duplicate
                                                                            </button>
                                                                        </form>


                                                                        <a href="{{ route('quiz.edit', ['quiz' => $item]) }}"
                                                                            class="btn btn-primary"><i
                                                                                class="fas fa-edit"></i>Update</a>


                                                                        <a data-toggle="modal"
                                                                            data-target="#modal-delete-{{ $item->id }}"
                                                                            class="btn btn-danger">
                                                                            <i class="fas fa-trash"></i> Delete
                                                                        </a>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
                                        <input type="file" name="file" class="form-control" placeholder="Upload ...">
                                        <a href="{{ url('/excel/quiz.csv') }}">excel example file</a>
                                    </div>

                                </div>

                                <div class="col-12 d-flex justify-content-end gap-5">
                                    <button type="button" class="btn btn-default mr-3" data-dismiss="modal">Close</button>
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
@section('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true
            });
            $(".hide").hide();
            $('[data-toggle="toggle"]').change(function() {
                $(".hide-" + this.dataset.id).toggle();
            });
        });
    </script>
@endsection
