@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                @if(session('error'))
    <div class="alert alert-danger text-center mt-3">
        {{ session('error') }}
    </div>
@endif


                   {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
