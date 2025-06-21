@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
    <div class="container">
        <h1>تفاصيل العلامة التجارية</h1>
        <ul>
            <li><strong>الاسم:</strong> {{ $brand->name }}</li>
        </ul>
        <a href="{{ route('brands.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>
    </div>
@endsection
