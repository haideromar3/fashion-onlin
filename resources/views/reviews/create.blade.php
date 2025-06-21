@extends('layouts.app')

@section('content')
<div class="container">
    <h1>إضافة تقييم جديد</h1>

    <form action="{{ route('reviews.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>رقم المنتج</label>
            <input type="number" name="product_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>التقييم (1 إلى 5)</label>
            <input type="number" name="rating" class="form-control" min="1" max="5" required>
        </div>
        <div class="mb-3">
            <label>التعليق</label>
            <textarea name="comment" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
