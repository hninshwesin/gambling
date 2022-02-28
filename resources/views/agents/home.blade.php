@extends('layouts.auth')

@extends('agents.sidebar')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>
@endif

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            {{-- <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard v1</li>
                </ol>
            </div><!-- /.col --> --}}
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="container">
    {{-- <div class="row justify-content-center"> --}}
        <div class="row">

            <div class="col-lg-4 col-12">
                <div class="small-box bg-success">

                    <div class="inner">

                        <h3>{{$total_balance}}<sup style="font-size: 20px"> MMK</sup></h3>

                        <p>Your Balance</p>
                    </div>

                    <div class="icon">
                        <i class="fa fa-wallet"></i>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-info">

                    <div class="inner">

                        <h3>{{$order_count}}</h3>

                        <p>New Orders</p>
                    </div>

                    <div class="icon">
                        <i class="fa fa-bars"></i>
                    </div>

                    {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    --}}
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <!-- small box -->
                <div class="small-box bg-gradient-success">

                    <div class="inner">
                        <h3>{{$client_count}}</h3>

                        <p>Client Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    --}}


                </div>
            </div>

        </div>

        <!-- /.card -->
        <div class="card">
            <!-- /.card-header -->
            <div class="card-header">
                <h3 class="card-title">All Client Infomation</h3>
            </div>
            <!-- /.card-body -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>{{$client->phone_number}}</td>
                            <td>{{$client->email}}</td>
                            <td>{{$client->created_at}}</td>
                        </tr>
                        @endforeach
                </table>
            </div>
        </div>

    </div>
    @endsection