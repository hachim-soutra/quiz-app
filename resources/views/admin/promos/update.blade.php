@extends('layouts.app')

@section('style')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
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
                            <li class="breadcrumb-item "><a href="{{ route('promo.index') }}">Promos</a></li>
                            <li class="breadcrumb-item active">Update Promotion</li>
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
                                    Update Promotion
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('promo.update', ['promo' => $promo]) }}"
                                    enctype="multipart/form-data">
                                    <div class="row">
                                        @csrf
                                        @method('put')
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Title</label>
                                                <input type="text" name="title"
                                                    value="{{ old('title') ? old('title') : $promo->title }}"
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
                                                    value="{{ old('description') ? old('description') : $promo->description }}"
                                                    class="form-control @error('description') is-invalid @enderror">
                                                @error('description')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Price</label>
                                                <input type="text" name="price"
                                                    value="{{ old('price') ? old('price') : $promo->price }}"
                                                    class="form-control @error('price') is-invalid @enderror">
                                                @error('price')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Image</label>
                                                <input type="file" name="image"
                                                    class="form-control @error('image') is-invalid @enderror">
                                                {{-- @if ($promo->image == 'blank.png')
                                                    <img src="" alt="No image available">
                                                @else
                                                    <img src="{{ asset('images/' . $promo->image) }}" width="150"
                                                        class="mt-3 rounded" alt="Promo Image">
                                                @endif --}}
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
                                                        @foreach ($promo->quizzes as $promo_quiz)
                                                            <option value="{{ $item->id }}"
                                                                @if ($item->id == $promo_quiz->id) selected @endif>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="checkbox"
                                                    {{ $promo->active ? 'Checked' : '' }}
                                                    id="toggle-state" name="active"
                                                    value=""
                                                    data-toggle="toggle"
                                                    data-on="Active"
                                                    data-off="Not Active"
                                                    data-onstyle="info"
                                                    data-width="110"
                                                    data-offstyle="secondary">
                                            </div>

                                        </div>
                                        <div class="col-12 d-flex justify-content-end gap-5">
                                            <button type="submit" class="btn px-5 btn-primary">Update promotion </button>
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
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

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
