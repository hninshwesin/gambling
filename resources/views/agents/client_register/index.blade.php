@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

@if ($errors->any())

<div class="alert alert-danger col-md-12" style="margin-right: 250px;margin-top: 1px;">

    <strong>Whoops!</strong> There were some problems with your input.<br><br>

    <ul>

        @foreach ($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@elseif ($message = Session::get('success'))

<div class="alert alert-success col-md-12" style="margin-right: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>


@elseif ($message = Session::get('error'))

<div class="alert alert-danger col-md-12" style="margin-right: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>

@endif

<div class="content-header">
    <div class="container">

        <div class="card">
            <div class="card-header">
                <h2 style="color: rgb(51, 137, 172)">Have you got secret code to make client register?
                </h2>
            </div>

            <div class="card-body">
                <h4>
                    If already got secret code =>
                    <a class="btn btn-primary" href="{{ url('/agent/app_user/create') }}">Register Now</a>
                </h4>
                <br>
                <h4>
                    If doesn't have secret code =>
                    <a class="btn btn-primary" href="{{ url('/agent/client_register/create') }}">Generate Code</a>
                </h4>

            </div>
        </div>

    </div>
</div>

@endsection