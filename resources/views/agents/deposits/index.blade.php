@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>
@endif

<div class="container">

    <div class="col-md-3 margin-tb" style="padding: 20px">

        <div class="pull-left">

            <h2>Deposits</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-success" href="{{ url('/agent/deposit/create') }}">Fill Deposit</a>

        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Deposit History</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone Number</th>
                        <th>Deposit Amount</th>
                        <th>Fee (%)</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        <td>{{$deposit->client->phone_number}}</td>
                        <td>{{$deposit->amount}}</td>
                        <td>{{$deposit->fee}} %</td>
                        <td>{{$deposit->description}}</td>
                        <td>{{$deposit->created_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection