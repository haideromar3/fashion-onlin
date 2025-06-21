@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل التقييم</h1>

    <div class="card">
        <div class="card-body">
            <h5>رقم المنتج: {{ $review->product_id }}</h5>
            <p>التقييم: {{ $review->rating }}</p>
            <p>التعليق: {{ $review->comment }}</p>
        </div>
    </div>

    <a href="{{ route('reviews.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
