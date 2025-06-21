@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="container">
    <h2 class="mb-4">تفاصيل الطلب رقم #{{ $order->id }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>الحالة:</strong> <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span></p>
                <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

            <p><strong>طريقة الشحن:</strong> {{ $order->shipping_method }}</p>
            <p><strong>طريقة الدفع:</strong> {{ $order->payment_method == 'cod' ? 'الدفع عند الاستلام' : 'بطاقة' }}</p>
            <p><strong>رسوم الشحن:</strong> {{ number_format($order->shipping_fee ?? 0, 2) }} د.أ</p>

            <p><strong>هل تم الدفع؟</strong> {{ $order->is_paid ? '✅ نعم' : '❌ لا' }}</p>
            <p><strong>الإجمالي:</strong> {{ number_format($order->total, 2) }} د.أ</p>
            <p><strong>المدينة:</strong> {{ $order->city }}</p>
            <p><strong>العنوان:</strong> {{ $order->address }}</p>

        </div>
    </div>

    <h5>🛍️ المنتجات:</h5>
    <ul class="list-group mb-4">
        @foreach($order->items as $item)
            <li class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        @php
                            $imagePath = $item->product->images->first()->image_path ?? null;
                            $imageUrl = $imagePath ? asset('storage/' . $imagePath) : asset('images/no-image.png');
                        @endphp
                        <img src="{{ $imageUrl }}" class="img-fluid rounded" alt="صورة المنتج">
                    </div>
                    <div class="col-md-10">
                        <h6>{{ $item->product->name }}</h6>
                        <p class="mb-1">
                            <strong>الكمية:</strong> {{ $item->quantity }} × {{ number_format($item->price, 2) }} د.أ<br>
                            <strong>القياس:</strong> {{ $item->size ?? 'غير محدد' }}<br>
                            <strong>اللون:</strong>
                            @if($item->color)
                                <span style="display:inline-block; width:20px; height:20px; background-color:{{ $item->color }}; border:1px solid #ccc; vertical-align: middle;"></span>
                                {{ $item->color }}
                            @else
                                غير محدد
                            @endif
                            <br>
                            <strong>الماركة:</strong> {{ $item->product->brand->name ?? 'غير محددة' }}
                        </p>
                        <span class="badge bg-primary">الإجمالي: {{ number_format($item->quantity * $item->price, 2) }} د.أ</span>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">🔙 العودة إلى طلباتي</a>
</div>
@endsection
