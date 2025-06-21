@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">جميع الطلبات</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>الزبون</th>
                <th>الإيميل</th>
                <th>رقم الهاتف</th>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>التفاصيل</th>
                <th>تحديث الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'غير معروف' }}</td>
                    <td>{{ $order->user->email ?? '-' }}</td>
                    <td>{{ $order->user->phone ?? $order->phone ?? '-' }}</td>
                    <td>{{ $order->user->address ?? $order->address ?? '-' }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-sm btn-info">عرض التفاصيل</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('delivery.orders.update', $order) }}">
                            @csrf
                            @method('PUT') {{-- ✅ هذا هو السطر المهم لإصلاح الخطأ --}}
                            <select name="status" class="form-control d-inline w-auto">
                                @foreach(['pending', 'processing', 'shipped', 'delivered', 'returned', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary mt-1">تحديث</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
