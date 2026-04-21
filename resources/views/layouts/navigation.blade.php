<nav class="app-nav" x-data="{ open: false }">

    {{-- Desktop sidebar --}}
    <aside class="nav-sidebar">
        <div class="nav-logo">
            <a href="{{ route('dashboard') }}">Familie Van Mierlo</a>
        </div>

        <ul class="nav-links">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link--active' : '' }}">
                    <x-heroicon-o-home class="nav-icon" />
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <x-heroicon-o-chat-bubble-left-right class="nav-icon" />
                    <span>Chat</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <x-heroicon-o-calendar-days class="nav-icon" />
                    <span>Agenda</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <x-heroicon-o-document-text class="nav-icon" />
                    <span>Notities</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <x-heroicon-o-shopping-cart class="nav-icon" />
                    <span>Boodschappen</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <x-heroicon-o-photo class="nav-icon" />
                    <span>Foto's</span>
                </a>
            </li>
        </ul>

        <div class="nav-bottom">
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <x-heroicon-o-cog-6-tooth class="nav-icon" />
                <span>Instellingen</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link nav-link--logout">
                    <x-heroicon-o-arrow-right-on-rectangle class="nav-icon" />
                    <span>Uitloggen</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile bottom navigation --}}
    <nav class="nav-bottom-bar">
        <a href="{{ route('dashboard') }}"
            class="nav-bottom-item {{ request()->routeIs('dashboard') ? 'nav-bottom-item--active' : '' }}">
            <x-heroicon-o-home class="nav-icon" />
            <span>Dashboard</span>
        </a>
        <a href="#" class="nav-bottom-item">
            <x-heroicon-o-chat-bubble-left-right class="nav-icon" />
            <span>Chat</span>
        </a>
        <a href="#" class="nav-bottom-item">
            <x-heroicon-o-calendar-days class="nav-icon" />
            <span>Agenda</span>
        </a>
        <a href="#" class="nav-bottom-item">
            <x-heroicon-o-shopping-cart class="nav-icon" />
            <span>Boodschappen</span>
        </a>
        <a href="#" class="nav-bottom-item">
            <x-heroicon-o-photo class="nav-icon" />
            <span>Foto's</span>
        </a>
    </nav>

</nav>
