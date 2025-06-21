@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">💳 الدفع معطل حالياً</h3>
    <div class="alert alert-info">
        تم تعطيل نموذج الدفع على هذه الصفحة مؤقتًا.
    </div>
</div>
@endsection
