@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل المفضلة</h1>
asdasdsad
    <div class="card">
        <div class="card-body">
            <h5>رقم المستخدم: {{ $wishlist->user_id }}</h5>
            <p>رقم المنتج: {{ $wishlist->product_id }}</p>
        </div>
    </div>

    <a href="{{ route('wishlists.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
