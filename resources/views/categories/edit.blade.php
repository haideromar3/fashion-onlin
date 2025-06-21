@extends('layouts.admin')

@section('title', 'تعديل الفئة')

@section('content')
<div class="container">
    <h1>تعديل الفئة</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">اسم الفئة</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">تحديث</button>
    </form>
</div>@extends('layouts.admin')

@section('title', 'تعديل الفئة')

@section('content')
<div class="container">
    <h1>تعديل الفئة</h1>

    {{-- تعديل بيانات الفئة --}}
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">اسم الفئة</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">تحديث الفئة</button>
    </form>

    <hr>

    {{-- إدارة الأنواع --}}
    <h2 class="mt-5">أنواع المنتجات</h2>

    {{-- نموذج إضافة نوع --}}
    <form action="{{ route('product-types.store') }}" method="POST" class="row g-3 mt-3">
        @csrf
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="اسم النوع" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">إضافة نوع</button>
        </div>
    </form>

    {{-- قائمة الأنواع --}}
    <table class="table table-bordered mt-4">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>
                        {{-- نموذج تعديل داخل الجدول --}}
                        <form action="{{ route('product-types.update', $type->id) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $type->name }}" class="form-control me-2" style="width: 70%;" required>
                            <button type="submit" class="btn btn-sm btn-warning">تحديث</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('product-types.destroy', $type->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف النوع؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@endsection
