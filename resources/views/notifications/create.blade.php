@extends('layouts.app')

@section('content')
<div class="container">
    <h1>إرسال إشعار جديد</h1>

    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>النص</label>
            <textarea name="body" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">إرسال</button>
    </form>
</div>
@endsection
