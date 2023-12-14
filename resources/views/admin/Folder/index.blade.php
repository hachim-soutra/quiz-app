@extends('layouts.app')
@section('style')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    @if (session('errors'))
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <div class="container-fluid">
        <section class="content-header">
            <div class="container-flui">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Folder</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Folder</li>


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
                                    Folder
                                </div>
                                <div class="card-tools">
                                    <a type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-add">Add</a>
                                </div>
                            </div>
                            <div class="card-body table-responsive px-2">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>label</th>
                                            <th>quizzes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($folders as $folder)
                                            <tr>
                                                <td>{{ $folder->label }}</td>
                                                <td>{{ count($folder->quizzes) }}</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal-update-{{ $folder->id }}"
                                                        class="btn btn-primary">Update</a>

                                                    <a data-toggle="modal" data-target="#modal-delete-{{ $folder->id }}"
                                                        class="btn btn-danger">Delete</a>

                                                    <div class="modal fade" id="modal-update-{{ $folder->id }}"
                                                        aria-modal="true" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Update Folder</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST"
                                                                        action="{{ route('folder.update', ['folder' => $folder]) }}"
                                                                        enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            @csrf
                                                                            @method('put')

                                                                            <div class="col-sm-12">
                                                                                <div class="form-group">
                                                                                    <label for="">label :</label>
                                                                                    <input type="text" name="label"
                                                                                        value="{{ old('label') ? old('label') : $folder->label }}"
                                                                                        class="form-control @error('label') is-invalid @enderror">
                                                                                    @error('label')
                                                                                        <div class="text-danger">
                                                                                            {{ $message }}
                                                                                        </div>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label>quizzes :</label>
                                                                                    <select name="quizzes"
                                                                                        class="form-control select2 select2-hidden-accessible"
                                                                                        multiple=""
                                                                                        data-placeholder="Select a State"
                                                                                        style="width: 100%;" tabindex="-1"
                                                                                        aria-hidden="true">
                                                                                        @foreach ($folder->quizzes as $quiz)
                                                                                            <option
                                                                                                value="{{ $quiz->id }}">
                                                                                                {{ $quiz->name }}
                                                                                            </option>
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div> <!-- /.form-group -->
                                                                            </div>
                                                                            <div
                                                                                class="col-12 d-flex justify-content-end gap-5">
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

                                                    <div class="modal fade" id="modal-delete-{{ $folder->id }}"
                                                        aria-modal="true" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete Folder</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST"
                                                                        action="{{ route('folder.destroy', ['folder' => $folder]) }}"
                                                                        enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            @csrf
                                                                            @method('delete')

                                                                            <div class="col-sm-12">
                                                                                Are you sure you want delete
                                                                                {{ $folder->label }} ?
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

                        <div class="modal fade" id="modal-add" aria-modal="true" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add Folder</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('folder.store') }}"
                                            enctype="multipart/form-data">
                                            <div class="row">
                                                @csrf
                                                @method('post')

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="">label</label>
                                                        <input type="text" name="label"
                                                            class="form-control @error('label') is-invalid @enderror">
                                                        @error('label')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
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

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#myTable').DataTable();
            $('.select2').select2({
                closeOnSelect: false
            });
        });
    </script>
@endsection
