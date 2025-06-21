@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container">
    <h2 class="mb-4">إنشاء تدوينة جديدة</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">عنوان التدوينة</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">المحتوى</label>
            <textarea name="content" id="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">صورة التدوينة (اختياري)</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_published" id="is_published" class="form-check-input" checked>
            <label for="is_published" class="form-check-label">نشر التدوينة مباشرة</label>
        </div>

        <button type="submit" class="btn btn-primary">نشر التدوينة</button>
    </form>
</div>
@endsection
