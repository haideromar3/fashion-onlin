@extends('layouts.app')

@section('content')
<div class="container">
    <h1>كل المستخدمين</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('profiles.show', $user->id) }}" class="btn btn-info btn-sm">عرض</a>
                    <a href="{{ route('profiles.edit', $user->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
