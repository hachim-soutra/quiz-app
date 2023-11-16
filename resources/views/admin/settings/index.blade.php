@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Settings</li>
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
                                    settings
                                </h3>

                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value</th>
                                            <th colspan="4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($settings as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td><img src="{{ asset('images/' . $item->value) }}" width="150"
                                                        height="auto" class="mt-3 rounded"></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal-update-{{ $item->id }}"
                                                        class="btn btn-primary"><i class="fas fa-edit"></i>Update</a>
                                                </td>

                                            </tr>

                                            <div class="modal fade" id="modal-update-{{ $item->id }}" aria-modal="true"
                                                role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Update Settings</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="{{ route('settings.update', ['setting' => $item]) }}"
                                                                enctype="multipart/form-data">
                                                                <div class="row">
                                                                    @csrf
                                                                    @method('put')

                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="">Image</label>
                                                                            <input type="file" name="value"
                                                                                value="{{ old('value') ? old('value') : $item->value }}"
                                                                                class="form-control">
                                                                            <img src="{{ asset('images/' . $item->value) }}"
                                                                                width="150" class="mt-3 rounded">
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
