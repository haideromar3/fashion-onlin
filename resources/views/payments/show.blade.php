@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>تفاصيل الدفع</h1>

    <div class="card">
        <div class="card-body">
            <h5>رقم الطلب: {{ $payment->order_id }}</h5>
            <p>المبلغ: {{ $payment->amount }}</p>
            <p>طريقة الدفع: {{ $payment->payment_method }}</p>
        </div>
    </div>

    <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
