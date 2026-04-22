<div class="admin">

    <div class="admin-header">
        <h1 class="admin-title">Gebruikers</h1>
        <button wire:click="$set('adding', true)" class="btn btn--primary btn--sm">
            <x-heroicon-o-plus class="btn-icon" />
            Toevoegen
        </button>
    </div>

    @if ($adding)
        <div class="admin-form">
            <h2 class="admin-form__title">Nieuwe gebruiker</h2>

            <div class="form-group">
                <label class="form-label">Naam</label>
                <input type="text" wire:model="name" class="form-input" placeholder="Voor- en achternaam" />
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">E-mailadres</label>
                <input type="email" wire:model="email" class="form-input" placeholder="email@familie.nl" />
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Wachtwoord</label>
                <input type="password" wire:model="password" class="form-input" placeholder="Minimaal 8 tekens" />
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Rol</label>
                <select wire:model="role" class="form-input">
                    <option value="lid">Lid</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="admin-form__actions">
                <button wire:click="$set('adding', false)" class="btn btn--sm">Annuleren</button>
                <button wire:click="addUser" class="btn btn--primary btn--sm">Opslaan</button>
            </div>
        </div>
    @endif

    <div class="admin-users">
        @foreach ($users as $user)
            <div class="admin-user">
                @if ($editingId === $user->id)
                    <div class="admin-user__edit">
                        <div class="form-group">
                            <label class="form-label">Naam</label>
                            <input type="text" wire:model="editName" class="form-input" />
                            @error('editName')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">E-mailadres</label>
                            <input type="email" wire:model="editEmail" class="form-input" />
                            @error('editEmail')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nieuw wachtwoord (optioneel)</label>
                            <input type="password" wire:model="editPassword" class="form-input"
                                placeholder="Laat leeg om niet te wijzigen" />
                            @error('editPassword')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rol</label>
                            <select wire:model="editRole" class="form-input">
                                <option value="lid">Lid</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="admin-form__actions">
                            <button wire:click="cancelEdit" class="btn btn--sm">Annuleren</button>
                            <button wire:click="saveEdit" class="btn btn--primary btn--sm">Opslaan</button>
                        </div>
                    </div>
                @else
                    <div class="admin-user__avatar">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" />
                        @else
                            <span>{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="admin-user__info">
                        <span class="admin-user__name">{{ $user->name }}</span>
                        <span class="admin-user__email">{{ $user->email }}</span>
                    </div>
                    <span class="admin-user__role admin-user__role--{{ $user->role }}">
                        {{ $user->role }}
                    </span>
                    <button wire:click="startEdit({{ $user->id }})" class="admin-user__edit-btn">
                        <x-heroicon-o-pencil-square />
                    </button>
                    @if ($user->id !== auth()->id())
                        @if ($deletingId === $user->id)
                            <div class="admin-user__confirm">
                                <span>Verwijderen?</span>
                                <button wire:click="deleteUser({{ $user->id }})"
                                    class="btn btn--sm btn--danger">Ja</button>
                                <button wire:click="$set('deletingId', null)" class="btn btn--sm">Nee</button>
                            </div>
                        @else
                            <button wire:click="$set('deletingId', {{ $user->id }})" class="admin-user__delete">
                                <x-heroicon-o-trash />
                            </button>
                        @endif
                    @endif
                @endif
            </div>
        @endforeach
    </div>

</div>
