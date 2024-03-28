@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0" id="answer">
        <img src="{{ asset('images/' . $answer->quiz->image) }}" alt="" width="100%" class="cover border-bottom p-0">
        <div class="col-md-10 col-lg-10">
            <img src="{{ asset('images/' . $logo->value) }}" alt="" width="300px" class="profil">
            <div class="">
                <div class="d-flex flex-column align-items-center justify-content-between px-2 mb-5">
                    @if ($answer->email)
                        <h2 class="text-deco">User email : {{ $answer->email }}</h2>
                    @endif
                    @if ($answer->score < intval($answer->target))
                        <p class="text-review">
                            {{ $below_target }}
                            <br />
                            Here below a quick summary of your assessment
                        </p>
                    @else
                        <p class="text-review">
                            {{ $above_target }}
                            <br />
                            Here below a quick summary of your assessment
                        </p>
                    @endif
                    <p class="fw-bold mb-0">Score :
                        <span
                            class="{{ $answer->score >= intval($answer->target) ? 'text-success' : 'text-danger' }}">{{ round($answer->score, 2) }}%
                            correct ({{ $answer->nbr_of_correct }} / {{ count($answer->questions_json) }})</span>
                    </p>
                    @if ($answer->status && $answer->status !== 'good')
                        <p class="status-text mb-0 mt-2">Status : {{ $answer->status }}</p>
                    @endif
                </div>

                <div class="row d-flex my-5 ">
                    <div class="col-6" id="chartdiv" style="height: 400px;"></div>
                    <div class="col-6" id="chartmixeddiv" style="height: 400px;"></div>
                </div>


                <h2><strong class="text-deco">Quiz </strong>: {{ $answer->quiz->name }}</h2>

                @csrf
                @foreach ($answer->getQuestions()->sortBy('sort') as $question)
                    <div class="question bg-white p-3 border-bottom">
                        <div class="d-flex flex-row align-items-center question-title">
                            <h3 class="mt-1 ml-2">{{ $question['name'] }}</h3>
                        </div>
                        @if ($question['image'])
                            <img src="{{ asset('images/question/' . $question['image']) }}" width="40%" height="auto"
                                class="mt-3 rounded" alt="imgg">
                            <br>
                            <br>
                        @endif
                        @if ($question['type'] === 'row answers')
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach ($question['options'] as $option)
                                            <th> {{ $option['name'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($question['options'] as $optionl)
                                        <tr
                                            class="{{ is_array($question['value']) && $question['value'][$question['id']][$optionl['id']] === $optionl['value'] ? 'bg-success-1' : 'bg-danger-1' }}">
                                            @foreach ($question['options'] as $k => $option)
                                                @if ($loop->first)
                                                    <td>{{ $optionl['value'] }}</td>
                                                @endif
                                                <td>
                                                    <input required type="radio" value="{{ $option['value'] }}"
                                                        {{ is_array($question['value']) && $question['value'][$question['id']][$optionl['id']] === $option['value'] ? 'checked' : '' }}>
                                                    {{ $option['value'] }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if (!is_array($question['value']) || count(array_diff($question['value'][$question['id']], $question['corrects'])) > 0)
                                <br>
                                <strong class="text-danger ms-3">
                                    {{ $question['error'] }}
                                </strong>
                                <br>
                            @endif
                        @else
                            @foreach ($question['options'] as $option)
                                <div class="ans ml-2">
                                    <label
                                        class="radio {{ is_array($question['value']) && in_array($option['id'], $question['value']) && $option['is_correct'] == 0 ? 'text-danger' : '' }}
                                        {{ $option['is_correct'] == 1 ? 'text-success' : '' }} ">
                                        <input disabled type="radio" name="question[{{ $option['id'] }}]" value="1"
                                            {{ is_array($question['value']) && in_array($option['id'], $question['value']) ? 'checked' : '' }}>
                                        <span>{{ $option['name'] }}</span>
                                    </label>
                                </div>
                            @endforeach
                            @if (!is_array($question['value']) || count(array_diff($question['value'], $question['corrects'])) > 0)
                                <br>
                                <strong class="text-danger ms-3">
                                    {{ $question['error'] }}
                                </strong>
                                <br>
                            @endif
                        @endif
                    </div>
                @endforeach

                @if (auth()->check() && auth()->user()->userable_type == \App\Models\User::CLIENT_TYPE)
                    <a href="{{ route('client.home') }}" class="btn text-white float-right my-3"
                        style="background-color: #343b7c; float: right;">Back to dashboard</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            var answer = @json($answersByCatego);
            var questions = @json($allQstByCatego);
            const values = Object.values(questions).map(function(x, index) {

                return Object.values(answer)[index] * 100 / x
            });
            am4core.ready(function() {

                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                var chart = am4core.create("chartdiv", am4charts.PieChart3D);
                chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

                // disable chart logo
                if (chart.logo) {
                    chart.logo.disabled = true;
                }

                chart.legend = new am4charts.Legend();

                chart.data = [{
                        answers: "Correct",
                        pourcentage: {{ $answer->nbr_of_correct }},
                        color: "{{ $correct_color }}"
                    },
                    {
                        answers: "Incorrect",
                        pourcentage: {{ $answer->nbr_of_incorrect }},
                        color: "{{ $incorrect_color }}"
                    },
                    {
                        answers: "Ignored",
                        pourcentage: {{ $answer->nbr_of_ignored }},
                        color: "{{ $ignored_color }}"
                    }
                ];

                var series = chart.series.push(new am4charts.PieSeries3D());
                series.dataFields.value = "pourcentage";
                series.dataFields.category = "answers";
                series.slices.template.propertyFields.fill = "color";

                // mixed chart
                var chart2 = am4core.create("chartmixeddiv", am4charts.XYChart3D);

                // disable chart logo
                if (chart2.logo) {
                    chart2.logo.disabled = true;
                }

                // Add data
                chart2.data = [];
                var chartData = chart2.data;
                for (var i = 0; i < Object.keys(answer).length; i++) {
                    var newValues = {
                        "category": Object.keys(answer)[i],
                        "value1": values[i],
                        "value2": {{ $answer->target }}
                    }
                    chartData.push(newValues);
                }

                // Create axes
                var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "category";

                var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());

                // Create series
                var columnSeries = chart2.series.push(new am4charts.ColumnSeries3D());
                columnSeries.dataFields.valueY = "value1";
                columnSeries.dataFields.categoryX = "category";
                columnSeries.name = "Categories";
                columnSeries.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";

                // Function to set color for each column based on its value
                function columnsColor(dataItem) {
                    if (dataItem < chartData[0].value2) {
                        return "{{ $color_below_target }}";
                    } else {
                        return "{{ $color_above_target }}";
                    }
                }

                // Set column fill color
                columnSeries.columns.template.adapter.add("fill", function(fill, target) {
                    return am4core.color(columnsColor(target.dataItem.valueY));
                });

                // Create series
                var lineSeries = chart2.series.push(new am4charts.LineSeries());
                lineSeries.dataFields.valueY = "value2";
                lineSeries.dataFields.categoryX = "category";
                lineSeries.name = "Target";
                lineSeries.strokeWidth = 3;
                lineSeries.stroke = am4core.color("#dc3545");
                lineSeries.bullets.push(new am4charts.CircleBullet());
                lineSeries.tooltipText = "{categoryX}: [bold]{valueY}[/]";


                // Add legend
                chart2.legend = new am4charts.Legend();
                // chart.legend.data = [{
                //     "name": "categories",
                //     "fill": am4core.color("#28a745")
                // }];

                // Add cursor
                chart2.cursor = new am4charts.XYCursor();

            });
        });
    </script>
@endsection
