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

    <form action="{{ route('depositPercents.update',$depositPercent->id)}}" method="POST">
        @csrf
        @method('PUT')

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Admin Percentage:</strong>

                    <div class="input-group">
                        <input type="number" name="admin_percent" class="form-control" placeholder="input Admin Fee (%)"
                            value="{{ $depositPercent->admin_percent }}" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Agent Percentage:</strong>

                    <div class="input-group">
                        <input type="number" class="form-control" name="agent_percent" placeholder="input Agent Fee (%)"
                            value="{{ $depositPercent->agent_percent }}" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8">

                <div class="form-group">

                    <strong>Client Percentage:</strong>

                    <div class="input-group">
                        <input type="number" class="form-control" name="client_percent"
                            placeholder="input Agent Fee (%)" value="{{ $depositPercent->client_percent }}" required>
                        <span class="input-group-addon"> %</span>
                    </div>

                </div>

            </div>

            <div class="col-md-8 text-center">

                <button type="submit" class="btn btn-primary">Submit</button>

            </div>

        </div>
    </form>
</div>

@endsection