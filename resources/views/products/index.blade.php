@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'المنتجات')

@section('content')
<div class="container py-4">

    <h1 class="mb-4 text-center fw-bold text-primary">المنتجات</h1>

    {{-- أزرار خاصة بالمدراء والموردين والمصممين --}}
    @auth
        @if(in_array(auth()->user()->role, ['admin', 'supplier', 'designer']))
            <div class="d-flex flex-wrap justify-content-start gap-3 mb-4">
                <a href="{{ route('products.create') }}" class="btn btn-success shadow-sm">
                    <i class="fas fa-plus me-2"></i> إضافة منتج
                </a>
                <a href="{{ route('brands.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-tag me-2"></i> إضافة ماركة
                </a>
            </div>
        @endif
    @endauth

    {{-- نموذج البحث للزبائن --}}
    @auth
        @if(auth()->user()->role === 'customer')
            <form method="GET" action="{{ route('products.index') }}" class="mb-5">
                <div class="row g-3 align-items-center justify-content-center">
                    <div class="col-md-4">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control shadow-sm" 
                            placeholder="ابحث عن منتج..." 
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                    </div>

                    <div class="col-md-3">
                        <select name="category" class="form-select shadow-sm">
                            <option value="">كل الأقسام</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="type" class="form-select shadow-sm">
                            <option value="">كل الأنواع</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary shadow-sm" type="submit">
                            <i class="fas fa-search me-2"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        @endif
    @endauth

    {{-- عرض المنتجات --}}
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative">

                    {{-- شارة Black Friday --}}
                    @if($product->is_black_friday)
                        <span class="position-absolute top-0 end-0 bg-danger text-white px-3 py-1 rounded-start shadow-sm animate__animated animate__pulse animate__infinite" style="z-index: 10; font-size: 0.85rem;">
                            🔥 عرض جديد
                        </span>
                    @endif

                    {{-- Carousel الصور --}}
                    @if ($product->images->count())
                        <div id="carousel-{{ $product->id }}" class="carousel slide" data-bs-ride="false" style="max-height: 250px; overflow: hidden;">
                            <div class="carousel-inner">
                                @foreach ($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">السابق</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">التالي</span>
                            </button>
                        </div>

                        {{-- Thumbnails --}}
                        <div class="d-flex justify-content-center mt-2 gap-2" id="thumbnails-{{ $product->id }}">
                            @foreach ($product->images as $index => $image)
                                <img 
                                    src="{{ asset('storage/' . $image->image_path) }}" 
                                    class="img-thumbnail shadow-sm" 
                                    style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border-radius: 0.5rem;"
                                    data-index="{{ $index }}" 
                                    alt="صورة {{ $product->name }}"
                                    loading="lazy"
                                >
                            @endforeach
                        </div>
                    @else
                        <img src="{{ asset('images/no-image.png') }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="لا توجد صورة">
                    @endif


@if($product->average_rating)
    <div class="mb-2">
        @for ($i = 1; $i <= 5; $i++)
            @if($product->average_rating >= $i)
                <i class="fas fa-star text-warning"></i>
            @elseif($product->average_rating >= $i - 0.5)
                <i class="fas fa-star-half-alt text-warning"></i>
            @else
                <i class="far fa-star text-warning"></i>
            @endif
        @endfor
        <small class="text-muted">({{ number_format($product->average_rating, 1) }})</small>
    </div>
@endif




                    <div class="card-body d-flex flex-column justify-content-between">

                        <div>
                            <h5 class="card-title fw-semibold">{{ $product->name }}</h5>

                            <p class="text-muted small mb-2" style="min-height: 60px;">{{ Str::limit($product->description, 100, '...') }}</p>

              {{-- السعر والخصم --}}
@if ($product->discount)
    @php
        // حساب السعر الأصلي قبل الخصم تقريبيًا
        $originalPrice = $product->price + ($product->price * $product->discount / (100 - $product->discount));
    @endphp
    <div class="mb-2 fs-5">
        {{-- السعر بعد الخصم (أعلى) --}}
        <div class="text-danger fw-bold">
            {{ number_format($product->price * ($conversionRate ?? 1), 2) }} {{ $currency ?? 'ر.س' }}
        </div>

        {{-- السعر الأصلي (مخطوط بالكامل مع العملة) تحت السعر الجديد --}}
        <div class="text-muted" style="font-size: 0.9rem;">
            <del>{{ number_format($originalPrice * ($conversionRate ?? 1), 2) }} {{ $currency ?? 'ر.س' }}</del>
        </div>

        {{-- علامة الخصم --}}
        <span class="badge bg-success mt-1">خصم {{ $product->discount }}%</span>
    </div>
@else
    <p class="mb-2 fs-5 fw-bold">
        {{ number_format($product->price * ($conversionRate ?? 1), 2) }} {{ $currency ?? 'ر.س' }}
    </p>
@endif


                            {{-- رسالة نفاد الكمية --}}
                            @if (isset($product->stock) && $product->stock <= 0)
                                <div class="alert alert-danger text-center py-1 mb-3">
                                    نفدت الكمية (المخزون: {{ intval($product->stock) }})
                                </div>
                            @elseif(isset($product->stock) && $product->stock <= 5)
                                <div class="alert alert-warning text-center py-1 mb-3">
                                    الكمية المتبقية قليلة ({{ intval($product->stock) }})
                                </div>
                            @endif

                            {{-- المقاسات --}}
                            @if($product->sizes && is_array(json_decode($product->sizes, true)))
                                <div class="mb-2">
                                    <small class="text-muted fw-semibold">المقاسات:</small><br>
                                    @foreach(json_decode($product->sizes, true) as $size)
                                        <span class="badge bg-secondary me-1 px-3 py-1 rounded-pill">{{ strtoupper($size) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- الألوان --}}
                            @if($product->colors)
                                <div>
                                    <small class="text-muted fw-semibold">الألوان:</small><br>
                                    @foreach(json_decode($product->colors, true) as $color)
                                        <span 
                                            class="badge rounded-pill me-1 px-3 py-1"
                                            style="background-color: {{ $color == 'white' ? '#f8f9fa' : $color }};
                                                   color: {{ in_array($color, ['white', 'yellow']) ? '#000' : '#fff' }};
                                                   border: 1px solid #ccc;">
                                            {{ $color }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- أزرار التفاعل --}}
                        <div class="mt-3 d-grid gap-2">

                            {{-- زر الإضافة إلى السلة أو رابط تسجيل الدخول --}}
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-primary add-to-cart shadow-sm"
                                        data-product-id="{{ $product->id }}"
                                    >
                                        <i class="fas fa-shopping-cart me-2"></i> أضف إلى السلة
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary shadow-sm">
                                    <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول للشراء
                                </a>
                            @endauth

                            {{-- زر عرض التفاصيل --}}
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-info-circle me-2"></i> عرض التفاصيل
                            </a>

              {{-- إذا كان المستخدم مسؤول أو مورد أو مصمم، أظهر أزرار التعديل والحذف --}}
