@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'طلباتي')

@section('content')
<div class="container">
    <h2 class="mb-4">📦 طلباتي</h2>

    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="{{ route('complaints.create') }}" class="btn btn-warning">
            📝 إرسال شكوى
        </a>
        <a href="{{ route('user.transactions.virtual_card') }}" class="btn btn-success">
            💳 عرض السجل المالي
        </a>
    </div>

    @if($orders->count())
        @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <strong>طلب رقم #{{ $order->id }}</strong>
                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>طريقة الشحن:</strong> {{ $order->shipping_method }}</p>
                    <p><strong>رسوم الشحن:</strong> {{ $order->shipping_fee ?? 0 }} د.أ</p>
                    <p><strong>طريقة الدفع:</strong> {{ $order->payment_method == 'cod' ? 'الدفع عند الاستلام' : 'بطاقة' }}</p>
                    <p><strong>مدفوع:</strong> {!! $order->is_paid ? '✅ <span class="text-success">نعم</span>' : '❌ <span class="text-danger">لا</span>' !!}</p>
                    <p><strong>المجموع:</strong> {{ $order->total }} د.أ</p>

                    <h6>المنتجات:</h6>
                    <ul class="mb-3">
                        @foreach($order->items as $item)
                            <li>{{ $item->product->name }} × {{ $item->quantity }} ({{ $item->price }} د.أ)</li>
                        @endforeach
                    </ul>

                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-info">عرض التفاصيل</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info text-center">لا يوجد أي طلبات حتى الآن.</div>
    @endif
</div>
@endsection
