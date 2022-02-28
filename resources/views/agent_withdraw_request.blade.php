@extends('layouts.auth')

@extends('layouts.sidebar')

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

    <div class="form-group col-md-6">

        <a class="btn btn-primary" href="{{ route('agent_detail.index') }}"> Back</a>

    </div>

    <table class="table table-bordered">

        <tr>

            <th>ID</th>

            <th>Name</th>

            <th>Withdraw Amount</th>

            <th>Description</th>

            <th>Date</th>

            <th style="width:280px">Action</th>

        </tr>

        @foreach ($agent_withdraws as $withdraw)

        <tr>

            <td>{{ $withdraw->id }}</td>

            <td>{{ $withdraw->agent->name }}</td>

            <td>{{$withdraw->amount}}</td>

            <td>{{$withdraw->description}}</td>

            <td>{{$withdraw->created_at}}</td>

            <td>

                <form action="{{ route('agent_withdraw_approve') }}" method="POST"
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