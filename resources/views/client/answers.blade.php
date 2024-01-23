@extends('layouts.app')

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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                        <div class="card-body table-responsive p-0">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-danger mb-3" id="deleteAllSelectedRecords">
                                    <i class="fas fa-trash" style="margin-right: 7px;"></i>Delete selected rows</button>
                            </div>
                            <div id="btn-place" style="display: inline;"></div>
                            <table class="table" id="myTable">
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
                                            <td>{{ round($answer->score, 2) }}%</td>
                                            <td>{{ $answer->created_at }}</td>
                                            <td><a href="{{ route('answer', ['token' => $answer->token]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-eye"></i>Show</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            $('#btn-place').html(table.buttons().container());
            $(function(e) {
                $("#select_all_ids").click(function() {
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
                                "url": "{{ route('answer.destroy') }}",
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
        });
    </script>
@endsection
