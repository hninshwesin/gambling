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

    @elseif ($message = Session::get('error'))

    <div class="alert alert-danger col-md-12" style="margin-right: 250px;margin-top: 1px;">

        <p>{{ $message }}</p>

    </div>

    @endif

    <div class="row" style="padding: 20px">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2> Request Deposit Client's Money </h2>

            </div>

        </div>

        <div class="form-group col-md-12">

            <a class="btn btn-primary" href="{{ url('/agent/deposit') }}">Back to Deposit Page</a>

        </div>

        <div class="form-group col-md-12">

            <a class="btn btn-primary" href="{{ route('home') }}"> Back to Home</a>

        </div>

    </div>

    <form action="{{ url('agent/deposit') }}" method="POST">
        @csrf
        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Client Phone Number:</strong>

                    <select class="form-control" name="client_id">
                        <option value="">--Select--</option>
                        @foreach( $clients as $client)
                        <option value="{{ $client->id }}">{{$client->phone_number}} (Current Balance -
                            {{$client->total_balances->wallet_balance}})</option>

                        @endforeach
                    </select>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Deposit Amount:</strong>

                    <input type="text" name="amount" class="form-control" placeholder="input amount" required>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Description:</strong>

                    <textarea class="form-control" name="description" placeholder="description or notes"></textarea>

                </div>

            </div>

            <div class="col-md-8 text-center">

                <button type="submit" class="btn btn-primary">Submit</button>

            </div>

        </div>
    </form>
</div>

@endsection