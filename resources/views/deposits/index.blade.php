@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    <div class="row" style="padding: 20px">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Deposits</h2>

            </div>

        </div>

        {{-- <div class="form-group col-md-6">

            <a class="btn btn-success" href="{{ route('deposit.create') }}">Fill Deposit</a>

        </div>

        <div class="form-group col-md-8">

            <a class="btn btn-success" href="{{ route('deposit_request') }}">Make Approve for Deposit Request</a>

        </div> --}}

        <div class="form-group col-md-6">

            <a class="btn btn-primary" href="{{ route('home') }}"> Back to Home</a>

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
                        <th>Final Amount</th>
                        <th>Description</th>
                        <th>Request Date</th>
                        <th>Approved Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        <td>{{$deposit->client->phone_number}}</td>
                        <td>{{$deposit->amount}}</td>
                        <td>{{$deposit->fee}} %</td>
                        <td>{{$deposit->final_amount}}</td>
                        <td>{{$deposit->description}}</td>
                        <td>{{$deposit->created_at}}</td>
                        <td>{{$deposit->updated_at}}</td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection