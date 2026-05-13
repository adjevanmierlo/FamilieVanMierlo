<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
  public function handle(Request $request, Closure $next)
  {
    if (Auth::check()) {
      \App\Models\User::where('id', Auth::id())
        ->update(['last_seen_at' => now()]);
    }

    return $next($request);
  }
}
