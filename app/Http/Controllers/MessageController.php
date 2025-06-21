<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\User;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{

 public function index()
    {
        if (auth()->user()->role === 'admin') {
            $messages = Message::latest()->paginate(10);
            return view('admin.messages.index', compact('messages'));
        }

        abort(403); // منع غير الأدمن من الوصول
    }


   public function create()
{
    $messages = \App\Models\Message::with('replies')
        ->where('sender_id', auth()->id())
        ->latest()
        ->get();

    return view('messages.create', compact('messages'));
}

public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:2000',
    ]);

    // إنشاء الرسالة
    $message = Message::create([
        'sender_id' => auth()->id(),
        'content' => $request->content,
    ]);

    // إرسال إشعار إلى جميع الأدمنز
    $admins = \App\Models\User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\NewMessageNotification($message));
    }

    return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح.');
}


public function reply(Request $request, Message $message)
{
    $request->validate([
        'reply_content' => 'required|string|max:2000',
    ]);

    Reply::create([
        'message_id' => $message->id,
        'admin_id' => auth()->id(),
        'content' => $request->reply_content,
    ]);

    return back()->with('success', 'تم إرسال الرد بنجاح.');
}

public function destroy($id)
{
    $message = Message::findOrFail($id);

    // السماح فقط لصاحب الرسالة أو الأدمن بالحذف
    if ($message->sender_id !== auth()->id() && auth()->user()->role !== 'admin') {
        abort(403, 'غير مصرح لك بحذف هذه الرسالة.');
    }

    $message->delete();

    return redirect()->back()->with('success', 'تم حذف الرسالة بنجاح.');
}




    
}
