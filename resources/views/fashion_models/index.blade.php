@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'قائمة العارضات')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">العارضات</h2>

    @auth
        @if(auth()->user()->role === 'admin')
            <div class="text-center mb-4">
                <a href="{{ route('fashion-models.create') }}" class="btn btn-success">➕ إضافة عارضة جديدة</a>
            </div>
        @endif
    @endauth

    {{-- نموذج الفلترة --}}
    <form method="GET" class="row mb-4 g-3 align-items-end">
        <div class="col-md-4">
            <label>اختر البلد</label>
            <select name="country" class="form-select">
                <option value="">كل البلدان</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>العمر</label>
            <input type="number" name="age" class="form-control" value="{{ request('age') }}" placeholder="العمر">
        </div>

        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-primary w-100">تصفية</button>
            <a href="{{ route('fashion-models.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- كروت العارضات --}}
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($fashionModels as $model)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($model->image)
                        <img src="{{ asset('storage/' . $model->image) }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="{{ $model->name }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="لا توجد صورة">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $model->name }}</h5>
                        <p class="card-text mb-1"><strong>البلد:</strong> {{ $model->country ?? '—' }}</p>
                        <p class="card-text mb-1"><strong>العمر:</strong> {{ $model->age ?? '—' }}</p>
                        <p class="card-text mb-3 text-truncate"><strong>نبذة:</strong> {{ $model->bio ?? '—' }}</p>

                        <div class="mt-auto d-flex justify-content-between">
                            {{-- زر عرض للجميع المصرح لهم --}}
                            <a href="{{ route('fashion-models.show', $model->id) }}" class="btn btn-info btn-sm">عرض</a>

                            {{-- أزرار التعديل والحذف للمدراء فقط --}}
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div>
                                        <a href="{{ route('fashion-models.edit', $model->id) }}" class="btn btn-warning btn-sm me-1">تعديل</a>
                                        <form action="{{ route('fashion-models.destroy', $model->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف العارضة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">لا توجد عارضات حالياً.</p>
        @endforelse
    </div>

    {{-- روابط الصفحات --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $fashionModels->links() }}
    </div>
</div>
@endsection
