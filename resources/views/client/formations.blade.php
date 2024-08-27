@extends('layouts.app')
@section('style')
    <style>
        .btn:hover {
            color: white;
            text-decoration: none;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Formations</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Formations</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    @foreach ($formations as $formation)
                        <div class="col-md-4 px-0 d-flex justify-content-center">
                            <div class="card card-rounded">
                                <img class="card-img-top"
                                    src="{{ $formation->image == 'blank.png' ? asset('images/formation.jpg') : asset('images/' . $formation->image) }}"
                                    alt="Card image cap">
                                <div class="ribbon-wrapper ribbon-lg">
                                    @if ($formation->payment_type == App\Enum\PayementTypeEnum::FREE->value)
                                        <div class="ribbon text-lg text-capitalize bg-info"> {{ $formation->payment_type }}
                                        </div>
                                    @elseif($formation->payment_type == App\Enum\PayementTypeEnum::PAYED->value)
                                        @if ($formation->product?->orders?->count())
                                            <div class="ribbon text-lg text-capitalize bg-success"> Payed </div>
                                        @else
                                            <div class="ribbon text-lg text-capitalize bg-secondary">
                                                {{ $formation->payment_type }} </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="card-title text-center">{{ Str::limit($formation->title, 100, '...') }}</h5>
                                    @if ($formation->payment_type == App\Enum\PayementTypeEnum::PAYED->value)
                                        <div class="price-desc">
                                            <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            {{ $formation->price }} $
                                        </div>
                                    @endif

                                    @if ($formation->payment_type == App\Enum\PayementTypeEnum::PAYED->value && !$formation->product?->orders?->count())
                                        @if ($formation->product)
                                            <a href="{{ route('checkout', ['price_token' => $formation->price_token, 'product_id' => $formation->product->id, 'product_type' => $formation->product->productable_type, 'query' => 'null']) }}"
                                                target="_blank" class="btn d-block button-access button-color">
                                                Buy now
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('client.formation.show', ['formation' => $formation]) }}"
                                            class="btn d-block button-access button-color">Access now</a>
                                    @endif
                                </div>
                                <div class="overlay justify-content-start align-items-start px-3 pt-3">
                                    <div class="xx">
                                        <div class="description"> {{ Str::limit($formation->description, 200, '...') }}
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
