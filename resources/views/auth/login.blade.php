@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('styles')

<style>
    body {
        background-image: url({{ asset('images/ecom1.jpg') }});
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 50vh;
        font-family: 'Cairo', sans-serif;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid #f3d4eb;
        transition: transform 0.3s ease;
    }

    .login-card:hover {
        transform: scale(1.01);
    }

    .card-header {
        background: linear-gradient(to right, #e75480, #ffb6c1);
        color: white;
        font-size: 1.5rem;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .btn-primary {
        background-color: #e75480;
        border-color: #e75480;
    }

    .btn-primary:hover {
        background-color: #d63f6c;
        border-color: #d63f6c;
    }

    .btn-secondary {
        background-color: #fff0f5;
        color: #e75480;
        border: 1px solid #e75480;
    }

    .btn-secondary:hover {
        background-color: #ffe4ec;
        color: #d63f6c;
    }

    .form-label {
        color: #e75480;
        font-weight: 500;
    }

    .card-footer a {
        color: #e75480;
        font-weight: bold;
    }

    .card-footer {
        background-color: transparent;
        border-top: none;
    }
</style>


@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card login-card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fs-4">
                    🔐 تسجيل الدخول
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- البريد الإلكتروني --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">📧 البريد الإلكتروني</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">🔒 كلمة المرور</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تذكرني --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">تذكرني</label>
                        </div>

                        {{-- الأزرار --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                🚪 دخول
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                    هل نسيت كلمة المرور؟
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- الدخول كزائر --}}
                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">
                            الدخول كزائر
                        </a>
                    </div>
                </div>

                <div class="card-footer text-center text-muted">
                    ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- يمكنك إضافة أي JavaScript هنا لاحقًا --}}
@endsection
