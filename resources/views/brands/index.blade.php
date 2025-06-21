@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'العلامات التجارية')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">🏷️ العلامات التجارية</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- زر إضافة علامة تجارية للمصمم/المورد/الادمن --}}
    @if(in_array(auth()->user()->role, ['admin', 'designer', 'supplier']))
        <div class="text-end mb-4">
            <a href="{{ route('brands.create') }}" class="btn btn-success">➕ إضافة علامة تجارية</a>
        </div>
    @endif

    <div class="row">
        @forelse ($brands as $brand)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100 border-0">
                    {{-- شعار العلامة --}}
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="شعار {{ $brand->name }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="لا يوجد شعار">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $brand->name }}</h5>
                        <p class="card-text text-muted">{{ $brand->description ?? '— لا يوجد وصف' }}</p>
                    </div>

                    {{-- أزرار تعديل وحذف --}}
                    @if(in_array(auth()->user()->role, ['admin', 'designer', 'supplier']))
                        <div class="card-footer bg-white d-flex justify-content-between">
                            <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">✏️ تعديل</a>

                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه العلامة؟')" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ حذف</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">لا توجد علامات تجارية حالياً.</div>
        @endforelse
    </div>
</div>
@endsection
