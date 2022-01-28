@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    <div class="row" style="padding: 20px">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Withdraws</h2>

            </div>

        </div>

        <div class="form-group col-md-6">

            <a class="btn btn-success" href="{{ route('withdraw.create') }}">Fill Withdraw</a>

        </div>

        <div class="form-group col-md-8">

            <a class="btn btn-success" href="{{ route('withdraw_request') }}">Make Approve for Withdraw Request</a>

        </div>

        <div class="form-group col-md-6">

            <a class="btn btn-primary" href="{{ route('home') }}"> Back to Home</a>

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
                        <th>Request Date</th>
                        <th>Approved Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdraws as $withdraw)
                    <tr>
                        <td>{{ $withdraw->id }}</td>
                        <td>{{$withdraw->client->phone_number}}</td>
                        <td>{{$withdraw->amount}}</td>
                        <td>{{$withdraw->fee}} %</td>
                        <td>{{$withdraw->description}}</td>
                        <td>{{$withdraw->created_at}}</td>
                        <td>{{$withdraw->updated_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection