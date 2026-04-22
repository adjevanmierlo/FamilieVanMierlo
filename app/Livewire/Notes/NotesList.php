<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotesList extends Component
{
  public ?Note $selectedNote = null;
  public string $title = '';
  public string $content = '';
  public bool $isShared = true;
  public bool $editing = false;

  public function selectNote(int $id): void
  {
    $this->selectedNote = Note::findOrFail($id);
    $this->title = $this->selectedNote->title;
    $this->content = $this->selectedNote->content ?? '';
    $this->isShared = $this->selectedNote->is_shared;
    $this->editing = false;
  }

  public function newNote(): void
  {
    $this->selectedNote = null;
    $this->title = '';
    $this->content = '';
    $this->isShared = true;
    $this->editing = true;
  }

  public function saveNote(): void
  {
    $this->validate([
      'title'   => 'required|min:1|max:255',
      'content' => 'nullable',
    ]);

    if ($this->selectedNote) {
      $this->selectedNote->update([
        'title'     => trim($this->title),
        'content'   => trim($this->content),
        'is_shared' => $this->isShared,
      ]);
    } else {
      $this->selectedNote = Note::create([
        'user_id'   => Auth::id(),
        'title'     => trim($this->title),
        'content'   => trim($this->content),
        'is_shared' => $this->isShared,
      ]);
    }

    $this->editing = false;
  }

  public function editNote(): void
  {
    $this->editing = true;
  }

  public function deleteNote(int $id): void
  {
    Note::findOrFail($id)->delete();
    $this->selectedNote = null;
    $this->title = '';
    $this->content = '';
    $this->editing = false;
  }

  public function cancelEdit(): void
  {
    $this->editing = false;
    if (!$this->selectedNote) {
      $this->title = '';
      $this->content = '';
    }
  }

  public function render()
  {
    $notes = Note::where(function ($q) {
      $q->where('user_id', Auth::id())
        ->orWhere('is_shared', true);
    })->latest()->get();

    return view('livewire.notes.notes-list', compact('notes'));
  }
}
