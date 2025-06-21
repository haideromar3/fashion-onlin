@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'تفاصيل المنتج')

@section('content')

<style>
    .star {
        font-size: 28px;
        cursor: pointer;
        color: #ccc;
        transition: color 0.2s;
    }
    .star.hovered,
    .star.selected {
        color: gold;
    }
</style>

<div class="container">
    <h1 class="mb-4 text-center">{{ $product->name }}</h1>

    {{-- الصور --}}
    @if ($product->images->count())
        <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($product->images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             class="d-block mx-auto img-fluid rounded"
                             style="max-width: 400px; height: auto; object-fit: contain;"
                             alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>
            @if ($product->images->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>

        {{-- الصور المصغرة --}}
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
            @foreach ($product->images as $index => $image)
                <img src="{{ asset('storage/' . $image->image_path) }}"
                     class="img-thumbnail"
                     style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                     onclick="document.querySelector('#productCarousel .carousel-item.active').classList.remove('active');
                              document.querySelectorAll('#productCarousel .carousel-item')[{{ $index }}].classList.add('active');">
            @endforeach
        </div>
    @else
        <img src="{{ asset('images/no-image.png') }}" class="img-fluid mb-4 d-block mx-auto" style="max-width: 300px;" alt="لا توجد صورة">
    @endif

    @php
        $currency = session('currency', 'USD');
        $rates = config('helpers.rates', []);
        $symbols = config('helpers.symbols', []);

        $rate = $rates[$currency] ?? 1;
        $symbol = $symbols[$currency] ?? '$';

        $convertedPrice = $product->price * $rate;
    @endphp

    {{-- اختيار العملة --}}
    <div class="mb-3">
        <label for="currency-select" class="form-label"><strong>اختر العملة:</strong></label>
        <select id="currency-select" class="form-select" onchange="window.location.href=this.value;">
            @foreach($rates as $code => $rate)
                <option value="{{ route('set.currency', $code) }}" {{ $currency == $code ? 'selected' : '' }}>
                    {{ $code }} ({{ $symbols[$code] ?? '' }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- تفاصيل المنتج --}}
    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>الوصف:</strong> {{ $product->description ?? 'لا يوجد' }}</li>

        <li class="list-group-item">
            <strong>السعر:</strong>
            @if ($product->discount)
                @php
                    $originalPrice = $convertedPrice / (1 - $product->discount / 100);
                @endphp
                <del>{{ number_format($originalPrice, 2) }} {{ $symbol }}</del>
                <strong>{{ number_format($convertedPrice, 2) }} {{ $symbol }}</strong>
                <span class="badge bg-success">خصم {{ $product->discount }}%</span>
            @else
                <strong>{{ number_format($convertedPrice, 2) }} {{ $symbol }}</strong>
            @endif
        </li>

        <li class="list-group-item"><strong>الكمية:</strong> {{ $product->stock }}</li>
        <li class="list-group-item"><strong>القسم:</strong> {{ $product->category->name ?? 'لا يوجد' }}</li>
        <li class="list-group-item"><strong>النوع:</strong> {{ $product->productType->name ?? 'لا يوجد' }}</li>
        <li class="list-group-item"><strong>الماركة:</strong> {{ $product->brand->name ?? 'لا يوجد' }}</li>
        <li class="list-group-item"><strong>تمت الإضافة بواسطة:</strong> {{ $product->user->name ?? 'غير معروف' }}</li>
        <li class="list-group-item"><strong>تاريخ الإضافة:</strong> {{ $product->created_at->format('Y-m-d') }}</li>
    </ul>

    {{-- القياسات --}}
    @if($product->sizes)
        <div class="mb-3">
            <strong>القياسات المتوفرة:</strong>
            @foreach(json_decode($product->sizes, true) as $size)
                <span class="badge bg-secondary me-1">{{ strtoupper($size) }}</span>
            @endforeach
        </div>
    @endif

    {{-- الألوان --}}
    @if($product->colors)
        <div class="mb-3">
            <strong>الألوان المتاحة:</strong>
            @foreach(json_decode($product->colors, true) as $color)
                <span class="badge rounded-pill me-1"
                      style="background-color: {{ $color == 'white' ? '#f8f9fa' : $color }};
                             color: {{ in_array($color, ['yellow', 'white']) ? '#000' : '#fff' }};
                             border: 1px solid #ccc;">
                    {{ __('colors.' . strtolower($color), [], ucfirst($color)) }}
                </span>
            @endforeach
        </div>
    @endif

    {{-- نموذج إضافة إلى السلة باستخدام AJAX --}}
    @if ($product->stock > 0)
        <form id="addToCartForm" class="mb-5">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @if($product->sizes)
            <div class="mb-3">
                <label class="form-label">اختر القياس:</label>
                <select name="size" class="form-select" required>
                    @foreach(json_decode($product->sizes, true) as $size)
                        <option value="{{ $size }}">{{ strtoupper($size) }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if($product->colors)
            <div class="mb-3">
                <label class="form-label">اختر اللون:</label>
                <select name="color" class="form-select" required>
                    @foreach(json_decode($product->colors, true) as $color)
                        <option value="{{ $color }}">{{ ucfirst($color) }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">الكمية المطلوبة:</label>
                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
            </div>

            <div class="mt-3">
                @if($product->stock > 10)
                    <span class="badge bg-success">متوفر: {{ $product->stock }} قطعة</span>
                @elseif($product->stock > 0)
                    <span class="badge bg-warning text-dark">كمية محدودة: {{ $product->stock }} قطعة</span>
                @else
                    <span class="badge bg-danger">نفدت الكمية</span>
                @endif
            </div>

            <button type="submit" id="addToCartBtn" class="btn btn-primary mt-3">أضف إلى السلة</button>
        </form>
    @else
        <div class="alert alert-warning mt-3">المنتج غير متوفر حالياً</div>
    @endif

    {{-- نموذج التقييم --}}
    @if(auth()->check())
        <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="mb-4" id="rating-form">
            @csrf
            <h5>أضف تقييمك:</h5>

            <div class="mb-2">
                <div id="star-rating" class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">&#9733;</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="0" required>
            </div>

            <div class="mb-3">
                <textarea name="comment" rows="3" class="form-control" placeholder="اكتب تعليقك هنا..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success">إرسال التقييم</button>
        </form>
    @endif

    {{-- عرض التقييمات --}}
    <hr>
    <h3>التقييمات ({{ $product->reviews->count() }})</h3>
    @forelse($product->reviews as $review)
        <div class="border p-3 rounded mb-3">
            <strong>{{ $review->user->name ?? 'مستخدم مجهول' }}</strong>
            <span class="text-warning">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        &#9733;
                    @else
                        &#9734;
                    @endif
                @endfor
            </span>
            <p>{{ $review->comment }}</p>
            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
        </div>
    @empty
        <p>لا توجد تقييمات بعد.</p>
    @endforelse

</div>

{{-- جافاسكريبت --}}

<script>
    // نجوم التقييم تفاعلية
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const val = star.dataset.value;
            highlightStars(val);
        });
        star.addEventListener('mouseout', () => {
            highlightStars(ratingInput.value);
        });
        star.addEventListener('click', () => {
            ratingInput.value = star.dataset.value;
            highlightStars(ratingInput.value);
        });
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            if(star.dataset.value <= rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }

    highlightStars(ratingInput.value);

    // إضافة إلى السلة باستخدام AJAX مع SweetAlert وتحديث عداد السلة
    const form = document.getElementById('addToCartForm');
    if(form) {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const formData = new FormData(form);

            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // تحديث عداد السلة في الـ navbar
                    if(data.cart_count !== undefined) {
                        const cartCountElem = document.getElementById('cart-count');
                        if(cartCountElem) {
                            cartCountElem.textContent = data.cart_count;
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'تمت الإضافة!',
                        text: data.message || 'تم إضافة المنتج إلى السلة بنجاح.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.message || 'حدث خطأ أثناء الإضافة.',
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'تعذر الاتصال بالخادم.',
                });
            });
        });
    }
</script>


@endsection
