@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-flui">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive px-2">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Client Name</th>
                                            <th>Client Email</th>
                                            <th>Purchase Price</th>
                                            <th>Current Price</th>
                                            <th>Currency</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order->quiz->name }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>{{ $order->user->email }}</td>
                                                <td>{{ $order->amount_stripe }}</td>
                                                <td>{{ $order->current_price }}</td>
                                                <td>{{ $order->currency }}</td>
                                                <td>{{ $order->status }}</td>
                                                <td>{{ $order->updated_at }}</td>
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
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
