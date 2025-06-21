@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل بيانات الشحنة</h1>

    <form action="{{ route('shippings.update', $shipping->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="address" class="form-control" value="{{ $shipping->address }}" required>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <input type="text" name="status" class="form-control" value="{{ $shipping->status }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
