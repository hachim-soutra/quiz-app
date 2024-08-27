@extends('layouts.app')

@section('style')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                            <li class="breadcrumb-item active">Add Formation</li>
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
                                    Add Formation
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('formation.store') }}" enctype="multipart/form-data">
                                    <div class="row">
                                        @csrf
                                        @method('post')
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Title</label>
                                                <input type="text" name="title"
                                                    value="{{ old('title') ? old('title') : '' }}"
                                                    class="form-control @error('title') is-invalid @enderror">
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <input type="text" name="description"
                                                    value="{{ old('description') ? old('description') : '' }}"
                                                    class="form-control ">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="">Payment type </label>
                                                <select name="payment_type" id="payement_type"
                                                    class="form-control text-capitalize @error('payment_type') is-invalid @enderror">
                                                    @foreach (App\Enum\PayementTypeEnum::cases() as $paymentType)
                                                        <option value="{{ $paymentType }}"
                                                            {{ old('payment_type') === $paymentType->value ? 'selected' : '' }}>
                                                            {{ $paymentType }}</option>
                                                    @endforeach
                                                </select>
                                                @error('payment_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-sm-12 formation_price">
                                            <div class="form-group">
                                                <label for="">Price (€)</label>
                                                <input type="text"
                                                    class="form-control @error('price') is-invalid @enderror"
                                                    name="price">
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Image</label>
                                                <input type="file" name="image" id="imageInput"
                                                    class="form-control @error('image') is-invalid @enderror">
                                                <img src="" width="150" id="imagePreview" alt="formation's image"
                                                    class="mt-3 rounded" style="display: none;">
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Video</label>
                                                <select name="video" class="form-control">
                                                    <option value="" disabled selected></option>
                                                    @foreach (Storage::allFiles('public/videos') as $file)
                                                        <option value="{{ File::basename($file) }}">
                                                            {{ File::name($file) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>quizzes :</label>
                                                <select name="select_quizzes[]"
                                                    class="form-control select2 select2-hidden-accessible @error('price') is-invalid @enderror"
                                                    multiple="" data-placeholder="Select a quiz" style="width: 100%;"
                                                    tabindex="-1" aria-hidden="true">
                                                    @foreach ($quiz as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                @error('select_quizzes')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end gap-5">
                                            <button type="submit" class="btn px-5 btn-primary">Add new formation </button>
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

            function payementProcess() {
                if ($('#payement_type').val() == 'free') {
                    $('.formation_price').hide();
                }
                if ($('#payement_type').val() == 'paid') {
                    $('.formation_price').show();
                }
            }

            payementProcess();

            $('#payement_type').on('change', function() {
                payementProcess();
            });
        });
    </script>
@endsection
