@extends('layouts.app')

@section('content')
<div class="container">
    <h1>كل المفضلات</h1>
    <a href="{{ route('wishlists.create') }}" class="btn btn-primary mb-3">إضافة إلى المفضلة</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>المنتج</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wishlists as $wishlist)
            <tr>
                <td>{{ $wishlist->user_id }}</td>
                <td>{{ $wishlist->product_id }}</td>
                <td>
                    <a href="{{ route('wishlists.show', $wishlist->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('wishlists.edit', $wishlist->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <form action="{{ route('wishlists.destroy', $wishlist->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $wishlists->links() }}
</div>
@endsection
