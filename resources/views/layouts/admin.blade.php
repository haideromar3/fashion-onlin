<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'لوحة التحكم')</title>

    {{-- Bootstrap 5.3 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- Bootstrap & App Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

    {{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>

    <style>
        body {
            background-color: #eef1f5;
            padding-bottom: 140px;
        }
        .navbar-brand img {
            border-radius: 50%;
        }
        .navbar .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #fff;
        }
        .card {
            background-color: #fff;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .table {
            background-color: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .table th {
            background-color: #f1f3f6;
            font-weight: bold;
        }
           footer.fixed-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #1f1f1f;
        color: #e0e0e0;
        z-index: 1030;
        padding: 10px 0;
        font-size: 0.85rem;
        box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.3);
    }

    footer.fixed-footer a {
        color: #ccc;
        text-decoration: none;
        margin: 0 5px;
        transition: color 0.3s;
    }

    footer.fixed-footer a:hover {
        color: #fff;
    }

    footer.fixed-footer i {
        font-size: 1rem;
    }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top" aria-label="لوحة تحكم التنقل">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" width="32" height="32" />
                <span>لوحة التحكم</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="تبديل التنقل">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- روابط لوحة التحكم --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> المستخدمين</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-box"></i> المنتجات</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}"><i class="fas fa-tags"></i> الفئات</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('fashion-models.index') }}"><i class="fas fa-user-tie"></i> العارضات</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('messages.index') }}"><i class="fas fa-envelope"></i> الرسائل</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports.index') }}"><i class="fas fa-chart-line"></i> التقارير</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('blogs.index') }}"><i class="fas fa-newspaper"></i> المدونة</a></li>
                    <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('products.black_friday') }}"><i class="fas fa-fire"></i> العروض و الحسومات</a></li>

                    {{-- فقط للمسؤولين --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.vacancies.index') }}"><i class="fas fa-briefcase"></i> إدارة الشواغر</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.complaints.index') }}"><i class="fas fa-exclamation-circle"></i> الشكاوى</a></li>
                    @endif

                    {{-- المصمم/المورد --}}
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'designer', 'supplier']))
                    <li class="nav-item"><a class="nav-link" href="{{ route('brands.index') }}"><i class="fas fa-trademark"></i> العلامات التجارية</a></li>
                    @endif
                </ul>

                {{-- إشعارات الرسائل والشكاوى --}}
                @php
                    $user = auth()->user();
                    $notifications = $user->notifications()->orderBy('created_at', 'desc')->take(5)->get();
                    $unreadCount = $user->unreadNotifications->count();
                @endphp



                </ul>

                {{-- معلومات المستخدم --}}
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-white"></i>
                        <span class="text-white">{{ Auth::user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}" class="mb-0 ms-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger p-0" style="text-decoration: none;">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </button>
                        </form>
                    </li>
                </ul>

            </div> {{-- نهاية collapse navbar-collapse --}}
        </div> {{-- نهاية container-fluid --}}
    </nav>

    {{-- محتوى الصفحة --}}
    <main class="container mt-5 pt-4">
        @yield('content')
    </main>

    {{-- الفوتر --}}
    <footer class="fixed-footer text-center">
        &copy; {{ date('Y') }} جميع الحقوق محفوظة - موقعك
    </footer>
@yield('scripts')

    @stack('scripts')
</body>
</html>
