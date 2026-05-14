<?php

namespace App\Livewire\Photos;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PhotoGallery extends Component
{
  use WithFileUploads;

  public $photos = [];
  public ?int $selectedAlbum = null;
  public ?Photo $selectedPhoto = null;
  public string $caption = '';
  public bool $uploading = false;

  public function uploadPhotos(): void
  {
    $this->validate([
      'photos.*' => 'image|max:10240',
    ]);

    $uploadedCount = 0;

    foreach ($this->photos as $photo) {
      $filename = $photo->store('photos', 'public');

      Photo::create([
        'user_id'       => Auth::id(),
        'album_id'      => $this->selectedAlbum,
        'filename'      => $filename,
        'original_name' => $photo->getClientOriginalName(),
        'caption'       => '',
      ]);

      $uploadedCount++;
    }

    // Notificatie toevoegen
    $albumName = $this->selectedAlbum
      ? Album::find($this->selectedAlbum)?->name
      : 'Algemeen';

    \App\Helpers\NotifyFamily::send(
      'fotos',
      'Foto\'s',
      Auth::user()->name . ' voegde ' . $uploadedCount . ' ' . ($uploadedCount === 1 ? 'foto' : 'foto\'s') . ' toe aan ' . $albumName,
      '/fotos'
    );

    $this->photos = [];
    $this->uploading = false;
  }

  public function selectPhoto(int $id): void
  {
    $this->selectedPhoto = Photo::with('user')->findOrFail($id);
  }

  public function closePhoto(): void
  {
    $this->selectedPhoto = null;
  }

  public function deletePhoto(int $id): void
  {
    $photo = Photo::findOrFail($id);
    Storage::disk('public')->delete($photo->filename);
    $photo->delete();
    $this->selectedPhoto = null;

    // Notificatie voor verwijderen (optioneel)
    \App\Helpers\NotifyFamily::send(
      'fotos',
      'Foto\'s',
      Auth::user()->name . ' verwijderde een foto',
      '/fotos'
    );
  }

  public function render()
  {
    $albums = Album::withCount('photos')->get();

    $photosQuery = Photo::with('user')
      ->when($this->selectedAlbum, fn($q) => $q->where('album_id', $this->selectedAlbum))
      ->latest();

    $allPhotos = $photosQuery->get();

    $groupedPhotos = $allPhotos->groupBy(fn($photo) => $photo->created_at->locale('nl')->isoFormat('D MMMM YYYY'));

    return view('livewire.photos.photo-gallery', compact('albums', 'allPhotos', 'groupedPhotos'));
  }
}
