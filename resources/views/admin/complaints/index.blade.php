@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <h3 class="mb-4">📨 قائمة الشكاوى</h3>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover table-bordered align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>الزبون</th>
                    <th>الشكوى</th>
                    <th>الرد</th>
                    <th>تاريخ آخر رد</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($complaints as $complaint)
                <tr>
                    <td class="fw-bold">{{ $complaint->user->name }}</td>

                    <td class="text-start">{{ $complaint->message }}</td>

                    <td>
                        @if ($complaint->reply)
                            <div class="alert alert-success py-2 mb-0">
                                {{ $complaint->reply }}
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.complaints.reply', $complaint->id) }}">
                                @csrf
                                <textarea name="reply" class="form-control form-control-sm" rows="2" required placeholder="أدخل الرد هنا..."></textarea>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">رد</button>
                            </form>
                        @endif
                    </td>

                    <td>{{ $complaint->updated_at ? $complaint->updated_at->format('Y-m-d H:i') : '-' }}</td>

                    <td>
                        {{-- زر "عرض التفاصيل" إن أردت تفعيل صفحة مفصلة لاحقًا --}}
<a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-info btn-sm mb-1">عرض</a>

                        <form method="POST" action="{{ route('admin.complaints.destroy', $complaint->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشكوى؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد شكاوى حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
