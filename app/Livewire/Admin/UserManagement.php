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

  public function render()
  {
    $users = User::orderBy('name')->get();

    return view('livewire.admin.user-management', compact('users'));
  }
}
