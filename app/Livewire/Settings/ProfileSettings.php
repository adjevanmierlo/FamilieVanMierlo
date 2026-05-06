<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileSettings extends Component
{
  use WithFileUploads;

  public string $name = '';
  public string $email = '';
  public $avatar = null;
  public bool $saved = false;

  public string $currentPassword = '';
  public string $newPassword = '';
  public string $newPasswordConfirmation = '';
  public bool $passwordSaved = false;

  public function mount(): void
  {
    $this->name  = Auth::user()->name;
    $this->email = Auth::user()->email;
  }

  public function save(): void
  {
    $this->validate([
      'name'   => 'required|min:2|max:255',
      'email'  => 'required|email|unique:users,email,' . Auth::id(),
      'avatar' => 'nullable|image|max:2048',
    ]);

    $user = Auth::user();

    if ($this->avatar) {
      if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
      }
      $user->avatar = $this->avatar->store('avatars', 'public');
    }

    $user->name  = $this->name;
    $user->email = $this->email;
    $user->save();

    $this->avatar = null;
    $this->saved  = true;

    $this->dispatch('profile-saved');
  }

  public function changePassword(): void
  {
    $this->validate([
      'currentPassword' => 'required',
      'newPassword'     => 'required|min:8',
      'newPasswordConfirmation' => 'required|same:newPassword',
    ]);

    if (!Hash::check($this->currentPassword, Auth::user()->password)) {
      $this->addError('currentPassword', 'Huidig wachtwoord is onjuist.');
      return;
    }

    Auth::user()->update([
      'password' => Hash::make($this->newPassword),
    ]);

    $this->currentPassword = '';
    $this->newPassword = '';
    $this->newPasswordConfirmation = '';
    $this->passwordSaved = true;
  }

  public function render()
  {
    return view('livewire.settings.profile-settings');
  }
}
