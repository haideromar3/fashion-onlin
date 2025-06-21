@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل الإشعار</h1>

    <div class="card">
        <div class="card-body">
            <h5>العنوان: {{ $notification->title }}</h5>
            <p>النص: {{ $notification->body }}</p>
        </div>
    </div>

    <a href="{{ route('notifications.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
