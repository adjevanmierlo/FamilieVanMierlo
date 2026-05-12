<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
  protected $fillable = ['user_id', 'message', 'attachment', 'read_at', 'is_edited'];

  protected $casts = [
    'read_at'    => 'datetime',
    'deleted_at' => 'datetime',
    'is_edited'  => 'boolean',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function isDeleted(): bool
  {
    return $this->deleted_at !== null;
  }

  public function reads(): HasMany
  {
    return $this->hasMany(ChatMessageRead::class);
  }

  public function isReadBy(int $userId): bool
  {
    return $this->reads->where('user_id', $userId)->isNotEmpty();
  }
}
