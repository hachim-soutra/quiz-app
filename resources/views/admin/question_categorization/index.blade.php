@extends('layouts.app')

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
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Questions Categorization</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item "><a href="{{ route('categorie.index') }}">Categorie</a></li>
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
                                    categories
                                </h3>
                                <div class="card-tools">
                                    <a href="{{ route('categorie.create') }}" type="button" class="btn btn-success"
                                        style="margin-right: 13px;">
                                        Add
                                    </a>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>name</th>
                                            <th>color</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $categorie)
                                            <tr>
                                                <td>{{ $categorie->id }}</td>
                                                <td title="{{ $categorie->name }}">{!! Str::limit($categorie->name, 70, '...') !!}</td>
                                                <td title="{{ $categorie->color }}">{!! Str::limit($categorie->color, 70, '...') !!}</td>
                                                <td>
                                                    <a type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#modal-update-{{ $categorie->id }}">
                                                        <i class="fas fa-edit"></i>Update</a>
                                                </td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal-delete-{{ $categorie->id }}"
                                                        type="button" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                        Delete</a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-update-{{ $categorie->id }}" aria-modal="true"
                                                role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Update categorie</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="{{ route('categorie.update', ['categorie' => $categorie]) }}">
                                                                <div class="row">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">name</label>
                                                                            <input type="text" name="name"
                                                                                value="{{ $categorie->name }}"
                                                                                class="form-control @error('name') is-invalid @enderror">
                                                                            @error('name')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Select your categorie's
                                                                                color</label>
                                                                            <input type="color" name="color"
                                                                                value="{{ $categorie->color }}"
                                                                                class="form-control @error('color') is-invalid @enderror"
                                                                                value="#e17070 ">
                                                                            @error('color')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
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

                                            <div class="modal fade" id="modal-delete-{{ $categorie->id }}"
                                                aria-modal="true" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete categorie</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="{{ route('categorie.destroy', ['categorie' => $categorie]) }}">
                                                                <div class="row">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="col-12 bp-3">
                                                                        Are you sure you want delete categorie
                                                                        {{ $categorie->name }}?
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
        $(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
