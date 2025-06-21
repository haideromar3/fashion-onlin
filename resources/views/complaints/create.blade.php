@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">

    <h3>إرسال شكوى</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('complaints.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="order_id" class="form-label">اختر الطلب المتعلق بالشكوى:</label>
            <select name="order_id" id="order_id" class="form-select" required>
                <option value="">-- اختر الطلب --</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">طلب #{{ $order->id }} - {{ $order->created_at->format('Y-m-d') }}</option>
                @endforeach
            </select>
            @error('order_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">نص الشكوى:</label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
            @error('message') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">إرسال الشكوى</button>
    </form>

    <hr>

    <h4>الشكاوى السابقة</h4>

    @forelse($complaints as $complaint)
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>الطلب:</strong> رقم #{{ $complaint->order->id }}</p>
                <p><strong>الشكوى:</strong> {{ $complaint->message }}</p>
                <p><strong>تاريخ الإرسال:</strong> {{ $complaint->created_at->format('Y-m-d H:i') }}</p>

                @if($complaint->reply)
                    <div class="alert alert-success mt-3">
                        <strong>رد المسؤول:</strong> {{ $complaint->reply }}
                    </div>
                @else
                    <div class="text-muted">لم يتم الرد بعد.</div>

                    {{-- أزرار التعديل والحذف تظهر فقط إذا لم يتم الرد على الشكوى --}}
                    <div class="mt-3">
                        <a href="{{ route('complaints.edit', $complaint->id) }}" class="btn btn-sm btn-warning">تعديل</a>

                        <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه الشكوى؟');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p>لا توجد شكاوى مرسلة بعد.</p>
    @endforelse

</div>
@endsection
