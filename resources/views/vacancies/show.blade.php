@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')
@section('title', $vacancy->title)
@section('content')
    <h3>{{ $vacancy->title }}</h3>
    <p>{{ $vacancy->description }}</p>

    <h5>تقديم على الشاغر</h5>
    <form action="{{ route('applications.store', $vacancy->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" class="form-control mb-2" placeholder="الاسم الكامل" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="البريد الإلكتروني" required>
        <textarea name="message" class="form-control mb-2" placeholder="رسالة (اختياري)"></textarea>
        <input type="file" name="cv" class="form-control mb-2" required>
        <button class="btn btn-primary">إرسال</button>
    </form>
@endsection
