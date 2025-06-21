@extends('layouts.admin')

@section('title', 'إدارة التصنيفات والأنواع')

@section('content')
<div class="container">
    <h1 class="mb-4">قائمة التصنيفات</h1>

    <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">إضافة تصنيف جديد</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- جدول التصنيفات --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الوصف</th>
                <th>عدد المنتجات</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '—' }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <a href="{{ route('categories.products', $category->id) }}" class="btn btn-sm btn-info">عرض المنتجات</a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">لا توجد تصنيفات بعد.</td></tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h2 class="mt-4">قائمة الأنواع</h2>

    {{-- إضافة نوع جديد --}}
    <form action="{{ route('product-types.store') }}" method="POST" class="mb-3">
        @csrf
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="اسم النوع" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">إضافة نوع</button>
            </div>
        </div>
    </form>

    {{-- جدول الأنواع مع التعديل المباشر --}}
    <table class="table table-bordered">
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
                        <form action="{{ route('product-types.update', $type->id) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $type->name }}" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-sm btn-warning">حفظ</button>
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
