@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', '  عروض وحسومات  ')

@section('content')
<div class="container">
    <h1 class="mb-5 text-center text-danger fw-bold">🔥 عروض وحسومات 🔥</h1>

    @if($products->isEmpty())
        <div class="alert alert-warning text-center">لا توجد منتجات في عروض و حسومات حالياً.</div>
    @endif

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-danger h-100">
                    @if ($product->images->count())
                        <div id="carousel-{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             class="d-block w-100 rounded-top"
                                             style="height: 250px; object-fit: cover;"
                                             alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="لا توجد صورة">
                    @endif

                    <div class="card-body text-center">
                        <h5 class="card-title text-danger">{{ $product->name }}</h5>
                        <p class="card-text"><strong>{{ $product->price }} د.أ</strong></p>

                        {{-- قياسات --}}
                        @if($product->sizes)
                            <p class="mb-1">
                                <strong>القياسات:</strong>
                                {{ implode(', ', json_decode($product->sizes, true)) }}
                            </p>
                        @endif

 {{-- ✅ الألوان --}}
@if($product->colors)
    <p>
        <strong>الألوان:</strong>
        @foreach(json_decode($product->colors, true) as $color)
            <span class="badge rounded-pill me-1"
                  style="background-color: {{ $color }};
                         color: {{ in_array($color, ['white', 'yellow']) ? '#000' : '#fff' }};
                         border: 1px solid #ccc;">
                {{ ucfirst($color) }}
            </span>
        @endforeach
    </p>
@endif

                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-danger btn-sm mt-2">عرض المنتج</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- روابط الصفحات --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
