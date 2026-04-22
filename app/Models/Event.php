<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
  protected $fillable = [
    'user_id',
    'title',
    'description',
    'start_at',
    'end_at',
    'all_day',
    'color'
  ];

  protected $casts = [
    'start_at' => 'datetime',
    'end_at'   => 'datetime',
    'all_day'  => 'boolean',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
