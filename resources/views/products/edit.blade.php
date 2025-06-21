@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'تعديل المنتج')

@section('content')
<div class="container">
    <h1 class="mb-4">تعديل المنتج</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- الاسم --}}
        <div class="mb-3">
            <label>اسم المنتج</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        {{-- الوصف --}}
        <div class="mb-3">
            <label>الوصف</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- السعر --}}
        <div class="mb-3">
            <label>السعر</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" required>
        </div>

        {{-- الخصم --}}
<div class="mb-3">
    <label>نسبة الخصم (%)</label>
    <input type="number" name="discount" class="form-control" value="{{ old('discount', $product->discount) }}" step="1" min="0" max="100">
</div>


        {{-- الكمية --}}
        <div class="mb-3">
            <label>الكمية</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>

        {{-- القسم --}}
        <div class="mb-3">
            <label>القسم</label>
            <select name="category_id" class="form-control">
                <option value="">اختر القسم</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- الماركة --}}
        <div class="mb-3">
            <label>الماركة</label>
            <select name="brand_id" class="form-control">
                <option value="">اختر الماركة</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- النوع --}}
<div class="mb-3">
    <label>النوع</label>
    <select name="product_type_id" class="form-control">
        <option value="">اختر النوع</option>
        @foreach($types as $type)
            <option value="{{ $type->id }}" {{ $product->product_type_id == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>




        {{-- القياسات --}}
        <div class="mb-3">
            <label>القياسات المتوفرة</label><br>
            @php
                $selectedSizes = json_decode($product->sizes, true) ?? [];
            @endphp
            @foreach(['small', 'medium', 'large', '1XL', '2XL','3XL','4XL'] as $size)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}"
                        {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                    <label class="form-check-label" for="size_{{ $size }}">{{ strtoupper($size) }}</label>
                </div>
            @endforeach
        </div>

        {{-- الألوان --}}
        <div class="mb-3">
            <label>الألوان المتوفرة</label><br>
            @php
                $selectedColors = json_decode($product->colors, true) ?? [];
            @endphp
            @foreach(['red' => 'أحمر', 'blue' => 'أزرق', 'yellow' => 'أصفر', 'black' => 'أسود', 'white' => 'أبيض'] as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $value }}" id="color_{{ $value }}"
                        {{ in_array($value, $selectedColors) ? 'checked' : '' }}>
                    <label class="form-check-label" for="color_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>

        {{-- الصور الجديدة --}}
        <div class="mb-3">
            <label>رفع صور جديدة (اختياري)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        {{-- الحالة --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_published" class="form-check-input" id="published"
                   {{ $product->is_published ? 'checked' : '' }}>
            <label class="form-check-label" for="published">نشر المنتج</label>
        </div>

        <button type="submit" class="btn btn-primary">💾 تحديث المنتج</button>
    </form>

    {{-- عرض الصور الحالية --}}
    @if($product->images->count())
        <h5 class="mt-4">الصور الحالية:</h5>
        <div class="d-flex flex-wrap">
            @foreach($product->images as $image)
                <div class="position-relative m-2">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="rounded border" width="100" height="100" style="object-fit: cover;">
                    <form action="{{ route('product-images.destroy', $image->id) }}" method="POST" class="position-absolute top-0 start-0">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('هل تريد حذف هذه الصورة؟')">×</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
