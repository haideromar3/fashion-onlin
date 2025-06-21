@extends('layouts.admin')

@section('title', 'تعديل عارضة')

@section('content')
<div class="container">
    <h2 class="mb-4">تعديل عارضة</h2>

    <form action="{{ route('fashion-models.update', $fashionModel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control" value="{{ $fashionModel->name }}" required>
        </div>

        <div class="mb-3">
            <label>البلد</label>
            <input type="text" name="country" class="form-control" value="{{ $fashionModel->country }}">
        </div>
         <div class="mb-3">
         <label>العمر</label>
        <input type="number" name="age" class="form-control" value="{{ $fashionModel->age }}">
           </div>

             <div class="mb-3">
            <label>البريد الإلكتروني</label>
             <input type="email" name="email" class="form-control" value="{{ $fashionModel->email }}">
             </div>
        <div class="mb-3">
            <label>الانستغرام</label>
            <input type="Email" name="instagram" class="form-control" value="{{ $fashionModel->instagram }}">
        </div>

        <div class="mb-3">
            <label>نبذة</label>
            <textarea name="bio" class="form-control" rows="4">{{ $fashionModel->bio }}</textarea>
        </div>

        <div class="mb-3">
            <label>صورة حالية</label><br>
            @if($fashionModel->image)
                <img src="{{ asset('storage/' . $fashionModel->image) }}" width="100" class="mb-2">
            @else
                لا توجد صورة
            @endif
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
