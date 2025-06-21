@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
    <div class="container">
        <h1>تعديل السلة</h1>
        <form action="{{ route('carts.update', $cart->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_id">المنتج</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $cart->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">الكمية</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $cart->quantity }}" required>
            </div>
            <button type="submit" class="btn btn-success">تحديث</button>
        </form>
    </div>
@endsection
