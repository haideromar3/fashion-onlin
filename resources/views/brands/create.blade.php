@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'إضافة ماركة')

@section('content')
<div class="container">
    <h1>إضافة ماركة جديدة</h1>

    <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>اسم الماركة</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>الوصف</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>الشعار (logo)</label>
            <input type="file" name="logo" class="form-control">
        </div>

        <button class="btn btn-success">حفظ</button>
    </form>
</div>
@endsection
