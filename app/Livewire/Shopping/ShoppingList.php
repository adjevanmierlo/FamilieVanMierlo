<?php

namespace App\Livewire\Shopping;

use App\Models\ShoppingItem;
use App\Models\ShoppingList as ShoppingListModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShoppingList extends Component
{
  public ShoppingListModel $list;
  public string $newItem = '';
  public string $newCategory = '';

  public function mount(): void
  {
    $this->list = ShoppingListModel::with('items')
      ->firstOrCreate(
        [],
        ['name' => 'Boodschappen', 'user_id' => Auth::id()]
      );
  }

  public function addItem(): void
  {
    $this->validate([
      'newItem' => 'required|min:1|max:255',
    ]);

    ShoppingItem::create([
      'shopping_list_id' => $this->list->id,
      'name'             => $this->newItem,
      'category'         => $this->newCategory ?: null,
      'completed'        => false,
      'user_id'          => Auth::id(),
    ]);

    $this->newItem = '';
    $this->newCategory = '';
    $this->list->refresh()->load('items');
  }

  public function toggleItem(int $id): void
  {
    $item = ShoppingItem::findOrFail($id);
    $item->update(['completed' => !$item->completed]);
    $this->list->refresh()->load('items');
  }

  public function deleteItem(int $id): void
  {
    ShoppingItem::findOrFail($id)->delete();
    $this->list->refresh()->load('items');
  }

  public function render()
  {
    return view('livewire.shopping.shopping-list');
  }
}
