@extends('layouts.app')

@extends('layouts.sidebar')

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
                        <th>Trade</th>
                        <th>Waiting minute</th>
                        <th>Open Price</th>
                        <th>Open Time</th>
                        <th>Close Price</th>
                        <th>Close Time</th>
                        <th>Result</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{$order->client->phone_number}}</td>
                        <td>{{$order->amount}}</td>
                        <td>
                            @if($order->bid_status == 0)
                            Buy
                            @elseif ($order->bid_status == 1)
                            Sell
                            @endif
                        </td>
                        <td>{{$order->minute}}</td>
                        <td>{{$order->stock_rate}}</td>
                        <td>{{$order->created_at->format('m/d/Y H:i')}}</td>
                        <td>{{$order->bid_compare->end_rate}}</td>
                        <td>{{$order->bid_compare->created_at->format('m/d/Y H:i')}}</td>
                        <td>
                            @if ($order->bid_compare->status == 0)
                            Stable
                            @elseif ($order->bid_compare->status == 1)
                            Win
                            @elseif ($order->bid_compare->status == 2)
                            Loss
                            @endif
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>
@endsection