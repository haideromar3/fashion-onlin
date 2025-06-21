@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
    <div class="container">
        <h1>تعديل الطلب</h1>
        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="customer_name">اسم العميل</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ $order->customer_name }}" required>
            </div>
            <div class="form-group">
                <label for="status">الحالة</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection
