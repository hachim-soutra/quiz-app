@extends('layouts.app')

@section('style')
    <style>
        .icon .fa-solid {
            font-size: 70px !important;
            top: 20px !important;
        }

        .bg-quizzes {
            background-color: #9eed75 !important;
        }

        .bg-answers {
            background-color: #37408b !important;
        }

        .progress {
            height: 10px;
        }
        a , a:hover {
            color: black;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3> {{ $quiz->where('payement_type', App\Enum\PayementTypeEnum::FREE->value)->count() }}</h3>
                        <p>Free Quizzes</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <a href="{{  route('client.quizzes').'?type='.App\Enum\PayementTypeEnum::FREE->value }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-quizzes">
                    <div class="inner">
                        <h3>{{ $quiz->where('payement_type', App\Enum\PayementTypeEnum::PAYED->value)->count() }}</h3>
                        <p>Paid Quizzes</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <a href="{{ route('client.quizzes').'?type='.App\Enum\PayementTypeEnum::PAYED->value }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-answers">
                    <div class="inner text-white">
                        <h3>{{ $answer->count() }}</h3>
                        <p>Answers</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <a href="{{ route('answers') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">Questions By Categories</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="chart-responsive" style="height: 200px !important;">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="myChart" height="200" ></canvas>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <ul class="chart-legend clearfix">
                                    @php
                                        $combinedData = array_combine($xValues, $barColors);
                                    @endphp

                                    @foreach ($combinedData as $name => $color)
                                        <li><i class="far fa-circle" style="color: {{ $color }}"></i>
                                            {{ $name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title text-bold">Latest Answers</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Quiz</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>More</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($answer as $answer)
                                    <tr>
                                        <td>
                                            <img src="{{ $answer->quiz->image == 'blank.png' ? asset('images/quiz-938x675.png') : asset('images/'.$answer->quiz->image) }}"
                                                class="img-circle img-size-32 mr-2">
                                            {{ $answer->quiz->name }}
                                        </td>
                                        <td>{{ $answer->created_at }}</td>
                                        <td class="d-flex flex-column">
                                            <div class="text-center">{{ $answer->score }}%</div>
                                            <div class="progress progress-xxs">
                                                <div class="progress-bar progress-bar-danger progress-bar-striped"
                                                    role="progressbar" aria-valuenow="{{ $answer->score }}"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    style="width: {{ $answer->score }}%; background-color: {{ $answer->score > 50 ? '#0d5db3' : ($answer->score < 50 ? '#b50d0d' : '#79828d') }}">
                                                    <span class="sr-only">{{ $answer->score }}% Complete (warning)</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('answers') }}" class="text-muted">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">Recently Added Quizzes</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach ($quiz->where('payement_type', App\Enum\PayementTypeEnum::PAYED->value)->take(4) as $quiz)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ $quiz->image == 'blank.png' ? asset('images/quiz-938x675.png') : asset('images/'.$quiz->image) }}" class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{ $quiz->name }}
                                            <span class="badge badge-warning float-right">{{ $quiz->price }} $</span></a>
                                        <span class="product-description">
                                            {{ $quiz->description }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('client.quizzes') . '?type=' . App\Enum\PayementTypeEnum::PAYED->value }}" class="uppercase">View All Quizzes</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: "doughnut",
            data: {
                // labels: @json($xValues),
                datasets: [{
                    backgroundColor: @json($barColors),
                    data: @json($yValues)
                }]
            },
            options: {
                title: {
                    display: false,
                    text: ""
                },
                responsive: false,
                maintaiAspectRatio: false,

            }
        });
    </script>
@endsection
