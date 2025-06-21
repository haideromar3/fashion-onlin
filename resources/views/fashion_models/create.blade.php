@extends('layouts.admin')

@section('title', 'إضافة عارضة')

@section('content')
<div class="container">
    <h2 class="mb-4">إضافة عارضة جديدة</h2>

    {{-- عرض رسائل الأخطاء --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fashion-models.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label>البلد</label>
            <input type="text" name="country" class="form-control" value="{{ old('country') }}">
        </div>

        <div class="mb-3">
            <label>العمر</label>
            <input type="number" name="age" class="form-control" value="{{ old('age') }}">
        </div>

        <div class="mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>رابط الانستغرام</label>
            <input type="url" name="instagram" class="form-control" value="{{ old('instagram') }}" placeholder="https://instagram.com/username">
        </div>

        <div class="mb-3">
            <label>نبذة عنها</label>
            <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
        </div>

        <div class="mb-3">
            <label>الصورة (jpg, jpeg, png فقط)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
    <label for="extra_images" class="form-label">صور إضافية</label>
    <input type="file" name="extra_images[]" class="form-control" multiple>
</div>

 
        <button class="btn btn-success">💾 حفظ العارضة</button>
    </form>
</div>
@endsection
