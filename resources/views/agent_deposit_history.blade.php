@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    @if ($message = Session::get('success'))

    <div class="alert alert-success col-md-12" style="margin-right: 250px;margin-top: 1px;">

        <p>{{ $message }}</p>

    </div>

    @endif

    <div class="row" style="padding: 20px">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Deposits</h2>

            </div>

        </div>

        <div class="form-group col-md-12">

            <a class="btn btn-primary" href="{{ route('agent_detail.index') }}"> Back to Agent Detail Page</a>

        </div>

        <div class="form-group col-md-12">

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
                        <th>Name</th>
                        <th>Deposit Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Approve Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agent_deposits as $agent_deposit)
                    <tr>
                        <td>{{ $agent_deposit->id }}</td>
                        <td>{{$agent_deposit->agent->name}}</td>
                        <td>{{$agent_deposit->amount}}</td>
                        <td>{{$agent_deposit->description}}</td>
                        <td>{{$agent_deposit->created_at}}</td>
                        <td>
                            @if ($agent_deposit->approve_status == 0)
                            Pending
                            @elseif ($agent_deposit->approve_status == 1)
                            Approved
                            @endif
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection