@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">App User Detail Lists</h3>
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
                    @foreach($app_users as $app_user)
                    <tr>
                        <td>{{ $app_user->id }}</td>
                        <td>{{$app_user->phone_number}}</td>
                        <td>{{$app_user->email}}</td>
                        <td>{{$app_user->total_balances->total_balance}}</td>
                        <td>{{$app_user->created_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection