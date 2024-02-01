@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profil Settings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid">
            <div class="card col-10 mx-4 py-4">
                <div class="card-body pl-5">
                    <form class="row d-flex" method="POST" action="{{ route('update-account', auth()->id()) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="">Password</label>
                                <input type="password" class="form-control" value="{{ Auth::user()->password }}">
                            </div>
                            <div class="collapse" id="collapseExample">
                                <div class="mb-4">
                                    <label for="">Enter the new password</label>
                                    <input type="text" class="form-control @error('password') is-invalid @enderror" name="password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="">Confirm the password</label>
                                    <input type="text" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button class="btn btn-primary">Save Changes</button>
                        </div>
                        <div class="col-md-4 align-items-center d-flex">
                            <button class="btn btn-outline-secondary" style="margin-top: 155px;" type="button"
                                data-toggle="collapse" data-target="#collapseExample" aria-expanded="false"
                                aria-controls="collapseExample">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
