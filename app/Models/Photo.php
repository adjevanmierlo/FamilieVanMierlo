<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
  protected $fillable = ['user_id', 'album_id', 'filename', 'original_name', 'caption'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function album(): BelongsTo
  {
    return $this->belongsTo(Album::class);
  }
}
