@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل التقييم</h1>

    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>التقييم (1 إلى 5)</label>
            <input type="number" name="rating" class="form-control" value="{{ $review->rating }}" min="1" max="5" required>
        </div>
        <div class="mb-3">
            <label>التعليق</label>
            <textarea name="comment" class="form-control" rows="3">{{ $review->comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
