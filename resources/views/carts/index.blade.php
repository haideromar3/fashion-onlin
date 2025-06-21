@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">🛒 سلة التسوق الخاصة بك</h2>

    {{-- رسائل الجلسة --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        {{-- جدول السلة --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-dark">
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
                        @php $total = 0; @endphp
                        @foreach($cart as $productId => $item)
                            @php
                                $itemTotal = $item['price'] * $item['quantity'];
                                $total += $itemTotal;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                         alt="{{ $item['name'] }}" 
                                         style="width: 80px;" 
                                         class="img-thumbnail">
                                </td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format($item['price'], 2) }} $</td>
                                <td>
                                    <form action="{{ route('cart.update', $productId) }}" method="POST" class="d-flex justify-content-center align-items-center">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control w-50" required>
                                        <button class="btn btn-primary btn-sm ms-2" type="submit">تحديث</button>
                                    </form>
                                </td>
                                <td>{{ number_format($itemTotal, 2) }} $</td>
                                <td>
                                    <form action="{{ route('cart.remove', $productId) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold table-light">
                            <td colspan="4" class="text-end">الإجمالي الكلي</td>
                            <td colspan="2">{{ number_format($total, 2) }} $</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

     {{-- زر متابعة الدفع --}}
<a href="{{ route('payments.create') }}" class="btn btn-success w-100 mt-3">
    ✅ إتمام الطلب والدفع
</a>


    @else
        <div class="alert alert-info mt-4">سلتك فارغة.</div>
    @endif
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'نجاح', text: '{{ session('success') }}', confirmButtonText: 'حسناً' });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'خطأ', text: '{{ session('error') }}', confirmButtonText: 'حسناً' });
</script>
@endif
@endsection
