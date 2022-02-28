@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="content-header">

    <div class="container">

        @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Whoops!</strong> There were some problems with your input.<br><br>

            <ul>

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

        @endif

        <div class="row" style="padding: 20px">

            <div class="form-group col-md-8">

                <a class="btn btn-success" href="{{ url('agent_withdraw_history') }}">Agent Withdraw History</a>

            </div>

            <div class="form-group col-md-8">

                <a class="btn btn-success" href="{{ route('agent_withdraw_request') }}">Make Approve for Agent Withdraw
                    Request</a>

            </div>

            <div class="form-group col-md-6">

                <a class="btn btn-primary" href="{{ route('home') }}"> Back to Home</a>

            </div>
        </div>

        <div class="card" style="border-color: rgb(150, 245, 190)">
            <div class="card-header" style="background-color: rgb(150, 245, 190)">
                <h1 class="card-title">Agent Lists</h1>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agent Name</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                            <th>Balance</th>
                            <th>Client Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                        <tr>
                            <td>{{ $agent->id }}</td>
                            <td>{{ $agent->name }}</td>
                            <td>{{ $agent->email }}</td>
                            <td>{{ $agent->created_at }}</td>
                            <td>{{ $agent->total_balance }}</td>
                            <td>
                                @if ($agent->have_client == 0)
                                No
                                @elseif ($agent->have_client == 1)
                                Yes
                                @endif
                            </td>
                            <td>

                                @if ($agent->have_client == 1)
                                -
                                @elseif ($agent->have_client == 0)
                                <form action="{{ route('agent_detail.destroy',$agent->id) }}" method="POST">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">Delete</button>

                                </form>
                                @endif

                            </td>
                        </tr>
                        @endforeach

                </table>
            </div>
        </div>
    </div>
</div>

@endsection