@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
    <div class="container">
        <h1>تفاصيل السلة</h1>
        <ul>
            <li><strong>المنتج:</strong> {{ $cart->product->name }}</li>
            <li><strong>الكمية:</strong> {{ $cart->quantity }}</li>
            <li><strong>السعر:</strong> {{ $cart->price }}</li>
        </ul>
        <a href="{{ route('carts.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>
    </div>
@endsection
