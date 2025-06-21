@extends('layouts.app')

@section('content')
<div class="container">
    <h3>تفاصيل الطلب #{{ $order->id }}</h3>

    <p><strong>الزبون:</strong> {{ $order->user->name ?? '-' }}</p>
    <p><strong>رقم الهاتف:</strong> {{ $order->phone ?? $order->user->phone ?? '-' }}</p>
    <p><strong>العنوان:</strong> {{ $order->address ?? $order->user->address ?? '-' }}</p>
    <p><strong>الحالة الحالية:</strong> {{ $order->status }}</p>

    <hr>

    <h5>المنتجات ضمن الطلب:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>المجموع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->price }}$</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ $product->pivot->price * $product->pivot->quantity }}$</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('delivery.orders.index') }}" class="btn btn-secondary">عودة للطلبات</a>
</div>
@endsection
