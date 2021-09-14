@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>
@endif

<div class="container">

    <div class="col-md-6 margin-tb" style="padding: 20px">

        <div class="pull-left">

            <h2>Percentage for Deposit</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-success" href="{{ route('depositPercents.create') }}">Fill Deposit amount</a>

        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Deposit Percentage</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admin Percentage</th>
                        <th>Agent Percentage</th>
                        <th>Client Percentage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($depositPercents as $depositPercent)
                    <tr>
                        <td>{{ $depositPercent->id }}</td>
                        <td>{{ $depositPercent->admin_percent }}</td>
                        <td>{{ $depositPercent->agent_percent }}</td>
                        <td>{{ $depositPercent->client_percent }}</td>
                        <td>
                            <a class="btn btn-primary"
                                href="{{ route('depositPercents.edit',$depositPercent->id) }}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection