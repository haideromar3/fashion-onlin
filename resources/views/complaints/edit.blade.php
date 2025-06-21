@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h3>تعديل الشكوى</h3>

    <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="order_id" class="form-label">اختر الطلب:</label>
            <select name="order_id" id="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ $complaint->order_id == $order->id ? 'selected' : '' }}>
                        طلب #{{ $order->id }} - {{ $order->created_at->format('Y-m-d') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">نص الشكوى:</label>
            <textarea name="message" id="message" class="form-control" rows="4" required>{{ $complaint->message }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        <a href="{{ route('complaints.create') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
