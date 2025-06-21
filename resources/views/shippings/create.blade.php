@extends('layouts.app')

@section('content')
<div class="container">
    <h1>إضافة شحنة جديدة</h1>

    <form action="{{ route('shippings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>رقم الطلب</label>
            <input type="number" name="order_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <input type="text" name="status" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
