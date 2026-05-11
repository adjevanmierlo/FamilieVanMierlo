<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\FamilieNotification;
use Illuminate\Support\Facades\Auth;

class NotifyFamily
{
  public static function send(string $type, string $title, string $body, string $url = ''): void
  {
    $users = User::where('id', '!=', Auth::id())->get();

    foreach ($users as $user) {
      $user->notify(new FamilieNotification($type, $title, $body, $url));
    }
  }
}
