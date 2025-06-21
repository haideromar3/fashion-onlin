
@extends('layouts.admin') {{-- أو layouts.user حسب الدور --}}

@section('content')
<div class="container my-4">
    <h2 class="mb-4 text-center">تفاصيل العارضة: {{ $fashionModel->name }}</h2>

    <div class="row">
        <div class="col-md-5">
            @if($fashionModel->image)
                <img src="{{ asset('storage/' . $fashionModel->image) }}" 
                     alt="{{ $fashionModel->name }}" 
                     class="img-fluid rounded shadow" 
                     style="width: 100%; height: auto; object-fit: contain;">
            @else
                <img src="{{ asset('images/no-image.png') }}" 
                     alt="لا توجد صورة" 
                     class="img-fluid rounded shadow">
            @endif

            @if($fashionModel->images->count())
    <div class="row mt-4">
        @foreach($fashionModel->images as $image)
            <div class="col-md-3 mb-3">
                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded shadow">
            </div>
        @endforeach
    </div>
@endif


        </div>

        <div class="col-md-7">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>الاسم:</strong> {{ $fashionModel->name }}</li>
                <li class="list-group-item"><strong>العمر:</strong> {{ $fashionModel->age ?? '—' }}</li>
                <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $fashionModel->email ?? '—' }}</li>
                <li class="list-group-item"><strong>البلد:</strong> {{ $fashionModel->country ?? '—' }}</li>
                <li class="list-group-item">
                    <strong>رابط Instagram:</strong> 
                    @if($fashionModel->instagram)
                        <a href="{{ $fashionModel->instagram }}" target="_blank">{{ $fashionModel->instagram }}</a>
                    @else
                        —
                    @endif
                </li>
                <li class="list-group-item"><strong>نبذة:</strong> {{ $fashionModel->bio ?? '—' }}</li>
            </ul>
            
            <div class="mt-4">
                <a href="{{ route('fashion-models.index') }}" class="btn btn-secondary">العودة لقائمة العارضات</a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('fashion-models.edit', $fashionModel->id) }}" class="btn btn-warning ms-2">تعديل العارضة</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
