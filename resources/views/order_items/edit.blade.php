@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>تعديل عنصر طلب</h1>

    <form action="{{ route('order-items.update', $orderItem->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>الكمية</label>
            <input type="number" name="quantity" class="form-control" value="{{ $orderItem->quantity }}" required>
        </div>
        <div class="mb-3">
            <label>السعر</label>
            <input type="number" name="price" class="form-control" value="{{ $orderItem->price }}" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
