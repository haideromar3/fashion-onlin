@extends('layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('styles')
<style>
    body {
        background-image: url({{ asset('images/login-bg.avif') }});
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 120vh;
        font-family: 'Cairo', sans-serif;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid #f3d4eb;
    }

    .card-header {
        background: linear-gradient(to right, #e75480, #ffb6c1);
        color: white;
        font-size: 1.6rem;
        font-weight: bold;
        text-align: center;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
    }

    .form-label {
        color: #e75480;
        font-weight: 500;
    }

    .btn-success {
        background-color: #e75480;
        border-color: #e75480;
    }

    .btn-success:hover {
        background-color: #d63f6c;
        border-color: #d63f6c;
    }

    .invalid-feedback {
        display: block;
        font-size: 0.9rem;
        color: #d63f6c;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card register-card rounded-4">
                <div class="card-header">
                    ✨ إنشاء حساب جديد
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- الاسم الكامل --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">👤 الاسم الكامل</label>
                            <input id="name" type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- البريد الإلكتروني --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">📧 البريد الإلكتروني</label>
                            <input id="email" type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">🔒 كلمة المرور</label>
                            <input id="password" type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تأكيد كلمة المرور --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">🔒 تأكيد كلمة المرور</label>
                            <input id="password-confirm" type="password" name="password_confirmation"
                                   class="form-control" required>
                        </div>

                        {{-- زر التسجيل --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                ✅ إنشاء الحساب
                            </button>
                        </div>
                    </form>

                    {{-- رابط تسجيل الدخول --}}
                    <div class="text-center mt-3">
                        لديك حساب بالفعل؟ <a href="{{ route('login') }}">سجّل الدخول من هنا</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
