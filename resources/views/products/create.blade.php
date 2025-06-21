@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">🛍️ إضافة منتج جديد</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>اسم المنتج</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>السعر</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>نسبة الحسم (%)</label>
                <input type="number" name="discount" class="form-control" min="0" max="100" step="0.1" value="{{ old('discount') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>الكمية</label>
                <input type="number" name="stock" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3">
                <label>الوصف</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-md-4 mb-3">
                <label>القسم</label>
                <select name="category_id" class="form-control">
                    <option value="">اختر القسم</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>نوع المنتج</label>
                <select name="product_type_id" class="form-control">
                    <option value="">اختر النوع</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>الماركة</label>
                <select name="brand_id" class="form-control">
                    <option value="">اختر الماركة</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- القياسات --}}
            <div class="col-md-12 mb-3">
                <label>القياسات المتوفرة</label><br>
                @foreach(['small', 'medium', 'large', '1XL', '2XL','3XL','4XL'] as $size)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}">
                        <label class="form-check-label" for="size_{{ $size }}">{{ strtoupper($size) }}</label>
                    </div>
                @endforeach
            </div>

            {{-- الألوان --}}
            <div class="col-md-12 mb-3">
                <label>الألوان المتوفرة</label><br>
                @foreach(['red' => 'أحمر', 'blue' => 'أزرق', 'yellow' => 'أصفر', 'black' => 'أسود', 'white' => 'أبيض'] as $value => $label)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $value }}" id="color_{{ $value }}">
                        <label class="form-check-label" for="color_{{ $value }}">{{ $label }}</label>
                    </div>
                @endforeach
            </div>

            <div class="col-md-12 mb-3">
                <label>رفع صور المنتج (أكثر من صورة)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            </div>

            <div class="col-md-6 mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_black_friday" id="black_friday">
                <label class="form-check-label" for="black_friday">إضافة إلى عروض Black Friday</label>
            </div>

            <div class="col-md-6 mb-3 form-check">
                <input type="checkbox" name="is_published" class="form-check-input" id="publishedCheck">
                <label class="form-check-label" for="publishedCheck">نشر المنتج</label>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-success px-5">💾 حفظ المنتج</button>
            </div>
        </div>
    </form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const priceInput = document.querySelector('input[name="price"]');
        const stockInput = document.querySelector('input[name="stock"]');
        const discountInput = document.querySelector('input[name="discount"]');

        // دالة التحقق من القيمة السالبة
        function validatePositive(input) {
            if (input) {
                input.addEventListener('input', function () {
                    if (parseFloat(this.value) < 0) {
                        alert('⚠️ لا يمكن إدخال قيمة سالبة!');
                        this.value = '';
                        this.focus();
                    }
                });
            }
        }

        validatePositive(priceInput);
        validatePositive(stockInput);
        validatePositive(discountInput);
    });
</script>



</div>
@endsection
