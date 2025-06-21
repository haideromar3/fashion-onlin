<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Message;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // أو mail إذا أردت إيميل
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'رسالة جديدة',
            'body' => 'تم إرسال رسالة جديدة من ' . $this->message->sender_name,
'url' => route('messages.index'),
        ];
    }
}
