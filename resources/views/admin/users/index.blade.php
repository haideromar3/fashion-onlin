@extends('layouts.admin')
@section('title', 'إدارة المستخدمين')
@section('content')
<div class="container">
    <h1>إدارة المستخدمين</h1>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- إحصائيات الأدوار --}}
    <div class="mb-4">
        <h5>إحصائيات المستخدمين</h5>
        <ul class="list-inline">
            <li class="list-inline-item"><span class="badge bg-dark">Admins: {{ $counts['admin'] ?? 0 }}</span></li>
            <li class="list-inline-item"><span class="badge bg-secondary">Customers: {{ $counts['customer'] ?? 0 }}</span></li>
            <li class="list-inline-item"><span class="badge bg-info text-dark">Designers: {{ $counts['designer'] ?? 0 }}</span></li>
            <li class="list-inline-item"><span class="badge bg-warning text-dark">Suppliers: {{ $counts['supplier'] ?? 0 }}</span></li>
        </ul>
    </div>

    {{-- فلترة حسب الدور --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3" style="max-width: 300px;">
        <div class="input-group">
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">-- كل الأدوار --</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="designer" {{ request('role') == 'designer' ? 'selected' : '' }}>Designer</option>
                <option value="supplier" {{ request('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
            </select>
            <button class="btn btn-outline-secondary" type="submit">بحث</button>
        </div>
    </form>

    {{-- جدول المستخدمين --}}

    {{-- نموذج البحث --}}
<form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
    <div class="input-group" style="max-width: 300px;">
        <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}">
        <button class="btn btn-outline-primary">بحث</button>
    </div>
</form>


    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور الحالي</th>
                <th>تعديل الدور</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                {{-- شارة الدور بالألوان --}}
                <td>
                    @php
                        $badgeClass = match($user->role) {
                            'admin' => 'bg-dark',
                            'customer' => 'bg-secondary',
                            'designer' => 'bg-info text-dark',
                            'supplier' => 'bg-warning text-dark',
                            default => 'bg-light text-dark'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($user->role) }}</span>
                </td>

                <td>
    {{-- تعديل الدور --}}
    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-flex mb-1">
        @csrf
        <select name="role" class="form-select me-2" style="width: 140px;">
            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="designer" {{ $user->role === 'designer' ? 'selected' : '' }}>Designer</option>
            <option value="supplier" {{ $user->role === 'supplier' ? 'selected' : '' }}>Supplier</option>
        </select>
        <button class="btn btn-sm btn-primary">تحديث</button>
    </form>
    
    <form action="{{ route('admin.users.togglePostPermission', $user->id) }}" method="POST" class="d-inline">
    @csrf
    <button class="btn btn-sm {{ $user->can_post_products ? 'btn-success' : 'btn-secondary' }}">
        {{ $user->can_post_products ? 'يمكنه النشر' : 'يمنع من النشر' }}
    </button>
</form>

    {{-- حذف المستخدم --}}
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger w-100 delete-user-btn">حذف</button>
        </form>
</td>

            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>

@push('scripts')
<script>
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const form = this.closest('form');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع بعد الحذف!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush


@endsection
