@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded">
        <div class="card-body">
            <h2 class="card-title mb-3">{{ $blog->title }}</h2>

            <p class="text-muted">
                بقلم: <strong>{{ $blog->user->name ?? 'مستخدم مجهول' }}</strong>
                | {{ $blog->created_at->translatedFormat('d M Y') }}
            </p>

            @if ($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="صورة التدوينة" class="img-fluid mb-4 rounded shadow-sm">
            @endif

            <p class="card-text fs-5">{!! nl2br(e($blog->content)) !!}</p>

            {{-- أزرار الإدارة تظهر فقط للكاتب أو الأدمن --}}
            @if(auth()->user()->id === $blog->user_id || auth()->user()->role === 'admin')
                <div class="mt-4">
                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>

                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> حذف
                        </button>
                    </form>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للمدونة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
