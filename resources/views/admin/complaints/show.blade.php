@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <h3>تفاصيل الشكوى رقم #{{ $complaint->id }}</h3>
    <div class="card shadow-sm p-3">
        <p><strong>الزبون:</strong> {{ $complaint->user->name }}</p>
        <p><strong>الشكوى:</strong> {{ $complaint->message }}</p>
        <p><strong>الرد:</strong> 
            @if($complaint->reply)
                {{ $complaint->reply }}
            @else
                <em>لا يوجد رد حتى الآن</em>
            @endif
        </p>
        <p><strong>آخر تحديث:</strong> {{ $complaint->updated_at->format('Y-m-d H:i') }}</p>
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary mt-3">عودة لقائمة الشكاوى</a>
    </div>
</div>
@endsection
