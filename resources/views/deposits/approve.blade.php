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

        <a class="btn btn-primary" href="{{ route('deposit.index') }}"> Back</a>

    </div>

    <table class="table table-bordered">

        <tr>

            <th>ID</th>

            <th>Phone Number</th>

            <th>Deposit Amount</th>

            <th>Fee (%)</th>

            <th>Description</th>

            <th>Date</th>

            <th style="width:280px">Action</th>

        </tr>

        @foreach ($deposits as $deposit)

        <tr>

            <td>{{ $deposit->id }}</td>

            <td>{{ $deposit->client->phone_number }}</td>

            <td>{{$deposit->amount}}</td>

            <td>{{$deposit->fee}} %</td>

            <td>{{$deposit->description}}</td>

            <td>{{$deposit->created_at}}</td>

            <td>

                <form action="{{ route('deposit_approve') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to approve this deposit request?');">
                    @csrf

                    <input type="number" class="form-control" value="{{$deposit->id}}" name="deposit_id" hidden>

                    <button class="btn btn-success my-2 my-sm-0" type="submit" value="submit">Approve</button>

                </form>

            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection