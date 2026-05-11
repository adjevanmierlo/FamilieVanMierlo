<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FamilieNotification extends Notification implements ShouldBroadcast
{
  use Queueable;

  public function __construct(
    public string $type,
    public string $title,
    public string $body,
    public string $url = '',
  ) {}

  public function via(object $notifiable): array
  {
    return ['database', 'broadcast'];
  }

  public function toArray(object $notifiable): array
  {
    return [
      'type'  => $this->type,
      'title' => $this->title,
      'body'  => $this->body,
      'url'   => $this->url,
    ];
  }

  public function toBroadcast(object $notifiable): BroadcastMessage
  {
    return new BroadcastMessage([
      'type'  => $this->type,
      'title' => $this->title,
      'body'  => $this->body,
      'url'   => $this->url,
    ]);
  }
}
