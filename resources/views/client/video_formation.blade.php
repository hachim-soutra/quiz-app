@extends('layouts.app')

@section('style')
<style>
    video {
        object-fit: cover;
        width: 100%;
    }
    .next-button
    {
        background-color: #cfe2f3;
        width: 12%;
        font-weight: 600;
    }

</style>
@endsection

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formations</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Formations</li>
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
                            <div class="card-body">
                                @if($formation->video)
                                <video height="500" controls>
                                    <source src="{{ asset('storage/' . $formation->video) }}" type="video/mp4">
                                </video>
                                @endif
                                
                                <div class="d-flex flex-column">
                                    <h3 class="card-title mt-4 text-uppercase" style="text-decoration: underline;">
                                        {{ $formation->title }}
                                    </h3>
                                    <div class="mb-3">
                                        {{ $formation->description }}
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('client.formation.quiz', ['id' => $formation->id]) }}" class="btn float-right next-button mt-2 ">
                                        Enroll <i class="fa-solid fa-arrow-right ml-1" aria-hidden="true"></i>
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
