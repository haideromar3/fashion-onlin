@extends('layouts.app')

@section('content')
<div class="container">
    <h2>سجل المعاملات للبطاقة رقم {{ $card->card_number }}</h2>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>نوع العملية</th>
                <th>المبلغ</th>
                <th>الرصيد بعد العملية</th>
                <th>الوصف</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type == 'debit' ? 'خصم' : ($transaction->type == 'credit' ? 'إيداع' : 'استعلام') }}</td>
                    <td>{{ $transaction->amount }} $</td>
                    <td>{{ $transaction->balance_after }} $</td>
                    <td>{{ $transaction->description ?? '-' }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد معاملات حتى الآن.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('virtual_cards.index') }}" class="btn btn-secondary">🔙 العودة إلى البطاقات</a>
</div>
@endsection
