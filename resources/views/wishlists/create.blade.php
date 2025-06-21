@extends('layouts.app')

@section('content')
<div class="container">
    <h1>إضافة إلى المفضلة</h1>

    <form action="{{ route('wishlists.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>رقم المستخدم</label>
            <input type="number" name="user_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>رقم المنتج</label>
            <input type="number" name="product_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
