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
                        <li class="breadcrumb-item "><a href="{{ route('quiz.index') }}">Quiz</a></li>
                        <li class="breadcrumb-item active">update Quiz</li>
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
                                Update Quiz
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="POST"
                            action="{{ route('quiz.update', ['quiz' => $item]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Quiz type</label>
                                        <select name="quiz_type" id="quiz_type" value={{$item->quiz_type}}
                                            class="form-control @error('quiz_type') is-invalid @enderror">
                                            {{-- <option value=""></option> --}}
                                            <option value="1" {{ old('quiz_type') == '1' ? 'selected' : '' }}>
                                                simple quiz</option>
                                            <option value="2" {{ old('quiz_type') == '2' ? 'selected' : '' }}>
                                                test quiz</option>
                                            <option value="3" {{ old('quiz_type') == '3' ? 'selected' : '' }}>
                                                simuler quiz</option>
                                        </select>
                                        @error('quiz_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Text <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('name') is-invalid @enderror" name="name" rows="3">
                                            {{ $item->name }}
                                        </textarea>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="3">{{ $item->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label>upload image</label>
                                        <input type="file" class="form-control"
                                            name="image">
                                        <img src="{{ asset('images/' . $item->image) }}"
                                            width="150" class="mt-3 rounded">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Select timer</label>
                                        <input type="time" name="quiz_time"
                                            class="form-control @error('quiz_time') is-invalid @enderror"
                                            value="{{ $item->quiz_time }}">
                                        @error('quiz_time')
                                            <div class="text-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Warning select
                                            timer</label>
                                        <input type="time" name="quiz_time_remind"
                                            value="{{ $item->quiz_time_remind }}"
                                            class="form-control @error('quiz_time_remind') is-invalid @enderror" />
                                        @error('quiz_time_remind')
                                            <div class="text-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-5">
                                    <button type="button"
                                        class="btn btn-default mr-3"
                                        data-dismiss="modal">Close</button>
                                    @if($item->quiz_time)
                                        <a href="{{ route('quiz.timer', ['id' => $item->id]) }}" type="submit"
                                            class="btn btn-danger mr-3">Remove Timer</a>
                                    @endif
                                    <button type="submit"
                                        class="btn btn-primary">Update</button>
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
