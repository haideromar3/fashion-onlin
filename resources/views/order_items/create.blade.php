@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>إضافة عنصر طلب</h1>

    <form action="{{ route('order-items.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>رقم الطلب</label>
            <input type="number" name="order_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>رقم المنتج</label>
            <input type="number" name="product_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الكمية</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>السعر</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
