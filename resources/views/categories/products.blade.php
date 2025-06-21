@extends('layouts.admin')

@section('title', 'منتجات تصنيف: ' . $category->name)

@section('content')
<div class="container">
    <h2 class="mb-4">منتجات تصنيف: {{ $category->name }}</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">العودة للتصنيفات</a>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($product->images->count())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->price }} د.أ</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">لا توجد منتجات في هذا التصنيف.</p>
        @endforelse
    </div>

    {{ $products->links() }}
</div>
@endsection
