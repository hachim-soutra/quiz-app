@extends('layouts.app')

@section('style')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formation</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item "><a href="{{ route('formation.index') }}">Formations</a></li>
                            <li class="breadcrumb-item active">Update Formation</li>
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
                                    Update formation
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('formation.update', ['formation' => $formation]) }}"
                                    enctype="multipart/form-data">
                                    <div class="row">
                                        @csrf
                                        @method('put')
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Title</label>
                                                <input type="text" name="title"
                                                    value="{{ old('title') ? old('title') : $formation->title }}"
                                                    class="form-control @error('title') is-invalid @enderror">
                                                @error('title')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <input type="text" name="description"
                                                    value="{{ old('description') ? old('description') : $formation->description }}"
                                                    class="form-control @error('description') is-invalid @enderror">
                                                @error('description')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Image</label>
                                                <input type="file" name="image"
                                                    class="form-control @error('image') is-invalid @enderror">
                                                @error('image')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>quizzes :</label>
                                                <select name="select_quizzes[]"
                                                    class="form-control select2 select2-hidden-accessible" multiple=""
                                                    data-placeholder="Select a quiz" style="width: 100%;" tabindex="-1"
                                                    aria-hidden="true">
                                                    @foreach ($quiz as $item)
                                                        @foreach ($formation->quizzes as $formation_quiz)
                                                            <option value="{{ $item->id }}"
                                                                @if ($item->id == $formation_quiz->id) selected @endif>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end gap-5">
                                            <button type="submit" class="btn px-5 btn-primary">Update formation </button>
                                        </div>
                                    </div>
                                </form>
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

            $('.select2').select2({
                closeOnSelect: false
            });

        });
    </script>
@endsection
