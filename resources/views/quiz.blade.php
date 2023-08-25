@extends('layouts.master')

@section('content')
    <div class="container mt-5">

        <div class="d-flex justify-content-center row">

            <div class="col-md-10 col-lg-10">
                <div class="border">
                    <form method="POST" action="{{ route('quiz.create-answer', ['id' => $quiz->id]) }}">
                        <div class="question bg-white border-bottom quiz-info">
                            <img src="{{ asset('images/logo.png') }}" alt="" width="100%" class="cover">
                            <img src="{{ asset('images/logo.png') }}" alt="" width="300px" class="profil">
                            <div class="d-flex flex-column justify-content-between px-2 user-info">
                                <h2>{{ $quiz->name }}</h2>
                                <p>{{ $quiz->description }}</p>
                            </div>
                        </div>
                        <div class="question bg-white p-3 border-bottom">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <label for="email">email :</label>

                                    </td>
                                    <td>
                                        <input type="email" id="email" name="email" class="form-control">

                                    </td>
                                </tr>
                            </table>

                        </div>

                        @csrf

                        <div class="d-flex flex-row justify-content-end align-items-center p-3 bg-white">
                            <button class="btn btn-primary border-success align-items-center btn-success"
                                type="submit">Next<i class="fa fa-angle-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
