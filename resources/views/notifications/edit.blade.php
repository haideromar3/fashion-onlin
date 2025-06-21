@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل الإشعار</h1>

    <form action="{{ route('notifications.update', $notification->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" value="{{ $notification->title }}" required>
        </div>
        <div class="mb-3">
            <label>النص</label>
            <textarea name="body" class="form-control" rows="4" required>{{ $notification->body }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
