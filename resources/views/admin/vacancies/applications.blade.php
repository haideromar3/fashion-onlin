@extends('layouts.app')
@section('title', 'المتقدمين - ' . $vacancy->title)

@section('content')
    <div class="container">
        <h2 class="mb-4">المتقدمين على شاغر: {{ $vacancy->title }}</h2>

        @if($applications->isEmpty())
            <div class="alert alert-info">لا يوجد متقدمين حتى الآن.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>الاسم</th>
                        <th>الإيميل</th>
                        <th>رقم الهاتف</th>
                        <th>CV</th>
                        <th>تاريخ التقديم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->full_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->phone ?? '---' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $app->cv_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    عرض CV
                                </a>
                            </td>
                            <td>{{ $app->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
