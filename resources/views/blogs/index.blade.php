@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">


<div class="container py-4" style="max-width: 720px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📚 المدونة</h2>
        @auth
            @if(in_array(auth()->user()->role, ['admin', 'designer', 'supplier']))
                <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> تدوينة جديدة
                </a>
            @endif
        @endauth
    </div>

    @forelse($blogs as $blog)
        <div class="card shadow-sm mb-4">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top img-fluid" alt="صورة التدوينة" style="max-height: 350px; object-fit: cover;">
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ $blog->title }}</h5>
                <p class="text-muted small mb-1">
                    الكاتب: <strong>{{ $blog->user->name ?? 'مستخدم مجهول' }}</strong> | {{ $blog->created_at->diffForHumans() }}
                </p>
                <p class="card-text" style="line-height: 1.5;">{{ Str::limit($blog->content, 180) }}</p>

                @php
                    $userLiked = auth()->check() ? $blog->likes->contains('user_id', auth()->id()) : false;
                @endphp

                <div class="d-flex justify-content-around border-top pt-3 mt-3 text-secondary" style="font-size: 1rem;">
                    <button type="button" 
                            class="btn btn-light d-flex align-items-center gap-2 btn-like" 
                            data-blog-id="{{ $blog->id }}" style="flex:1; justify-content: center;">
                        <i class="bi bi-hand-thumbs-up{{ $userLiked ? '-fill text-primary' : '' }}"></i> 
                        لايك 
                        <span class="like-count ms-1">{{ $blog->likes->count() }}</span>
                    </button>

                    <button type="button" 
                            class="btn btn-light d-flex align-items-center gap-2 btn-comment-toggle" 
                            style="flex:1; justify-content: center;">
                        <i class="bi bi-chat-left-text"></i> تعليق 
                        <span class="comment-count ms-1">{{ $blog->comments->count() }}</span>
                    </button>

                    <div class="dropdown" style="flex:1;">
                        <button class="btn btn-light d-flex align-items-center justify-content-center gap-2 dropdown-toggle btn-share w-100" 
                                type="button" id="shareDropdown{{ $blog->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-share"></i> مشاركة
                        </button>
                        <ul class="dropdown-menu text-center" aria-labelledby="shareDropdown{{ $blog->id }}">
                            <li>
                                <a class="dropdown-item text-success" 
                                   href="https://wa.me/?text={{ urlencode(route('blogs.show', $blog->id)) }}" 
                                   target="_blank">
                                   <i class="bi bi-whatsapp"></i> واتساب
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-primary" 
                                   href="https://www.facebook.com/dialog/send?link={{ urlencode(route('blogs.show', $blog->id)) }}&app_id=YOUR_APP_ID&redirect_uri={{ urlencode(route('blogs.show', $blog->id)) }}" 
                                   target="_blank">
                                   <i class="bi bi-messenger"></i> ماسنجر
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- قسم التعليقات --}}
                <div class="comments-section mt-4 border rounded p-3 bg-light" style="display: none;">
                    <h5 class="mb-3">التعليقات</h5>

                    <ul class="list-group mb-3 comments-list">
@forelse($blog->comments as $comment)
    <li class="list-group-item" id="comment-{{ $comment->id }}">
        <div class="fw-bold">{{ $comment->user->name ?? 'مستخدم' }}</div>
        
        {{-- المحتوى النصي للتعليق (لعرضه فقط) --}}
        <p class="comment-content" id="content-{{ $comment->id }}">{{ $comment->content }}</p>

        {{-- مربع النص للتحرير مخفي افتراضياً --}}
        <textarea id="edit-content-{{ $comment->id }}" class="form-control mb-2" style="display:none;">{{ $comment->content }}</textarea>

        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>

        @auth
            @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                <div class="mt-2 d-flex gap-2 align-items-center flex-wrap">

                    <button id="edit-btn-{{ $comment->id }}" 
                            class="btn btn-sm btn-outline-primary" 
                            onclick="showEdit({{ $comment->id }})">
                        تعديل
                    </button>

                    <button id="save-btn-{{ $comment->id }}" 
                            class="btn btn-sm btn-success" 
                            style="display:none;"
                            onclick="updateComment({{ $comment->id }})">
                        حفظ
                    </button>

                    <button id="cancel-btn-{{ $comment->id }}" 
                            class="btn btn-sm btn-secondary" 
                            style="display:none;"
                            onclick="cancelEdit({{ $comment->id }})">
                        إلغاء
                    </button>

                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                </div>
            @endif
        @endauth
    </li>
