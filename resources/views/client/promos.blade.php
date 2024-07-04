@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Promos</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Promos</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    @foreach ($promos as $promo)
                        <div class="col-md-4 px-0 d-flex justify-content-center">
                            <div class="card card-rounded">
                                <img class="card-img-top"
                                    src="{{ $promo->image == 'blank.png' ? asset('images/promo.png') : asset('images/' . $promo->image) }}"
                                    alt="Card image cap">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon text-lg text-capitalize bg-secondary"> Promotion </div>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="card-title text-center">{{ Str::limit($promo->title, 100, '...') }}</h5>
                                    <div class="price-desc">
                                        <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                        {{ $promo->price }} $
                                    </div>
                                    {{-- send id of promo's quizzes in query to show them in quizzes blade --}}
                                    @php
                                        $params = [];
                                        foreach ($promo->quizzes as $quiz) {
                                            $params['ids'][] = $quiz->id;
                                        }
                                        // Convert the array to a query string
                                        $query = http_build_query($params);
                                    @endphp
                                    @if ($promo->product->orders->count())
                                        <a href="{{ route('client.quizzes') }}?{{ $query }}"
                                            class="btn d-block button-access button-color">Access now</a>
                                    @else
                                        <a @if ($promo->price_token) href="{{ route('checkout', ['price_token' => $promo->price_token, 'product_id' => $promo->product->id, 'product_type' => $promo->product->productable_type, 'query' => $query ])}}" target="_blank" @endif
                                            class="btn d-block button-access button-color">Buy now</a>
                                    @endif
                                </div>
                                <div class="overlay justify-content-start align-items-start px-3 pt-3">
                                    <div class="xx">
                                        <div class="description"> {{ Str::limit($promo->description, 200, '...') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
        <a id="back-to-top" href="#" class="btn button-color back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
@endsection
