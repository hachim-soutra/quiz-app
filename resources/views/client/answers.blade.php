@extends('layouts.app')
@section('style')
    <style>
        div.dt-buttons .dt-button,
        .dt-button:hover:not(.disabled) {
            overflow: hidden;
            text-overflow: ellipsis;
            border: none;
            border-radius: 20px;
            background-color: #343b7c !important;
            color: white !important;
        }

        .button-show {
            background-color: #343b7c;
            color: white;
            border-radius: 5px;
        }

        .button-show:hover {
            color: white;
        }

        .progress {
            height: 10px !important;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
@endsection
@section('content')
    <div class="container-fluid">
        <section class="content-header mb-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Answers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Answers</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-white p-4 border-0">
                            <div class="card-body table-responsive p-0">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-outline-danger mb-3" id="deleteAllSelectedRecords">
                                        <i class="fas fa-trash" style="margin-right: 7px;"></i>Delete selected rows</button>
                                </div>
                                <div id="btn-place" style="display: inline;"></div>
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all_ids"></th>
                                            <th>Quiz name</th>
                                            <th>Email</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($answers as $answer)
                                            <tr>
                                                <td><input type="checkbox" value="{{ $answer->id }}" name="ids"
                                                        class="checkbox_ids"></th>
                                                <td>{{ Str::limit($answer->quiz?->name, 70, '...') }}</td>
                                                <td>{{ Str::limit($answer->email, 70, '...') }}</td>
                                                <td class="d-flex flex-column">
                                                    <div class="text-center">{{ $answer->score }}%</div>
                                                    <div class="progress progress-xxs">
                                                        <div class="progress-bar progress-bar-danger progress-bar-striped"
                                                            role="progressbar" aria-valuenow="{{ $answer->score }}"
                                                            aria-valuemin="0" aria-valuemax="100"
                                                            style="width: {{ $answer->score }}%; background-color: {{ $answer->score > 50 ? '#0d5db3' : ($answer->score < 50 ? '#b50d0d' : '#79828d') }}">
                                                            <span class="sr-only">{{ $answer->score }}% Complete
                                                                (warning)
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $answer->created_at }}</td>
                                                <td><a href="{{ route('answer', ['token' => $answer->token]) }}"
                                                        class="btn button-show">
                                                        <i class="fas fa-eye mr-1"></i>Show</a>
                                                </td>
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ],
                // renderer: "jqueryui",
            });
            $('#btn-place').html(table.buttons().container());
            console.log('cc');

            $("#select_all_ids").click(function() {
                console.log('hey');
                $('.checkbox_ids').prop('checked', $(this).prop('checked'));
            });
            $('#deleteAllSelectedRecords').click(function(e) {
                e.preventDefault();
                var all_ids = [];
                $('input:checkbox[name=ids]:checked').each(function() {
                    all_ids.push($(this).val());
                });
                var obj = $.confirm({
                    title: 'Confirm!',
                    content: `Are you sure you want delete ${all_ids.length} answer(s) ?  `,
                    confirmButtonClass: 'btn-info float-right px-3',
                    cancelButtonClass: 'btn-danger mr-2',
                    confirmButton: 'Ok',
                    confirm: function() {
                        $.ajax({
                            "type": "POST",
                            "url": "{{ route('client.answer.destroy') }}",
                            success: function(result) {
                                location.reload();
                            },
                            "data": {
                                _token: '{{ csrf_token() }}',
                                item: all_ids
                            },
                        });
                    },
                    cancel: function() {}
                });
                obj.$el.find('.jconfirm-box').css({
                    'top': '150%',
                    'left': '50%',
                    'margin-top': '-43px',
                    'margin-left': '0px'
                });
            });
        });
    </script>
@endsection