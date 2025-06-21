<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Complaint;

class NewComplaintNotification extends Notification
{
    use Queueable;

    public $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // يمكن إضافة mail إذا أردت إشعار عبر البريد
    }

    /**
     * Get the array representation of the notification for storage in database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'شكوى جديدة',
            'body' => 'تم إرسال شكوى جديدة من ' . $this->complaint->user->name,
            'url' => route('admin.complaints.show', $this->complaint->id),
            'complaint_id' => $this->complaint->id,
            'user_id' => $this->complaint->user->id,
        ];
    }
}
