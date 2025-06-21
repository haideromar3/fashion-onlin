<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewComplaintNotification;
use App\Models\User;


class ComplaintController extends Controller
{
    
    // للمستخدم: إرسال الشكوى
   public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'message' => 'required|string|max:1000',
    ]);

    // إنشاء الشكوى
    $complaint = Complaint::create([
        'user_id' => Auth::id(),
        'order_id' => $request->order_id,
        'message' => $request->message,
    ]);

    // إرسال إشعار إلى جميع الأدمنز
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new NewComplaintNotification($complaint));
    }

    return back()->with('success', 'تم إرسال الشكوى بنجاح.');
}
    // للأدمن: عرض جميع الشكاوى
    public function index()
    {
        $complaints = Complaint::latest()->with('user', 'order')->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    // للأدمن: الرد على الشكوى
    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string|max:1000']);

        $complaint = Complaint::findOrFail($id);
        $complaint->reply = $request->reply;
        $complaint->save();

        return back()->with('success', 'تم إرسال الرد.');
    }

    public function myComplaints()
{
    $complaints = auth()->user()->complaints()->with('order')->latest()->get();
    return view('complaints.my_complaints', compact('complaints'));
}

public function create()
{
    $orders = auth()->user()->orders;
    $complaints = auth()->user()->complaints()->with('order')->latest()->get();

    return view('complaints.create', compact('orders', 'complaints'));
}

public function edit(Complaint $complaint)
{
    if ($complaint->user_id !== auth()->id() || $complaint->reply) {
        abort(403);
    }

    $orders = auth()->user()->orders;
    return view('complaints.edit', compact('complaint', 'orders'));
}

public function update(Request $request, Complaint $complaint)
{
    if ($complaint->user_id !== auth()->id() || $complaint->reply) {
        abort(403);
    }

    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'message' => 'required|string|max:1000',
    ]);

    $complaint->update([
        'order_id' => $request->order_id,
        'message' => $request->message,
    ]);

    return redirect()->route('complaints.create')->with('success', 'تم تعديل الشكوى بنجاح.');
}


public function destroy(Complaint $complaint)
{
    $user = auth()->user();

    // السماح للحذف إذا كان المستخدم أدمن
    // أو إذا كان صاحب الشكوى
    if ($user->role !== 'admin' && $complaint->user_id !== $user->id) {
        abort(403);
    }

    $complaint->delete();

    // إعادة توجيه حسب الدور (اختياري)
    if ($user->role === 'admin') {
        return redirect()->route('admin.complaints.index')->with('success', 'تم حذف الشكوى بنجاح.');
    } else {
        return redirect()->route('complaints.create')->with('success', 'تم حذف الشكوى بنجاح.');
    }
}




public function show(Complaint $complaint)
{
    return view('admin.complaints.show', compact('complaint'));
}


    
}
