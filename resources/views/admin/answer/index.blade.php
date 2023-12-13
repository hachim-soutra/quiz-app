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
                            <button class="btn btn-secondary mb-3" id="button">Delete selected row</button>
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
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
                                            <td>{{ $answer->id }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#myTable').DataTable();
            table.on('click', 'tbody tr', function(e) {
                e.currentTarget.classList.toggle('selected');
            });
            document.querySelector('#button').addEventListener('click', function() {
                var listOfId = [];
                var i = table.rows('.selected').data().map(function(item) {
                    return listOfId.push(item[0]);
                });

                $.ajax({
                    "type": "POST",
                    "url": "{{ route('answer.destroy') }}",
                    success: function(result) {
                        location.reload();
                    },
                    "data": {
                        _token: '{{ csrf_token() }}',
                        item: listOfId
                    },

                });
            });
        });
    </script>
@endsection
