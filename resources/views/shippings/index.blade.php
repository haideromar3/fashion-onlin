@extends('layouts.app')

@section('content')
<div class="container">
    <h1>كل الشحنات</h1>
    <a href="{{ route('shippings.create') }}" class="btn btn-primary mb-3">إضافة شحنة جديدة</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shippings as $shipping)
            <tr>
                <td>{{ $shipping->order_id }}</td>
                <td>{{ $shipping->address }}</td>
                <td>{{ $shipping->status }}</td>
                <td>
                    <a href="{{ route('shippings.show', $shipping->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('shippings.edit', $shipping->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="{{ route('shippings.destroy', $shipping->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shippings->links() }}
</div>
@endsection
