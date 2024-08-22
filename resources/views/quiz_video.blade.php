@extends('layouts.master')

@section('style')
    <style>
        .video {
            object-fit: cover;
            height: 391px;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">
            <div class="mt-3 d-flex flex-column " style="gap: 20px;">
                <div class="d-flex flex-column justify-content-between px-2">
                    <h2 class="text-deco">
                        {{ $answer->quiz->name }}
                    </h2>
                    <p class="sous-title">{{ $answer->quiz->description }}</p>
                </div>
                <div class="d-flex flex-row justify-content-center align-items-center bg-white">
                    <video class="w-100 " controls  style="height: 500px; object-fit: cover;">
                        <source src="{{ asset('storage/' . $answer->quiz->video) }}" type="video/mp4">
                    </video>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center py-3 bg-white gap-2">
                    <a  href="{{ route('questions', ['token' => $answer->token, 'id' => $id ]) }}" class="btn border-success btn-success">
                        <i class="fa fa-check ml-3" aria-hidden="true"></i> Next</a>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
