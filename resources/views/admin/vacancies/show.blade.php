@extends('layouts.admin')

@section('title', 'المتقدمين على: ' . $vacancy->title)

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">المتقدمين على شاغر: {{ $vacancy->title }}</h2>

    @if($vacancy->applications->isEmpty())
        <div class="alert alert-info">لا يوجد متقدمين حتى الآن.</div>
    @else
        <div class="row g-3">
            @foreach($vacancy->applications as $app)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $app->full_name }}</h5>
                            <p class="mb-1"><strong>الإيميل:</strong> {{ $app->email }}</p>                            <p class="mb-2"><strong>تاريخ التقديم:</strong> {{ $app->created_at->format('Y-m-d H:i') }}</p>

                            <a href="{{ asset('storage/' . $app->cv_path) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-auto">
                                <i class="fas fa-file-pdf"></i> عرض السيرة الذاتية
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> رجوع إلى قائمة الشواغر
    </a>
</div>
@endsection
