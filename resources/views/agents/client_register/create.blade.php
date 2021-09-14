@extends('agents.auth')

@extends('agents.sidebar')

@if ($errors->any())

<div class="alert alert-danger col-md-8" style="margin-left: 300px;margin-top: 1px;">

    <strong>Whoops!</strong> There were some problems with your input.<br><br>

    <ul>

        @foreach ($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

@section('content')
<div class="content-header">
    <div class="container">

        <div class="card">
            <div class="card-header">
                <h2 style="color: rgb(51, 137, 172)">Generate Registration Code
                </h2>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('client_register.store')}}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Generate
                    </button>
                </form>
            </div>
        </div>

        @if (session()->has('data'))
        <div class=" alert alert-success col-md-8 justify-content-center">
            <h4 id="text">{{ session()->get('data') }}</h4>
            <button class="btn btn-primary" onclick="copyToClipboard('#text')"> copy</button>
        </div>

        @endif

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