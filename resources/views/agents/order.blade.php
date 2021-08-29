@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

<div class="container">

    <!-- /.card -->
    <div class="card">
        <!-- /.card-header -->
        <div class="card-header">
            <h3 class="card-title">Order Details</h3>
        </div>
        <!-- /.card-body -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Phone Number</th>
                        <th>Amount</th>
                        <th>Waiting minute</th>
                        <th>Currency Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{$order->app_user->phone_number}}</td>
                        <td>{{$order->amount}}</td>
                        <td>{{$order->minute}}</td>
                        <td>{{$order->stock_rate}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>
@endsection