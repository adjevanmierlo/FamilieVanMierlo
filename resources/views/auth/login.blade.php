<x-guest-layout>
    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="auth-logo">
                <a href="/">Familie Van Mierlo</a>
            </div>

            <h1 class="auth-title">Welkom terug</h1>
            <p class="auth-subtitle">Log in op de familie app</p>

            <x-auth-session-status class="auth-status" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">E-mailadres</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="form-input {{ $errors->has('email') ? 'form-input--error' : '' }}" />
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Wachtwoord</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="form-input {{ $errors->has('password') ? 'form-input--error' : '' }}" />
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-remember">
                    <label class="form-checkbox">
                        <input type="checkbox" name="remember" />
                        <span>Onthoud mij</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="form-link">
                            Wachtwoord vergeten?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    Inloggen
                </button>

            </form>
        </div>
    </div>
</x-guest-layout>
