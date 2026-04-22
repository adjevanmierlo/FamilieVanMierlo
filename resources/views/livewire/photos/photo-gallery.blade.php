<div class="photos">

    {{-- Header --}}
    <div class="photos-header">
        <h1 class="photos-title">Foto's</h1>
        <button wire:click="$set('uploading', true)" class="btn btn--primary btn--sm">
            <x-heroicon-o-plus class="btn-icon" />
        </button>
    </div>

    {{-- Upload --}}
    @if ($uploading)
        <div class="photos-upload">
            <input type="file" wire:model="photos" multiple accept="image/*" capture="environment"
                class="photos-upload__input" id="photo-upload" />
            <label for="photo-upload" class="photos-upload__label">
                <x-heroicon-o-camera class="photos-upload__icon" />
                <span>Foto's kiezen of maken</span>
            </label>
            @if (count($this->photos ?? []) > 0)
                <div class="photos-upload__actions">
                    <button wire:click="uploadPhotos" class="btn btn--primary btn--sm">
                        Uploaden
                    </button>
                    <button wire:click="$set('uploading', false)" class="btn btn--sm">
                        Annuleren
                    </button>
                </div>
            @else
                <button wire:click="$set('uploading', false)" class="btn btn--sm">
                    Annuleren
                </button>
            @endif
        </div>
    @endif

    {{-- Albums filter --}}
    @if ($albums->count() > 0)
        <div class="photos-albums">
            <button wire:click="$set('selectedAlbum', null)"
                class="photos-album-btn {{ $selectedAlbum === null ? 'photos-album-btn--active' : '' }}">
                Alle foto's
            </button>
            @foreach ($albums as $album)
                <button wire:click="$set('selectedAlbum', {{ $album->id }})"
                    class="photos-album-btn {{ $selectedAlbum === $album->id ? 'photos-album-btn--active' : '' }}">
                    {{ $album->name }} ({{ $album->photos_count }})
                </button>
            @endforeach
        </div>
    @endif

    {{-- Galerij --}}
    <div class="photos-grid">
        @forelse($allPhotos as $photo)
            <div wire:click="selectPhoto({{ $photo->id }})" class="photos-item">
                <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->original_name }}" />
            </div>
        @empty
            <div class="photos-empty">
                <x-heroicon-o-photo class="photos-empty__icon" />
                <p>Nog geen foto's. Voeg de eerste toe!</p>
            </div>
        @endforelse
    </div>

    {{-- Foto detail modal --}}
    @if ($selectedPhoto)
        <div class="photos-modal" wire:click="closePhoto">
            <div class="photos-modal__content" wire:click.stop>
                <button wire:click="closePhoto" class="photos-modal__close">
                    <x-heroicon-o-x-mark />
                </button>
                <img src="{{ Storage::url($selectedPhoto->filename) }}" alt="{{ $selectedPhoto->original_name }}" />
                <div class="photos-modal__info">
                    <span class="photos-modal__author">{{ $selectedPhoto->user->name }}</span>
                    <span
                        class="photos-modal__date">{{ $selectedPhoto->created_at->locale('nl')->isoFormat('D MMMM YYYY') }}</span>
                    <button wire:click="deletePhoto({{ $selectedPhoto->id }})" class="btn btn--sm btn--danger">
                        <x-heroicon-o-trash class="btn-icon" />
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
