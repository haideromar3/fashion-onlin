@extends('layouts.admin')

@section('title', 'إضافة شاغر وظيفي')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">إضافة شاغر وظيفي جديد</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>خطأ!</strong> يرجى تصحيح الأخطاء التالية:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vacancies.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">عنوان الشاغر</label>
            <input type="text" name="title" class="form-control" placeholder="مثال: مصمم واجهات UI/UX" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">وصف الشاغر</label>
            <textarea name="description" class="form-control" rows="5" placeholder="اكتب تفاصيل الوظيفة هنا...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">نشر الشاغر</button>
        <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
