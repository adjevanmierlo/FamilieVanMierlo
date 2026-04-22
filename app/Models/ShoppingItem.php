<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingItem extends Model
{
  protected $fillable = ['shopping_list_id', 'name', 'category', 'completed', 'user_id'];

  protected $casts = [
    'completed' => 'boolean',
  ];

  public function list(): BelongsTo
  {
    return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
