@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>

            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background-color: #351c75ff !important">
                        <div class="inner text-white">
                            <h3>{{ App\Models\User::count() }}</h3>
                            <p>Admins</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ route('quiz.index') }}" class="small-box" style="background-color: #343b7cff !important">
                        <div class="inner text-white">
                            <h3>{{ Harishdurga\LaravelQuiz\Models\Quiz::count() }}</h3>
                            <p>Quizzes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box" style="background-color: #ccccccff !important">
                        <div class="inner">
                            <h3>{{ Harishdurga\LaravelQuiz\Models\Question::count() }}</h3>
                            <p>Questions</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <a href="{{ route('admin.answer') }}" class="small-box" style="background-color: #cfe2f3ff !important">
                        <div class="inner text-dark">
                            <h3>{{ App\Models\Answer::count() }}</h3>
                            <p>Answers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>

                    </a>
                </div>

            </div>
            <div class="row justify-content-center mt-5">
                    <div class="h-auto" style="width:600px;">
                        <canvas id="myChart" ></canvas>
                    </div>
            </div>

        </div>
    </section>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
        var xValues = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];
        var yValues = [55, 49, 44, 24, 15];
        var barColors = [
            "#6f42c1",
            "#00aba9",
            "#2b5797",
            "#e8c3b9",
            "#fd7e14"
        ];
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                    borderWidth: 10
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "xsqnxjsqnxjqsnxjs"
                }
            }
        });
    </script>
@endsection
