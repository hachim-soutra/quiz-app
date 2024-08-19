@extends('layouts.app')

@section('style')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
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
                        <h1>Promos</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Promos</li>
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
                                    Promos
                                </div>
                                <div class="card-tools">
                                    <a id="btn-add" type="button" class="btn btn-success" href="{{ route('promo.create') }}">Add</a>
                                </div>
                            </div>
                            <div class="card-body table-responsive px-2">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Quizzes</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($promos as $promo)
                                            <tr>
                                                <td class="text-capitalize">
                                                    {!! Str::limit($promo->title, 70, '...') !!}
                                                </td>
                                                <td class="text-capitalize">{!! Str::limit($promo->description, 70, '...') !!}</td>
                                                <td class="text-capitalize">{{ count($promo->quizzes) }}</td>
                                                <td>{{ $promo->price }}</td>
                                                <td>
                                                    <h4>
                                                        @if ($promo->active)
                                                            <span class="badge badge-pill px-3 badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-pill px-3 badge-danger">Not active</span>
                                                        @endif
                                                    </h4>
                                                </td>
                                                <td>
                                                    <a href="{{ route('promo.show-edit', ['promo' => $promo]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i> Update</a>

                                                    <a data-toggle="modal" data-target="#modal-delete-{{ $promo->id }}"
                                                        class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete</a>
                                                    <div class="modal fade" id="modal-delete-{{ $promo->id }}"
                                                        aria-modal="true" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete Promotion</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="POST"
                                                                        action="{{ route('promo.destroy', ['promo' => $promo]) }}"
                                                                        enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            @csrf
                                                                            @method('delete')

                                                                            <div class="col-sm-12">
                                                                                Are you sure you want delete
                                                                                {{ $promo->title }} ?
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
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#myTable').DataTable();
            $('.select2').select2({
                closeOnSelect: false
            });

            $('#toggle-state').val($('#toggle-state').prop('checked'));
            $('#toggle-state').change(function() {
                var isChecked = $(this).prop('checked');
                console.log('Checked:', isChecked);
                $(this).val(isChecked);
                console.log('Value:', $(this).val());
            })
        });

        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