@empty
    <li class="list-group-item text-muted text-center">لا توجد تعليقات حتى الآن.</li>
@endforelse

                    </ul>

                    @auth
                        <form class="comment-form" data-blog-id="{{ $blog->id }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="content" class="form-control" placeholder="أضف تعليقًا..." required>
                                <button class="btn btn-primary" type="submit">إرسال</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center text-muted">
                            <small>يجب تسجيل الدخول لإضافة تعليق.</small>
                        </div>
                    @endauth
                </div>

                {{-- إجراءات التعديل والحذف --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-outline-primary btn-sm">عرض التفاصيل</a>

                    @auth
                        @if(auth()->id() === $blog->user_id)
                            <div class="d-flex gap-2">
                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">تعديل</a>

                                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف التدوينة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">لا توجد تدوينات منشورة حاليًا.</div>
    @endforelse

    <div class="mt-4 d-flex justify-content-center">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- زر لايك ---
    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', function () {
            const blogId = this.dataset.blogId;
            fetch(`/blogs/${blogId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.like-count');
                    icon.classList.toggle('bi-hand-thumbs-up-fill');
                    icon.classList.toggle('text-primary');
                    count.textContent = data.likes;
                }
            });
        });
    });

    // --- إظهار/إخفاء التعليقات ---
    document.querySelectorAll('.btn-comment-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const commentsSection = this.closest('.card-body').querySelector('.comments-section');
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        });
    });

    // --- إرسال تعليق ---
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const blogId = this.dataset.blogId;
            const input = this.querySelector('input[name="content"]');
            const content = input.value.trim();
            if (!content) return;

            fetch(`/blogs/${blogId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const commentsList = this.closest('.comments-section').querySelector('.comments-list');
                    commentsList.insertAdjacentHTML('beforeend', `
                        <li class="list-group-item" id="comment-${data.commentId}">
                            <div class="comment-content"><strong>${data.user}</strong>: ${data.comment} <br><small class="text-muted">الآن</small></div>

                            <!-- أزرار تعديل -->
                            <button id="edit-btn-${data.commentId}" onclick="showEdit(${data.commentId})" class="btn btn-sm btn-link">تعديل</button>
                            <button id="save-btn-${data.commentId}" onclick="updateComment(${data.commentId})" class="btn btn-sm btn-link" style="display:none;">حفظ</button>
                            <button id="cancel-btn-${data.commentId}" onclick="cancelEdit(${data.commentId})" class="btn btn-sm btn-link" style="display:none;">إلغاء</button>

                            <!-- مربع نص التعديل مخفي -->
                            <textarea id="edit-content-${data.commentId}" class="form-control mt-2" style="display:none;">${data.comment}</textarea>
                        </li>
                    `);
                    input.value = '';
                    this.closest('.card-body').querySelector('.comment-count').textContent = data.total;
                }
            });
        });
    });
});

// --- وظائف تعديل التعليق ---
window.showEdit = function(commentId) {
    document.querySelector(`#comment-${commentId} .comment-content`).style.display = 'none';
    document.getElementById(`edit-content-${commentId}`).style.display = 'block';
    document.getElementById(`edit-btn-${commentId}`).style.display = 'none';
    document.getElementById(`save-btn-${commentId}`).style.display = 'inline-block';
    document.getElementById(`cancel-btn-${commentId}`).style.display = 'inline-block';
}

window.cancelEdit = function(commentId) {
    document.querySelector(`#comment-${commentId} .comment-content`).style.display = 'block';
    document.getElementById(`edit-content-${commentId}`).style.display = 'none';
    document.getElementById(`edit-btn-${commentId}`).style.display = 'inline-block';
    document.getElementById(`save-btn-${commentId}`).style.display = 'none';
    document.getElementById(`cancel-btn-${commentId}`).style.display = 'none';
}

window.updateComment = function(commentId) {
    const content = document.getElementById(`edit-content-${commentId}`).value.trim();
    if (!content) {
        alert('لا يمكن ترك التعليق فارغاً.');
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ content })
    })
    .then(response => {
        if (!response.ok) throw new Error('خطأ في التحديث');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.querySelector(`#comment-${commentId} .comment-content`).textContent = data.content;
            cancelEdit(commentId);
        } else {
            alert('فشل في تحديث التعليق.');
        }
    })
    .catch(error => {
        alert('حدث خطأ أثناء تحديث التعليق.');
        console.error(error);
    });
}


</script>
@endpush
