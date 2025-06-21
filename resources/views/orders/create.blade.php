@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
    <div class="container">
        <h1>إضافة طلب جديد</h1>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_name">اسم العميل</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">الحالة</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending">معلق</option>
                    <option value="completed">مكتمل</option>
                    <option value="canceled">ملغي</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">حفظ</button>
        </form>
    </div>
@endsection
