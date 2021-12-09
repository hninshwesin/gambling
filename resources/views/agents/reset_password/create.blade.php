@extends('agents.auth')

@extends('agents.sidebar')

@section('content')

@if ($errors->any())

<div class="alert alert-danger col-md-8" style="margin-left: 300px;margin-top: 1px;">

    <strong>Whoops!</strong> There were some problems with your input.<br><br>

    <ul>

        @foreach ($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@elseif ($message = Session::get('success'))

<div class="alert alert-success col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>

@elseif ($message = Session::get('error'))

<div class="alert alert-danger col-md-8" style="margin-left: 250px;margin-top: 1px;">

    <p>{{ $message }}</p>

</div>

@endif

<div class="content-header">
    <div class="container">

        <div class="card">
            <div class="card-header">
                <h3 style="color: rgb(51, 137, 172)">Reset Password
                </h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('password.store')}}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="form-group">

                                <strong>E-Mail Address:</strong>

                                <input type="email" name="email" class="form-control" required>

                            </div>
                        </div>
                        <div class="col-md-8 text-center">

                            <button type="submit" class="btn btn-primary">Send Password Reset Email</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
    }
</script>
@endsection