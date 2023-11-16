@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0">

        @if ($quiz->image === 'blank.png')
            <div class="cover p-0"></div>
        @else
            <img src="{{ asset('images/' . $quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        @endif
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">

            <div class="ms-5 mt-5">
                <form method="POST" action="{{ route('quiz.create-answer', ['id' => $quiz->id]) }}">

                    <div class="d-flex flex-column justify-content-between px-2 mb-5">
                        <h2 class="text-deco">{{ $quiz->name }}</h2>
                        <p class="sous-title">{{ $quiz->description }}</p>
                    </div>
                    <div class="question bg-white p-3">
                        <table width="100%">
                            <tr>
                                <td>
                                    <label for="email">email :</label>
                                </td>
                                <td>
                                    <input type="email" id="email" name="email"
                                        class="@error('email') is-invalid @enderror form-control">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        </table>

                    </div>

                    @csrf

                    <div class="d-flex flex-row justify-content-end align-items-center p-3 bg-white">
                        <button class="btn btn-primary border-success align-items-center btn-success" type="submit">Start
                            Quiz<i class="fa fa-angle-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
