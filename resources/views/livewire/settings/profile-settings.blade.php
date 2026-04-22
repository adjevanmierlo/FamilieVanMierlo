<div class="settings">

    <div class="settings-header">
        <h1 class="settings-title">Instellingen</h1>
    </div>

    <div class="settings-grid">

        {{-- Profiel --}}
        <div class="settings-card">
            <h2 class="settings-card__title">Mijn profiel</h2>

            <div class="settings-avatar">
                @if (Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                        class="settings-avatar__img" />
                @else
                    <div class="settings-avatar__placeholder">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <label class="settings-avatar__upload">
                    <input type="file" wire:model="avatar" accept="image/*" class="hidden" />
                    <x-heroicon-o-camera class="settings-avatar__icon" />
                </label>
            </div>

            @if ($saved)
                <div class="settings-success">
                    <x-heroicon-o-check-circle />
                    Opgeslagen!
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Naam</label>
                <input type="text" wire:model="name" class="form-input" />
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">E-mailadres</label>
                <input type="email" wire:model="email" class="form-input" />
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="save" class="btn btn--primary">
                Opslaan
            </button>
        </div>

        {{-- Weergave --}}
        <div class="settings-card">
            <h2 class="settings-card__title">Weergave</h2>

            <div class="settings-theme" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', val => {
                localStorage.setItem('theme', val ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', val);
            });
            document.documentElement.classList.toggle('dark', dark);">

                <div class="settings-theme__option" @click="dark = false"
                    :class="{ 'settings-theme__option--active': !dark }">
                    <x-heroicon-o-sun class="settings-theme__icon" />
                    <span>Licht</span>
                </div>

                <div class="settings-theme__option" @click="dark = true"
                    :class="{ 'settings-theme__option--active': dark }">
                    <x-heroicon-o-moon class="settings-theme__icon" />
                    <span>Donker</span>
                </div>

            </div>
        </div>

    </div>

</div>
