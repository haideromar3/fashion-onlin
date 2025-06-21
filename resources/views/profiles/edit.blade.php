@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container mt-5">
    {{-- العنوان --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">تعديل الملف الشخصي</h2>
    </div>

    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- تنبيه عند وجود أخطاء في كلمة المرور --}}
    @if($errors->has('current_password') || $errors->has('new_password'))
        <div class="alert alert-danger mt-3">
            تأكد من إدخال كلمة المرور الحالية بشكل صحيح، وأن كلمة المرور الجديدة متطابقة مع التأكيد.
        </div>
    @endif

    {{-- نموذج التعديل --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
            {{-- الاسم الأول --}}
            <div class="col-md-6">
                <label for="first_name" class="form-label">الاسم الأول</label>
                <input type="text" id="first_name" name="first_name" 
                    class="form-control @error('first_name') is-invalid @enderror" 
                    value="{{ old('first_name', explode(' ', $user->name)[0] ?? '') }}" 
                    required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- الاسم الأخير --}}
            <div class="col-md-6">
                <label for="last_name" class="form-label">الاسم الأخير</label>
                <input type="text" id="last_name" name="last_name" 
                    class="form-control @error('last_name') is-invalid @enderror" 
                    value="{{ old('last_name', explode(' ', $user->name)[1] ?? '') }}" 
                    required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- البريد الإلكتروني (غير قابل للتعديل) --}}
            <div class="col-12">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            {{-- النبذة --}}
            <div class="col-12">
                <label for="bio" class="form-label">النبذة</label>
                <textarea id="bio" name="bio" rows="4" 
                    class="form-control @error('bio') is-invalid @enderror" 
                    placeholder="اكتب نبذة قصيرة عن نفسك">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- رقم الهاتف --}}
            <div class="col-md-6">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone', $profile->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- العنوان --}}
            <div class="col-md-6">
                <label for="address" class="form-label">العنوان</label>
                <input type="text" id="address" name="address" 
                    class="form-control @error('address') is-invalid @enderror" 
                    value="{{ old('address', $profile->address) }}">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- الدولة --}}
            <div class="col-md-6">
                <label for="country" class="form-label">الدولة</label>
                <input type="text" id="country" name="country" 
                    class="form-control @error('country') is-invalid @enderror" 
                    value="{{ old('country', $profile->country) }}">
                @error('country')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

  
        </div>

        <h4 class="fw-bold my-4">تغيير كلمة المرور</h4>

        {{-- كلمة المرور الحالية --}}
        <div class="mb-3">
            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- كلمة المرور الجديدة --}}
        <div class="mb-3">
            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- تأكيد كلمة المرور الجديدة --}}
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
        </div>

        {{-- زر الحفظ --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection
