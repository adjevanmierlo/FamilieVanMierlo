<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

#[Fillable(['name', 'email', 'password', 'avatar', 'role', 'color', 'last_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
  /** @use HasFactory<UserFactory> */
  use HasFactory, Notifiable, HasPushSubscriptions;

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'last_seen_at' => 'datetime',
    ];
  }

  /**
   * Get the count of unread notifications of a specific type.
   *
   * @param string $type
   * @return int
   */
  public function unreadNotificationsOfType(string $type): int
  {
    return $this->unreadNotifications()
      ->where('data->type', $type)
      ->count();
  }

  /**
   * Check if the user is online based on last_seen_at.
   *
   * @return bool
   */
  public function isOnline(): bool
  {
    return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(2));
  }
}
