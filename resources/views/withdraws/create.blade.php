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

    <form action="{{ route('withdraw.store')}}" method="POST">
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

                    <strong>Withdraw Amount:</strong>

                    <input type="text" name="amount" class="form-control" placeholder="input amount" required>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Admin Fee:</strong>
                    <div class="input-group">
                        <input type="number" class="form-control" name="admin_fee" placeholder="Admin Fee (%)" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Agent Fee:</strong>
                    <div class="input-group">
                        <input type="number" class="form-control" name="agent_fee" placeholder="Agent Fee (%)" required>
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