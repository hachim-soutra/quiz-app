@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Questions Categorization</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ route('categorie.index') }}">Categorie</a></li>
                        <li class="breadcrumb-item active">Add categorie</li>
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
                                Add categorie
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{route('categorie.store')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="row ">
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <label for="">name</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="">Select your categorie's color</label>
                                            <input type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="#e17070 ">
                                            @error('color')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-5">

                                        <button type="submit" class="btn px-5 btn-primary">Add new categorie </button>
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
