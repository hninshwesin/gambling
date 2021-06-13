@extends('layouts.app')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    <!-- /.card -->
    <div class="card">
        <!-- /.card-header -->
        <div class="card-header">
            <h3 class="card-title">Order Details</h3>
            @foreach ($app_users as $app_user)
            <h1 hidden>{{$app_user->phone_number}}</h1>
            @endforeach
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
                        @if ($app_user->id == $order->app_user_id)
                        <td>{{$app_user->phone_number}}</td>
                        @endif
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