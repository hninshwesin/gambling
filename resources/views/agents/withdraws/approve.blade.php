@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

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

    @if ($message = Session::get('error'))

    <div class="alert alert-danger col-md-12" style="margin-right: 250px;margin-top: 1px;">

        <p>{{ $message }}</p>

    </div>

    @endif

    <div class="form-group col-md-6">

        <a class="btn btn-primary" href="{{ url('agent/withdraw') }}"> Back</a>

    </div>

    <table class="table table-bordered">

        <tr>

            <th>ID</th>

            <th>Phone Number</th>

            <th>Withdraw Amount</th>

            <th>Fee (%)</th>

            <th>Description</th>

            <th>Date</th>

            <th style="width:280px">Action</th>

        </tr>

        @foreach ($withdraws as $withdraw)

        <tr>

            <td>{{ $withdraw->id }}</td>

            <td>{{ $withdraw->client->phone_number }}</td>

            <td>{{$withdraw->amount}}</td>

            <td>{{$withdraw->fee}} %</td>

            <td>{{$withdraw->description}}</td>

            <td>{{$withdraw->created_at}}</td>

            <td>

                <form action="{{ route('withdraw_approve_from_client') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to approve this withdraw request?');">
                    @csrf

                    <input type="number" class="form-control" value="{{$withdraw->id}}" name="withdraw_id" hidden>

                    <button class="btn btn-success my-2 my-sm-0" type="submit" value="submit">Approve</button>

                </form>

            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection