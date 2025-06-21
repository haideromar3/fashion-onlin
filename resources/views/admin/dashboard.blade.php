@extends('layouts.admin')

@section('title', 'الرئيسية')

@section('content')
<h1 class="mb-4 fw-bold text-primary"> لوحة تحكم الإدارة</h1>

{{-- الكروت الأساسية --}}
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد المستخدمين</h6>
                <h4 class="card-text">{{ \App\Models\User::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد المنتجات</h6>
                <h4 class="card-text">{{ \App\Models\Product::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد الفئات</h6>
                <h4 class="card-text">{{ \App\Models\Category::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد الطلبات</h6>
                <h4 class="card-text">{{ \App\Models\Order::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-dark shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد العارضات</h6>
                <h4 class="card-text">{{ \App\Models\FashionModel::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد الشواغر</h6>
                <h4 class="card-text">{{ \App\Models\Vacancy::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body text-center">
                <h6 class="card-title">عدد التدوينات</h6>
                <h4 class="card-text">{{ \App\Models\Blog::count() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
    <div class="card text-white bg-teal shadow" style="background-color: #20c997 !important;">
        <div class="card-body text-center">
            <h6 class="card-title">عدد الزبائن</h6>
            <h4 class="card-text">{{ \App\Models\User::where('role', 'customer')->count() }}</h4>
        </div>
    </div>
</div>


</div>

{{-- 🔥 أحدث المنتجات --}}
<div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
        <span>🛍️ أحدث المنتجات</span>
        <span class="badge bg-primary">{{ \App\Models\Product::count() }} منتج</span>
    </div>
    <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-hover table-sm align-middle mb-0">
            <thead class="table-light sticky-top">
                <tr>
                    <th>الاسم</th>
                    <th>السعر</th>
                    <th>المنشئ</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Models\Product::with('creator')->latest()->take(5)->get() as $product)
                <tr>
                    <td class="text-nowrap">{{ $product->name }}</td>
                    <td class="text-success fw-bold">{{ number_format($product->price) }} ل.س</td>
                    <td>
                        @php
                            $creator = $product->creator;
                            $role = $creator->role ?? 'مستخدم';
                            $role_badge = [
                                'admin' => 'badge bg-danger',
                                'supplier' => 'badge bg-info text-dark',
                                'designer' => 'badge bg-warning text-dark',
                                'user' => 'badge bg-secondary'
                            ][$role] ?? 'badge bg-secondary';
                        @endphp
                        <span class="{{ $role_badge }}">{{ $creator->name ?? 'غير معروف' }} ({{ $role }})</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


{{-- ✏️ إحصائيات سريعة --}}
<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="bi bi-chat-left-text-fill fs-2 text-primary"></i>
                <h6 class="mt-2">عدد التعليقات</h6>
                <p class="fs-5">{{ \App\Models\Comment::count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="bi bi-star-fill fs-2 text-warning"></i>
                <h6 class="mt-2">عدد التقييمات</h6>
                <p class="fs-5">{{ \App\Models\Review::count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="bi bi-heart-fill fs-2 text-danger"></i>
                <h6 class="mt-2">عدد الإعجابات</h6>
<p class="fs-5">{{ \App\Models\BlogLike::count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <i class="bi bi-eye-fill fs-2 text-info"></i>
              <h6 class="mt-2">عدد الشكاوى</h6>
<p class="fs-5">{{ \App\Models\Complaint::count() }}</p>

            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endpush
