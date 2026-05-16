<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Conversation $conversation,
        public Message $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'message',
            'conversation_id' => $this->conversation->id,
            'property_title'  => $this->conversation->property?->title,
            'preview' => mb_strimwidth($this->message->body, 0, 80, '…'),
            'sender'  => $this->message->sender?->name,
            'url'     => route('chat.show', $this->conversation),
        ];
    }
}
