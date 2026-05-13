<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatMessageRead;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class FamilieChat extends Component
{
  use WithFileUploads;

  public string $message = '';
  public $attachment = null;
  public ?int $editingId = null;
  public string $editingMessage = '';

  protected function getListeners(): array
  {
    return [
      'echo:familie-chat,MessageSent' => '$refresh',
    ];
  }

  public function sendMessage(): void
  {
    if (empty(trim($this->message)) && !$this->attachment) {
      return;
    }

    $attachmentPath = null;

    if ($this->attachment) {
      $this->validate([
        'attachment' => 'file|max:10240|mimes:jpg,jpeg,png,gif,webp',
      ]);
      $attachmentPath = $this->attachment->store('chat-attachments', 'public');

      // Automatisch opslaan in fotoboek
      \App\Models\Photo::create([
        'user_id'       => Auth::id(),
        'album_id'      => null,
        'filename'      => $attachmentPath,
        'original_name' => $this->attachment->getClientOriginalName(),
        'caption'       => 'Gedeeld via chat',
      ]);
    }

    $chatMessage = ChatMessage::create([
      'user_id'    => Auth::id(),
      'message'    => trim($this->message),
      'attachment' => $attachmentPath,
    ]);

    $chatMessage->load('user');

    broadcast(new MessageSent($chatMessage));

    // Notificatie toevoegen
    \App\Helpers\NotifyFamily::send(
      'chat',
      Auth::user()->name,
      trim($this->message) ?: 'Stuurde een foto',
      '/chat'
    );

    $this->message = '';
    $this->attachment = null;

    $this->dispatch('scroll-to-bottom');
  }

  public function deleteMessage(int $id): void
  {
    $message = ChatMessage::findOrFail($id);

    if ($message->user_id !== Auth::id()) {
      return;
    }

    $message->deleted_at = now();
    $message->message    = '';
    $message->attachment = null;
    $message->save();

    $message->load('user');
    broadcast(new MessageSent($message));

    // Notificatie toevoegen bij verwijderen
    \App\Helpers\NotifyFamily::send(
      'chat',
      Auth::user()->name,
      'Verwijderde een bericht',
      '/chat'
    );

    $this->dispatch('scroll-to-bottom');
  }

  public function startEdit(int $id): void
  {
    $message = ChatMessage::findOrFail($id);

    if ($message->user_id !== Auth::id()) {
      return;
    }

    $this->editingId      = $id;
    $this->editingMessage = $message->message;
  }

  public function saveEdit(): void
  {
    if (empty(trim($this->editingMessage))) {
      return;
    }

    $message = ChatMessage::findOrFail($this->editingId);

    if ($message->user_id !== Auth::id()) {
      return;
    }

    $message->update([
      'message'   => trim($this->editingMessage),
      'is_edited' => true,
    ]);

    $message->load('user');
    broadcast(new MessageSent($message));

    // Notificatie toevoegen bij bewerken
    \App\Helpers\NotifyFamily::send(
      'chat',
      Auth::user()->name,
      'Bewerkte een bericht',
      '/chat'
    );

    $this->editingId      = null;
    $this->editingMessage = '';
  }

  public function cancelEdit(): void
  {
    $this->editingId      = null;
    $this->editingMessage = '';
  }

  public function render()
  {
    $messages = ChatMessage::with(['user', 'reads.user'])
      ->latest()
      ->take(50)
      ->get()
      ->reverse()
      ->values();

    // Markeer alle berichten als gelezen en broadcast
    $unread = ChatMessage::whereDoesntHave('reads', function ($q) {
      $q->where('user_id', Auth::id());
    })->get();

    foreach ($unread as $message) {
      $isNew = !ChatMessageRead::where([
        'chat_message_id' => $message->id,
        'user_id'         => Auth::id(),
      ])->exists();

      ChatMessageRead::firstOrCreate([
        'chat_message_id' => $message->id,
        'user_id'         => Auth::id(),
      ], [
        'read_at' => now(),
      ]);

      // Broadcast dat dit bericht gelezen is
      if ($isNew) {
        $message->load('user');
        broadcast(new MessageSent($message));
      }
    }

    return view('livewire.chat.familie-chat', compact('messages'));
  }
}
