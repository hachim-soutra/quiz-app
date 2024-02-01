@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quizzes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Quizzes</li>
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
                            <div class="card-body table-responsive px-2">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quizzes as $quiz)
                                            <tr>
                                                <td>{{ Str::limit($quiz->name,60, '...' )}}</td>
                                                <td>{{ Str::limit($quiz->description, 60, '...') }}</td>
                                                @if($quiz->orders->count())
                                                    <td>{{$quiz->orders[0]->quiz_id == $quiz->id ? 'paid' : ''}}</td>
                                                    <td>{{ $quiz->price ? $quiz->price.'$' : App\Enum\PayementTypeEnum::FREE }}</td>
                                                    <td><a href="{{ route('quiz', ['slug' => $quiz->slug]) }}"
                                                        class="btn btn-primary">Access Now</a></td>
                                                @else
                                                    <td>{{ $quiz->payement_type }}</td>
                                                    <td>{{ $quiz->price ? $quiz->price.'$' : App\Enum\PayementTypeEnum::FREE }}</td>
                                                    <td>
                                                        @if($quiz->payement_type == 'free')
                                                            <a href="{{ route('quiz', ['slug' => $quiz->slug]) }}"
                                                            class="btn btn-primary">Access Now</a>
                                                        @else
                                                            <a @if ($quiz->price_token) href="{{ route('checkout', ['price_token' => $quiz->price_token, 'quiz_id' => $quiz->id]) }}" @endif
                                                                class="btn btn-primary w-75">Buy Now</a>
                                                        @endif
                                                    </td>
                                                @endif
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
