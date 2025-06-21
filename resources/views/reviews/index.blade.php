@extends('layouts.app')

@section('content')
<div class="container">
    <h1>كل التقييمات</h1>
    <a href="{{ route('reviews.create') }}" class="btn btn-primary mb-3">إضافة تقييم جديد</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>المنتج</th>
                <th>التقييم</th>
                <th>التعليق</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->product_id }}</td>
                <td>{{ $review->rating }}</td>
                <td>{{ Str::limit($review->comment, 50) }}</td>
                <td>
                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reviews->links() }}
</div>
@endsection
