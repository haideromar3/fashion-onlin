@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل الشحنة</h1>

    <div class="card">
        <div class="card-body">
            <h5>رقم الطلب: {{ $shipping->order_id }}</h5>
            <p>العنوان: {{ $shipping->address }}</p>
            <p>الحالة: {{ $shipping->status }}</p>
        </div>
    </div>

    <a href="{{ route('shippings.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
