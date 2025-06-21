@extends('layouts.admin')

@section('title', 'تعديل الشاغر')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">تعديل الشاغر: {{ $vacancy->title }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>خطأ!</strong> يرجى تصحيح الأخطاء التالية:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vacancies.update', $vacancy->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">عنوان الشاغر</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $vacancy->title) }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">وصف الشاغر</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $vacancy->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary">رجوع</a>
    </form>
</div>
@endsection
