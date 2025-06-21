@extends('layouts.app')

@section('content')
<div class="container">
    <h1>معلومات المستخدم</h1>

    <div class="card">
        <div class="card-body">
            <h5>الاسم: {{ $user->name }}</h5>
            <p>البريد الإلكتروني: {{ $user->email }}</p>
        </div>
    </div>

    <a href="{{ route('profiles.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
