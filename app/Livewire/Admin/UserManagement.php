<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserManagement extends Component
{
  use WithFileUploads;

  public string $name = '';
  public string $email = '';
  public string $password = '';
  public string $role = 'lid';
  public bool $adding = false;
  public ?int $deletingId = null;

  // Edit properties
  public ?int $editingId = null;
  public string $editName = '';
  public string $editEmail = '';
  public string $editPassword = '';
  public string $editRole = 'lid';

  public function addUser(): void
  {
    $this->validate([
      'name'     => 'required|min:2|max:255',
      'email'    => 'required|email|unique:users,email',
      'password' => 'required|min:8',
      'role'     => 'required|in:admin,lid',
    ]);

    User::create([
      'name'               => $this->name,
      'email'              => $this->email,
      'password'           => Hash::make($this->password),
      'role'               => $this->role,
      'email_verified_at'  => now(),
    ]);

    $this->name     = '';
    $this->email    = '';
    $this->password = '';
    $this->role     = 'lid';
    $this->adding   = false;
  }

  public function deleteUser(int $id): void
  {
    if ($id === auth()->id()) {
      return;
    }

    User::findOrFail($id)->delete();
    $this->deletingId = null;
  }

  public function startEdit(int $id): void
  {
    $user = User::findOrFail($id);
    $this->editingId  = $id;
    $this->editName   = $user->name;
    $this->editEmail  = $user->email;
    $this->editPassword = '';
    $this->editRole   = $user->role;
  }

  public function saveEdit(): void
  {
    $this->validate([
      'editName'     => 'required|min:2|max:255',
      'editEmail'    => 'required|email|unique:users,email,' . $this->editingId,
      'editPassword' => 'nullable|min:8',
      'editRole'     => 'required|in:admin,lid',
    ]);

    $user = User::findOrFail($this->editingId);

    $data = [
      'name'  => $this->editName,
      'email' => $this->editEmail,
      'role'  => $this->editRole,
    ];

    if (!empty($this->editPassword)) {
      $data['password'] = Hash::make($this->editPassword);
    }

    $user->update($data);

    $this->editingId = null;
  }

  public function cancelEdit(): void
  {
    $this->editingId = null;
  }

  public function render()
  {
    $users = User::orderBy('name')->get();

    return view('livewire.admin.user-management', compact('users'));
  }
}
