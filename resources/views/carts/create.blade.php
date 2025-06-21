@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')


@if(session('cart') && count(session('cart')) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>الصورة</th>
                <th>المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الإجمالي</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $productId => $item)
            <tr>
                <td><img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 80px;"></td>
                <td>{{ $item['name'] }}</td>
                <td>{{ number_format($item['price'], 2) }} د.إ</td>
                <td>
                    <form action="{{ route('cart.update', $productId) }}" method="POST">
                        @csrf
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width: 60px;">
                        <button type="submit" class="btn btn-sm btn-primary">تعديل</button>
                    </form>
                </td>
                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} د.إ</td>
                <td>
                    <form action="{{ route('cart.remove', $productId) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا المنتج؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-end"><strong>المجموع الكلي:</strong></td>
                <td colspan="2"><strong>{{ number_format($total, 2) }} د.إ</strong></td>
            </tr>
        </tbody>
    </table>
    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تفريغ السلة؟');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-warning">تفريغ السلة</button>
    </form>
@else
    <p>سلة التسوق فارغة.</p>
@endif
