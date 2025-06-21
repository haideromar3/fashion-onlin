@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل المفضلة</h1>

    <form action="{{ route('wishlists.update', $wishlist->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>رقم المستخدم</label>
            <input type="number" name="user_id" class="form-control" value="{{ $wishlist->user_id }}" required>
        </div>
        <div class="mb-3">
            <label>رقم المنتج</label>
            <input type="number" name="product_id" class="form-control" value="{{ $wishlist->product_id }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
