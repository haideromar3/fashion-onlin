@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'عن الموقع')

@section('content')
<div class="card p-4 mb-4">
    <h2 class="mb-3">من نحن؟</h2>
    <p>نحن منصة إلكترونية تهتم ببيع وشراء الأزياء من خلال المصممين والموردين، ونسعى لتقديم تجربة تسوق فريدة ومريحة للزبائن.</p>
</div>

<div class="card p-4 mb-4">
    <h2 class="mb-3">الخدمات التي نقدمها</h2>
    <ul>
        <li>عرض وشراء منتجات الموضة</li>
        <li>التواصل مع المصممين والموردين</li>
        <li>عروض وتخفيضات موسمية</li>
        <li>نظام نقاط وعضوية مميزة</li>
        <li>شحن وتوصيل محلي ودولي</li>
    </ul>
</div>

<div class="card p-4">
    <h2 class="mb-3">الأسئلة الشائعة (FAQ)</h2>

    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                    كيف يمكنني شراء منتج؟
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    يمكنك تصفح المنتجات وإضافتها إلى السلة ثم إتمام عملية الشراء من صفحة السلة.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                    هل يوجد شحن دولي؟
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    نعم، نوفر خدمة الشحن المحلي والدولي حسب سياسة التوصيل.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                    كيف يمكنني التواصل مع المصممين أو الموردين؟
                </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    يمكنك التواصل من خلال صفحة "تواصل معنا" المتاحة للمستخدمين بعد تسجيل الدخول.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
