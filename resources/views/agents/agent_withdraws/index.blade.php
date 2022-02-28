@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>
@endif

<div class="container">

    <div class="row" style="padding: 20px">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Withdraws</h2>

            </div>

        </div>

        <div class="form-group col-md-12">

            <a class="btn btn-success" href="{{ url('/agent/agent_withdraw/create') }}">Make Request to ADMIN for
                Withdraw
                money</a>

        </div>

        <div class="form-group col-md-12">

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
                        <th>Name</th>
                        <th>Withdraw Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Approve Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agent_withdraws as $agent_withdraw)
                    <tr>
                        <td>{{ $agent_withdraw->id }}</td>
                        <td>{{$agent_withdraw->agent->name}}</td>
                        <td>{{$agent_withdraw->amount}}</td>
                        <td>{{$agent_withdraw->description}}</td>
                        <td>{{$agent_withdraw->created_at}}</td>
                        <td>
                            @if ($agent_withdraw->approve_status == 0)
                            Pending
                            @elseif ($agent_withdraw->approve_status == 1)
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