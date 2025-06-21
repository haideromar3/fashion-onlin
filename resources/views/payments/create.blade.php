@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'إتمام الدفع')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">🧾 إتمام عملية الدفع</h3>

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <form action="{{ route('payments.process') }}" method="POST" id="payment-form" class="border p-4 rounded shadow-sm bg-light">
        @csrf

        <!-- طريقة الشحن -->
        <div class="mb-3">
            <label for="shipping_method" class="form-label">🚚 طريقة الشحن:</label>
            <select name="shipping_method" id="shipping_method" class="form-select" required>
                <option value="">اختر طريقة الشحن</option>
                <option value="standard" data-fee="0">عادي (0 $)</option>
                <option value="express" data-fee="20">سريع (+20 $)</option>
            </select>
        </div>

        <!-- المحافظة -->
        <div class="mb-3">
            <label for="city" class="form-label">🏙️ المحافظة:</label>
            <select name="city" class="form-select" required>
                <option value="">اختر المحافظة</option>
                @foreach(['دمشق','ريف دمشق','حلب','حمص','حماة','اللاذقية','طرطوس','إدلب','درعا','السويداء','القنيطرة','دير الزور','الرقة','الحسكة'] as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
        </div>

        <!-- العنوان -->
        <div class="mb-3">
            <label for="address" class="form-label">📍 العنوان التفصيلي:</label>
            <input type="text" name="address" class="form-control" required placeholder="مثال: شارع الثورة - بناء رقم 12">
        </div>

        <!-- طريقة الدفع -->
        <div class="mb-3">
            <label for="payment_method" class="form-label">💳 طريقة الدفع:</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="">اختر طريقة الدفع</option>
                <option value="cod">الدفع عند الاستلام</option>
                <option value="virtual_card">بطاقة بنكية</option>
            </select>
        </div>

        <!-- رقم البطاقة البنكية -->
        <div class="mb-3" id="virtual-card-field" style="display: none;">
            <label for="card_number" class="form-label">🔢 رقم البطاقة البنكية:</label>
            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="أدخل رقم البطاقة " maxlength="16">
        </div>

        <!-- عرض رسوم الشحن -->
        <div class="mb-3 text-center">
            <span class="fw-bold">💰 رسوم الشحن الإضافية:</span>
            <span id="shipping-fee-display">0 $</span>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                🛍️ إتمام الطلب والدفع
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function () {
        const cardField = document.getElementById('virtual-card-field');
        cardField.style.display = this.value === 'virtual_card' ? 'block' : 'none';
    });

    document.getElementById('shipping_method').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const fee = selectedOption.dataset.fee || 0;
        document.getElementById('shipping-fee-display').textContent = `${fee} $`;
    });
</script>
@endsection
