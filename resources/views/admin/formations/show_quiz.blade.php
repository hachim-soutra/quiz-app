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
                            <div class="card-header">
                                <div class="card-title">
                                    {{ $formation->title }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
