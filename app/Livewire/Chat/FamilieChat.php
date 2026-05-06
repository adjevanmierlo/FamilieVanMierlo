<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\ChatMessage;
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
    }

    $chatMessage = ChatMessage::create([
      'user_id'    => Auth::id(),
      'message'    => trim($this->message),
      'attachment' => $attachmentPath,
    ]);

    $chatMessage->load('user');

    broadcast(new MessageSent($chatMessage));

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
    $messages = ChatMessage::with('user')
      ->latest()
      ->take(50)
      ->get()
      ->reverse()
      ->values();

    return view('livewire.chat.familie-chat', compact('messages'));
  }
}
