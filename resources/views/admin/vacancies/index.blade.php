@extends('layouts.admin')

@section('title', 'قائمة الشواغر الوظيفية')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">قائمة الشواغر الوظيفية</h2>

    <a href="{{ route('admin.vacancies.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> إضافة شاغر جديد
    </a>

    @if ($vacancies->isEmpty())
        <div class="alert alert-info">لا يوجد شواغر حالياً.</div>
    @else
        <div class="row g-3">
            @foreach ($vacancies as $vacancy)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $vacancy->title }}</h5>
                            <p class="card-text text-secondary flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($vacancy->description, 100, '...') }}
                            </p>
                            <p class="mb-2">
                                <strong>عدد المتقدمين:</strong> {{ $vacancy->applications()->count() }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between">
                                <a href="{{ route('admin.vacancies.show', $vacancy->id) }}" class="btn btn-info btn-sm" title="عرض المتقدمين">
                                    <i class="fas fa-users"></i> المتقدمين
                                </a>
                                <a href="{{ route('admin.vacancies.edit', $vacancy->id) }}" class="btn btn-warning btn-sm" title="تعديل الشاغر">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vacancies.destroy', $vacancy->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الشاغر؟')" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="حذف الشاغر">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
