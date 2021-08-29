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

    <form action="{{ url('/agent/deposit') }}" method="POST">
        @csrf
        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="form-group">

                    <strong>App User Phone Number:</strong>

                    <select class="form-control" name="app_user_id">
                        <option value="">--Select--</option>
                        @foreach( $app_users as $app_user)
                        <option value="{{ $app_user->id }}">{{$app_user->phone_number}} (Current Balance -
                            {{$app_user->total_balances->total_balance}})</option>

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

                    <strong>Admin Fee: (with %)</strong>
                    <div class="input-group">
                        <input type="number" class="form-control" name="admin_fee" value="0" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Agent Fee: (with %)</strong>
                    <div class="input-group">
                        <input type="number" class="form-control" name="agent_fee" value="0" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Client Fee: (with %)</strong>
                    <div class="input-group">
                        <input type="number" class="form-control" name="client_fee" value="0" required>
                        <span class="input-group-addon"> %</span>
                    </div>

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