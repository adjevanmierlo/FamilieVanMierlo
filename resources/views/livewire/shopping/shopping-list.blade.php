<div class="shopping">

    <div class="shopping-header">
        <h1 class="shopping-title">Boodschappen</h1>
        <span class="shopping-count">
            {{ $list->items->where('completed', false)->count() }} items
        </span>
    </div>

    <form wire:submit="addItem" class="shopping-form">
        <div class="shopping-form__inputs">
            <input type="text" wire:model="newItem" placeholder="Item toevoegen..." class="form-input" />
            <input type="text" wire:model="newCategory" placeholder="Categorie (optioneel)" class="form-input" />
        </div>
        <button type="submit" class="btn btn--primary">
            <x-heroicon-o-plus class="btn-icon" />
        </button>
    </form>

    @error('newItem')
        <span class="form-error">{{ $message }}</span>
    @enderror

    <div class="shopping-list">
        @forelse($list->items->where('completed', false) as $item)
            <div class="shopping-item">
                @if ($editingId === $item->id)
                    <div class="shopping-item__edit">
                        <input type="text" wire:model="editingName" class="form-input" />
                        <input type="text" wire:model="editingCategory" placeholder="Categorie" class="form-input" />
                        <div class="shopping-item__edit-actions">
                            <button wire:click="saveEdit" class="btn btn--primary btn--sm">Opslaan</button>
                            <button wire:click="cancelEdit" class="btn btn--sm">Annuleren</button>
                        </div>
                    </div>
                @else
                    <button wire:click="toggleItem({{ $item->id }})" class="shopping-item__check">
                        <x-heroicon-o-ellipsis-horizontal-circle class="check-icon" />
                    </button>
                    <div class="shopping-item__content">
                        <span class="shopping-item__name">{{ $item->name }}</span>
                        @if ($item->category)
                            <span class="shopping-item__category">{{ $item->category }}</span>
                        @endif
                    </div>
                    <button wire:click="startEdit({{ $item->id }})" class="shopping-item__edit-btn">
                        <x-heroicon-o-pencil-square class="delete-icon" />
                    </button>
                    <button wire:click="deleteItem({{ $item->id }})" class="shopping-item__delete">
                        <x-heroicon-o-trash class="delete-icon" />
                    </button>
                @endif
            </div>
        @empty
            <p class="shopping-empty">Lijst is leeg. Voeg items toe!</p>
        @endforelse

        @if ($list->items->where('completed', true)->count() > 0)
            <div class="shopping-divider">
                <span>Afgevinkt</span>
            </div>

            @foreach ($list->items->where('completed', true) as $item)
                <div class="shopping-item shopping-item--completed">
                    <button wire:click="toggleItem({{ $item->id }})" class="shopping-item__check">
                        <x-heroicon-o-check-circle class="check-icon" />
                    </button>
                    <div class="shopping-item__content">
                        <span class="shopping-item__name">{{ $item->name }}</span>
                        @if ($item->category)
                            <span class="shopping-item__category">{{ $item->category }}</span>
                        @endif
                    </div>
                    <button wire:click="deleteItem({{ $item->id }})" class="shopping-item__delete">
                        <x-heroicon-o-trash class="delete-icon" />
                    </button>
                </div>
            @endforeach
        @endif
    </div>

</div>
