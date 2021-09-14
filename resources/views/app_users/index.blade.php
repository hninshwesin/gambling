@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

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
                        <td>{{$client->total_balances->total_balance}}</td>
                        <td>{{$client->created_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection