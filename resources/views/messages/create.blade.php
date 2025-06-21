@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container my-4">
    <h2 class="mb-4 text-center">✉️ إرسال رسالة إلى الإدارة</h2>

    {{-- إشعار النجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- نموذج إرسال رسالة --}}
    <div class="card shadow mb-5">
        <div class="card-body">
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="content" class="form-label">💬 محتوى الرسالة</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="اكتب رسالتك هنا..." required>{{ old('content') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">📤 إرسال</button>
            </form>
        </div>
    </div>

{{-- عرض الرسائل المرسلة سابقاً --}}
<h4 class="mb-3">📄 الرسائل السابقة وردود الإدارة</h4>

@forelse($messages as $msg)
    <div class="card mb-3 border-start border-4 border-info shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong>🕒 {{ $msg->created_at->format('Y-m-d H:i') }}</strong>

            {{-- زر الحذف يظهر فقط إذا المستخدم هو المرسل --}}
@if($msg->sender_id === auth()->id())
<form action="{{ route('messages.destroy', $msg->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">🗑️ حذف</button>
    </form>
@endif


        </div>
        <div class="card-body">
            <p class="mb-2"><strong>✍️ رسالتك:</strong></p>
            <p>{{ $msg->content }}</p>

            {{-- الردود --}}
            @if($msg->replies->count())
                <hr>
                <h6 class="text-primary">💬 ردود الإدارة:</h6>
                @foreach($msg->replies as $reply)
                    <div class="bg-light rounded p-2 mb-2">
                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                        <div>{{ $reply->content }}</div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">⏳ لم يتم الرد حتى الآن.</p>
            @endif
        </div>
    </div>
@empty
    <p class="text-muted text-center">لا توجد رسائل مرسلة بعد.</p>
@endforelse

</div>
@endsection
