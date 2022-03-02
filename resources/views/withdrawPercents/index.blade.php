@extends('layouts.auth')

@extends('layouts.sidebar')

@section('content')

<div class="container">

    @if ($message = Session::get('success'))

    <div class="alert alert-success col-md-12" style="margin-right: 250px;margin-top: 1px;">

        <p>{{ $message }}</p>

    </div>
    @endif

    <div class="col-md-6 margin-tb" style="padding: 20px">

        <div class="pull-left">

            <h2>Percentage for Withdraw</h2>

        </div>

        <div class="pull-right">

            <a class="btn btn-success" href="{{ route('withdrawPercents.create') }}">Fill Withdraw amount</a>

        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Withdraw Percentage</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admin Percentage</th>
                        <th>Agent Percentage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawPercents as $withdrawPercent)
                    <tr>
                        <td>{{ $withdrawPercent->id }}</td>
                        <td>{{ $withdrawPercent->admin_percent }}</td>
                        <td>{{ $withdrawPercent->agent_percent }}</td>
                        <td>
                            <a class="btn btn-primary"
                                href="{{ route('withdrawPercents.edit',$withdrawPercent->id) }}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>

</div>

@endsection