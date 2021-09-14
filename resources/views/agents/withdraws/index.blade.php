@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

<div class="container">

    <div class="col-md-3 margin-tb" style="padding: 20px">

        <div class="pull-left">

            <h2>Withdraws</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-success" href="{{ url('/agent/withdraw/create') }}">Fill Withdraw amount</a>

        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Withdraw History</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone Number</th>
                        <th>Withdraw Amount</th>
                        <th>Fee (%)</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdraws as $withdraw)
                    <tr>
                        <td>{{ $withdraw->id }}</td>
                        <td>{{$withdraw->client>phone_number}}</td>
                        <td>{{$withdraw->amount}}</td>
                        <td>{{$withdraw->fee}} %</td>
                        <td>{{$withdraw->description}}</td>
                        <td>{{$withdraw->created_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection