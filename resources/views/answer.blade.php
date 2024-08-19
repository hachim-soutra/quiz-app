@extends('layouts.master')

@section('content')
    <div class="d-flex justify-content-center row w-100 m-0" id="divToExport">
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

                <div class="row d-flex my-5 mx-5" style="height: 300px; width: 88%;">
                    <div class="col-6" id="chartdiv"></div>
                    <div class="col-6" id="chartmixeddiv"></div>
                </div>

                <div class="d-flex w-100 align-items-start justify-content-between">
                    <h2><strong class="text-deco">Quiz </strong>: {{ $answer->quiz->name }}</h2>
                    @if (auth()->check() && auth()->user()->userable_type == \App\Models\User::CLIENT_TYPE)
                        <div style="display: flex; gap: 12px;white-space: nowrap;">
                            <button type="button" class="btn text-white float-right btn-pdf"
                                style="background-color: #343b7c; float: right;" onclick="generatePDF()">
                                <i class="fa-solid fa-print"></i>
                                Export Pdf
                            </button>
                            <a href="{{ route('client.home') }}" class="btn text-white float-right me-3 btn-pdf"
                                style="background-color: #343b7c; float: right;">
                                <i class="fa-solid fa-share"></i>
                                Go to dashboard
                            </a>
                        </div>
                    @endif
                    @if($answer->quiz->formations)
                    <a href="{{ auth()->check() && auth()->user()->userable_type == \App\Models\User::CLIENT_TYPE ? route('client.formation.next-quiz', ['id' => $answer->quiz->id]) : route('formation.next-quiz', ['id' => $answer->quiz->id]) }}" class="btn float-right next-button mt-2 ">
                        Next <i class="fa-solid fa-arrow-right ml-1" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>


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
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
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
                series.startAngle = 0;
                series.endAngle = 360;

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
                lineSeries.tooltipText = "{name}: [bold]{valueY}[/]";

                // Add cursor
                chart2.cursor = new am4charts.XYCursor();

            });
        });

        function generatePDF() {

            buttons = document.querySelectorAll('.btn-pdf');
            // Hide buttons export to pdf and go to dash to not print in pdf
            for (var i = 0; i < buttons.length; i += 1) {
                buttons[i].style.display = 'none';
            }

            // Choose the element id which you want to export.
            var element = document.getElementById('divToExport');
            const {
                width,
                height
            } = document.body.getBoundingClientRect();

            var opt = {
                margin: [20, 0.5, 20, 0.5],
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy']
                },
                filename: 'Quiz recap  {{ $answer->quiz->name }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    onclone: (element) => {
                        const svgElements = Array.from(element.querySelectorAll('svg'));
                        svgElements.forEach(s => {
                            const bBox = s.getBBox();
                            s.setAttribute("x", bBox.x);
                            s.setAttribute("y", bBox.y);
                            s.setAttribute("width", bBox.width);
                            s.setAttribute("height", bBox.height);
                        })
                    }
                },
                jsPDF: {
                    unit: 'pt',
                    format: [1000, 1000],
                    orientation: 'portrait',
                    precision: '12',

                }
            };

            html2pdf().set(opt).from(element).toPdf().get('pdf')
                .then(function(pdf) {
                    var totalPages = pdf.internal.getNumberOfPages();

                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);
                        pdf.setFontSize(10);
                        pdf.setTextColor(150);
                        pdf.setFontType('bold');
                        //Add you content in place of example here
                        pdf.text('https://quizzes.pminlife.com => {{ $answer->quiz->name }}', pdf.internal.pageSize
                            .getWidth() - 20, 15, { align: "right"});
                        pdf.text('page ' + i, pdf.internal.pageSize
                            .getWidth() / 2 - 20, pdf.internal.pageSize.getHeight() -
                            10);
                    }
                    for (var x = 0; x < buttons.length; x += 1) {
                        buttons[x].style.display = 'block';
                    }
                }).save();
        }
    </script>
@endsection
