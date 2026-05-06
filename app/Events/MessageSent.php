<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public function __construct(
    public ChatMessage $message
  ) {}

  public function broadcastOn(): Channel
  {
    return new Channel('familie-chat');
  }

  public function broadcastWith(): array
  {
    return [
      'id'         => $this->message->id,
      'message'    => $this->message->message,
      'attachment' => $this->message->attachment,
      'user'       => [
        'id'     => $this->message->user->id,
        'name'   => $this->message->user->name,
        'avatar' => $this->message->user->avatar,
      ],
      'created_at' => $this->message->created_at->toISOString(),
    ];
  }
}
