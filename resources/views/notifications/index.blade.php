@extends('layouts.app')

@section('content')
<div class="container">
    <h1>كل الإشعارات</h1>
    <a href="{{ route('notifications.create') }}" class="btn btn-primary mb-3">إرسال إشعار جديد</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>النص</th>
                <th>الخيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{ $notification->title }}</td>
                <td>{{ Str::limit($notification->body, 50) }}</td>
                <td>
                    <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('notifications.edit', $notification->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $notifications->links() }}
</div>
@endsection