@auth
    @if(in_array(auth()->user()->role, ['admin', 'supplier', 'designer']))
        <div class="d-flex gap-2">
            {{-- زر تعديل المنتج --}}
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit me-1"></i> تعديل المنتج
            </a>

            {{-- زر حذف المنتج --}}
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="fas fa-trash-alt me-1"></i> حذف المنتج
                </button>
            </form>
        </div>
    @endif
@endauth


                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    لا توجد منتجات متاحة حالياً.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // عند الضغط على الصور المصغرة، تغيير صورة الكاروسيل الرئيسية
        document.querySelectorAll('[id^="thumbnails-"]').forEach(container => {
            const carouselId = container.id.replace('thumbnails-', 'carousel-');
            const carouselElement = document.getElementById(carouselId);
            const carousel = bootstrap.Carousel.getInstance(carouselElement) || new bootstrap.Carousel(carouselElement);

            container.querySelectorAll('img').forEach(img => {
                img.addEventListener('click', () => {
                    const index = parseInt(img.dataset.index);
                    carousel.to(index);
                });
            });
        });

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        // عرض رسالة تطلب من المستخدم اختيار القياس واللون
        alert('يرجى اختيار القياس واللون قبل إضافة المنتج إلى السلة');
    });
});

    });
</script>
@endsection
