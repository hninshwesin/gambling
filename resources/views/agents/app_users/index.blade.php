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

            <h2>Client Register</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-success" href="{{ url('/agent/app_user/create') }}">Client Register</a>

        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Client Detail Lists</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Current Balance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{$client->phone_number}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->total_balances->wallet_balance}}</td>
                        <td>{{$client->created_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection