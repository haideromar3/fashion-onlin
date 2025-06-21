@extends('layouts.admin')

@section('title', 'تفاصيل الفئة')

@section('content')
<div class="container">
    <h1>تفاصيل الفئة</h1>

    <ul>
        <li><strong>الاسم:</strong> {{ $category->name }}</li>
        <li><strong>الوصف:</strong> {{ $category->description ?? '—' }}</li>
    </ul>

    <a href="{{ route('categories.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>
</div>
@endsection
