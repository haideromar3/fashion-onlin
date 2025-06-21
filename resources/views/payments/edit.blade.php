@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>تعديل عملية دفع</h1>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>المبلغ</label>
            <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" step="0.01" required>
        </div>
        <div class="mb-3">
            <label>طريقة الدفع</label>
            <input type="text" name="payment_method" class="form-control" value="{{ $payment->payment_method }}" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
