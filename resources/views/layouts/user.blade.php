@php
    $user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الموقع - @yield('title')</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <style>
    nav.navbar {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    .navbar .nav-link {
        font-weight: 500;
        color: #333 !important;
        transition: color 0.2s ease;
    }

    .navbar .nav-link:hover {
        color: #0d6efd !important;
    }

    .btn-cart {
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background-color: #dc3545;
        color: white;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 50%;
    }

    .navbar-nav {
        gap: 1rem; /* تباعد متساوي بين العناصر */
    }

    .navbar .btn-sm {
        padding: 5px 10px;
        font-size: 0.875rem;
    }

    .navbar-brand {
        font-weight: bold;
        color: #0d6efd !important;
        font-size: 1.3rem;
    }
</style>

</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">الموقع</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="تبديل التنقل">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- إخفاء القائمة لو الدور delivery --}}
            @if(!$user || $user->role !== 'delivery')
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="fa-solid fa-box-open me-1"></i> المنتجات</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">ℹ️ عنا</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('blogs.index') }}">📰 المدونة</a></li>

                        @if($user && $user->role === 'customer')
                            <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">📦 طلباتي</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('products.black_friday') }}">🔥 عروض وحسومات</a></li>
                        @endif

                        @if($user && in_array($user->role, ['designer', 'supplier', 'customer']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('messages.create') }}">✉️ تواصل معنا</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('fashion-models.index') }}">👗 العارضات</a></li>
                        @endif

                        @if($user)
                            <li class="nav-item"><a class="nav-link" href="{{ route('vacancies.index') }}">💼 فرص العمل</a></li>
                        @endif

                        @if($user && in_array($user->role, ['admin', 'designer', 'supplier']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('brands.index') }}">🏷️ العلامات التجارية</a></li>
                        @endif

                    </ul>
                </div>
            @endif

            {{-- تبقى هذه القائمة ظاهرة دائماً للمستخدم --}}
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                @if($user && $user->role === 'customer')
                    <li class="nav-item me-3">
                        <a href="{{ route('cart.index') }}" class="nav-link btn-cart">
                            <i class="fa-solid fa-cart-shopping"></i> سلتك
                            <span id="cart-count" class="cart-badge">{{ session('cart_count', 0) }}</span>
                        </a>
                    </li>
                @endif

                @if($user)
                    <li class="nav-item me-3">
                        <span class="nav-link text-secondary fw-semibold">{{ $user->name }}</span>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('profile.edit') }}">👤 الملف الشخصي</a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm" type="submit">تسجيل الخروج</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">إنشاء حساب</a>
                    </li>
                @endif
            </ul>

        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    @include('layouts.footer')

    @yield('scripts')
    @stack('scripts')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
