<div class="notes">

    <div class="notes-sidebar">
        <div class="notes-sidebar__header">
            <h1 class="notes-title">Notities</h1>
            <button wire:click="newNote" class="btn btn--primary btn--sm">
                <x-heroicon-o-plus class="btn-icon" />
            </button>
        </div>

        <div class="notes-list">
            @forelse($notes as $note)
                <div wire:click="selectNote({{ $note->id }})"
                    class="notes-item {{ $selectedNote?->id === $note->id ? 'notes-item--active' : '' }}">
                    <div class="notes-item__header">
                        <span class="notes-item__title">{{ $note->title }}</span>
                        @if ($note->is_shared)
                            <x-heroicon-o-users class="notes-item__shared" />
                        @endif
                    </div>
                    <span class="notes-item__date">{{ $note->updated_at->locale('nl')->diffForHumans() }}</span>
                </div>
            @empty
                <p class="notes-empty">Nog geen notities.</p>
            @endforelse
        </div>
    </div>

    <div class="notes-content">
        @if ($editing)
            <div class="notes-editor">
                <input type="text" wire:model="title" placeholder="Titel..." class="notes-editor__title" />
                @error('title')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <textarea wire:model="content" placeholder="Schrijf hier je notitie..." class="notes-editor__content" rows="15"></textarea>
                <div class="notes-editor__footer">
                    <label class="form-checkbox">
                        <input type="checkbox" wire:model="isShared" />
                        <span>Gedeeld met familie</span>
                    </label>
                    <div class="notes-editor__actions">
                        <button wire:click="cancelEdit" class="btn btn--sm">Annuleren</button>
                        <button wire:click="saveNote" class="btn btn--primary btn--sm">Opslaan</button>
                    </div>
                </div>
            </div>
        @elseif($selectedNote)
            <div class="notes-view">
                <div class="notes-view__header">
                    <h2 class="notes-view__title">{{ $selectedNote->title }}</h2>
                    <div class="notes-view__actions">
                        <button wire:click="editNote" class="btn btn--sm">
                            <x-heroicon-o-pencil-square class="btn-icon" />
                        </button>
                        <button wire:click="deleteNote({{ $selectedNote->id }})" class="btn btn--sm btn--danger">
                            <x-heroicon-o-trash class="btn-icon" />
                        </button>
                    </div>
                </div>
                <div class="notes-view__meta">
                    <span>{{ $selectedNote->user->name }}</span>
                    <span>{{ $selectedNote->updated_at->locale('nl')->isoFormat('D MMMM YYYY') }}</span>
                </div>
                <div class="notes-view__content">{{ $selectedNote->content }}</div>
            </div>
        @else
            <div class="notes-placeholder">
                <x-heroicon-o-document-text class="notes-placeholder__icon" />
                <p>Selecteer een notitie of maak een nieuwe aan</p>
            </div>
        @endif
    </div>

</div>
