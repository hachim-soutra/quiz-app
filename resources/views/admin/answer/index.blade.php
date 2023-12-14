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
                            <button class="btn btn-secondary mb-3" id="deleteAllSelectedRecords">Delete selected
                                row</button>
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
                                            <td>{{ $answer->quiz?->name }}</td>
                                            <td>{{ $answer->email }}</td>
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
    </div>
    </section>

    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var table = $('#myTable').DataTable();
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
                    confirmButtonClass: 'btn-info',
                    cancelButtonClass: 'btn-danger',    
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
    </script>
@endsection
