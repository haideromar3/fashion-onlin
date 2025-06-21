@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')


@section('content')
<div class="container">
    <h1 class="mb-4">تعديل العلامة التجارية</h1>

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- الاسم --}}
        <div class="mb-3">
            <label for="name" class="form-label">اسم العلامة التجارية</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $brand->name }}" required>
        </div>

        {{-- الوصف --}}
        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $brand->description }}</textarea>
        </div>

        {{-- عرض الشعار الحالي --}}
        @if($brand->logo)
            <div class="mb-3">
                <label class="form-label">الشعار الحالي</label><br>
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="100" height="100" class="img-thumbnail">
            </div>
        @endif

        {{-- تغيير الشعار --}}
        <div class="mb-3">
            <label for="logo" class="form-label">تغيير الشعار</label>
            <input type="file" name="logo" id="logo" class="form-control">
        </div>

        {{-- زر الحفظ --}}
        <button type="submit" class="btn btn-success">💾 حفظ التعديلات</button>
    </form>
</div>
@endsection
