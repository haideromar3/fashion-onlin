@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h1>كل عناصر الطلب</h1>
    <a href="{{ route('order-items.create') }}" class="btn btn-primary mb-3">إضافة عنصر طلب جديد</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الطلب</th>
                <th>المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItems as $item)
            <tr>
                <td>{{ $item->order_id }}</td>
                <td>{{ $item->product_id }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>
                    <a href="{{ route('order-items.show', $item->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('order-items.edit', $item->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="{{ route('order-items.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orderItems->links() }}
</div>
@endsection
