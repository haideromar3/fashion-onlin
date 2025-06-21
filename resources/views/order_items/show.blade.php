@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>تفاصيل عنصر الطلب</h1>

    <div class="card">
        <div class="card-body">
            <h5>رقم الطلب: {{ $orderItem->order_id }}</h5>
            <h5>رقم المنتج: {{ $orderItem->product_id }}</h5>
            <p>الكمية: {{ $orderItem->quantity }}</p>
            <p>السعر: {{ $orderItem->price }}</p>
        </div>
    </div>

    <a href="{{ route('order-items.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
