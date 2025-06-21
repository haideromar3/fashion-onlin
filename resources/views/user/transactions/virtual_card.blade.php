@extends(auth()->check() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'السجل المالي')

@section('content')
<div class="container">
   <h2 class="mb-4">💳 السجل المالي</h2>

<div class="mb-3">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        ⬅️ الرجوع إلى طلباتي
    </a>
</div>


    @if($transactions->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>الرصيد بعد العملية</th>
                    <th>الوصف</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaction->type == 'debit' ? 'سحب' : ($transaction->type == 'credit' ? 'إيداع' : 'استعلام') }}</td>
                    <td>{{ $transaction->amount }} د.أ</td>
                    <td>{{ $transaction->balance_after }} د.أ</td>
                    <td>{{ $transaction->description ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">لا يوجد أي معاملات مالية حتى الآن.</div>
    @endif
</div>
@endsection
