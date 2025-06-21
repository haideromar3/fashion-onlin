@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'الوظائف المتاحة')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center text-primary fw-bold">الوظائف المتاحة</h2>

    @if($vacancies->isEmpty())
        <div class="alert alert-info text-center">
            لا توجد وظائف متاحة حاليًا.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($vacancies as $vacancy)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $vacancy->title }}</h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($vacancy->description, 100) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('vacancies.show', $vacancy) }}" class="btn btn-outline-primary w-100">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                        <div class="card-footer text-muted text-end small">
                            آخر تحديث: {{ $vacancy->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
