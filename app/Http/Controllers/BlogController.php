<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogLike;
use App\Models\Comment;


class BlogController extends Controller
{
    // عرض جميع التدوينات المنشورة
    public function index()
    {
        $blogs = Blog::where('is_published', true)->with('user')->latest()->paginate(5);
        return view('blogs.index', compact('blogs'));
    }

    // عرض نموذج إنشاء تدوينة
    public function create()
    {
        return view('blogs.create');
    }

    // حفظ التدوينة الجديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = auth()->id();
        $blog->is_published = $request->has('is_published');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('blog_images', $filename, 'public');
            $blog->image = $path;
        }

        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'تم نشر التدوينة بنجاح.');
    }

    // عرض تدوينة مفردة
    public function show(Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }

    // عرض نموذج التعديل
    public function edit(Blog $blog)
    {
        if ($blog->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذه التدوينة.');
        }

        return view('blogs.edit', compact('blog'));
    }

    // تعديل التدوينة
   public function update(Request $request, Blog $blog)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'is_published' => 'nullable', // ✅ لا داعي للتحقق من القيمة
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('blogs', 'public');
        $blog->image = $imagePath;
    }

    $blog->is_published = $request->has('is_published') ? 1 : 0; // ✅ هذا هو الأهم
    $blog->title = $validated['title'];
    $blog->content = $validated['content'];

    $blog->save();

    return redirect()->route('blogs.show', $blog->id)->with('success', 'تم تحديث التدوينة بنجاح');
}


    // حذف التدوينة
    public function destroy(Blog $blog)
    {
        if ($blog->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذه التدوينة.');
        }

        // حذف الصورة إن وجدت
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'تم حذف التدوينة بنجاح.');
    }

  public function like($id)
{
    $blog = Blog::findOrFail($id);
    $user = auth()->user();

    $existingLike = $blog->likes()->where('user_id', $user->id)->first();

    if ($existingLike) {
        $existingLike->delete();
    } else {
        $blog->likes()->create(['user_id' => $user->id]);
    }

    return response()->json(['success' => true, 'likes' => $blog->likes()->count()]);
}

public function comment(Request $request, $id)
{
    $request->validate(['content' => 'required|string|max:500']);

    $blog = Blog::findOrFail($id);

    $comment = $blog->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->content,
    ]);

    return response()->json([
        'success' => true,
        'comment' => $comment->content,
        'user' => auth()->user()->name,
        'total' => $blog->comments()->count()
    ]);
}

public function deleteComment($id)
{
    $comment = Comment::findOrFail($id);

    if ($comment->user_id !== auth()->id()) {
        abort(403, 'غير مصرح لك بحذف هذا التعليق.');
    }

    $comment->delete();

    return redirect()->back()->with('success', 'تم حذف التعليق بنجاح.');
}

public function ajaxUpdateComment(Request $request, $id)
{
    $comment = Comment::findOrFail($id);

    if ($comment->user_id !== auth()->id()) {
        return response()->json(['error' => 'غير مصرح'], 403);
    }

    $request->validate([
        'content' => 'required|string|max:500',
    ]);

    $comment->content = $request->content;
    $comment->save();

    return response()->json(['success' => true, 'content' => $comment->content]);
}



}
