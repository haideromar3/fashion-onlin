@extends('layouts.admin')

@section('title', 'الرسائل')

@section('content')
<div class="container my-4">
    <h1 class="mb-4 text-center">📬 رسائل المستخدمين</h1>

    @forelse ($messages as $msg)
        @php
            $role = $msg->sender->role ?? 'unknown';
            $roleColor = match ($role) {
                'designer' => 'primary',
                'supplier' => 'success',
                'customer' => 'info',
                'admin' => 'dark',
                default => 'secondary'
            };
            $roleLabel = match ($role) {
                'designer' => 'مصمم',
                'supplier' => 'مورد',
                'customer' => 'زبون',
                'admin' => 'مدير',
                default => 'غير معروف'
            };
        @endphp

        <div class="card shadow-sm mb-4 border-start border-4 border-{{ $roleColor }}">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <div>
                    <strong>👤 {{ $msg->sender->name }}</strong>
                    <span class="badge bg-{{ $roleColor }}">{{ $roleLabel }}</span>
                </div>
                <small class="text-muted">🕒 {{ $msg->created_at->diffForHumans() }}</small>
            </div>

            <div class="card-body">
                <p class="mb-3">{{ $msg->content }}</p>

                {{-- أدوات الإدارة --}}
                <div class="d-flex justify-content-end">
                    <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger me-2">🗑️ حذف</button>
                    </form>

                    <button class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $msg->id }}">✉️ رد</button>
                </div>

                <div class="collapse mt-3" id="replyForm{{ $msg->id }}">
                    <form method="POST" action="{{ route('messages.reply', $msg->id) }}">
                        @csrf
                        <div class="mb-2">
                            <textarea name="reply_content" class="form-control" rows="3" placeholder="اكتب الرد هنا..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">📤 إرسال الرد</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">لا توجد رسائل حالياً.</div>
    @endforelse

    <div class="d-flex justify-content-center">
        {{ $messages->links() }}
    </div>
</div>
@endsection
