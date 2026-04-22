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
                <a href="{{ route('chat') }}"
                    class="nav-link {{ request()->routeIs('chat') ? 'nav-link--active' : '' }}">
                    <x-heroicon-o-chat-bubble-left-right class="nav-icon" />
                    <span>Chat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('agenda') }}"
                    class="nav-link {{ request()->routeIs('agenda') ? 'nav-link--active' : '' }}">
                    <x-heroicon-o-calendar-days class="nav-icon" />
                    <span>Agenda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('notes') }}"
                    class="nav-link {{ request()->routeIs('notes') ? 'nav-link--active' : '' }}">
                    <x-heroicon-o-document-text class="nav-icon" />
                    <span>Notities</span>
                </a>
            </li>
            <li>
                <a href="{{ route('shopping') }}"
                    class="nav-link {{ request()->routeIs('shopping') ? 'nav-link--active' : '' }}">
                    <x-heroicon-o-shopping-cart class="nav-icon" />
                    <span>Boodschappen</span>
                </a>
            </li>
            <li>
                <a href="{{ route('photos') }}"
                    class="nav-link {{ request()->routeIs('photos') ? 'nav-link--active' : '' }}"> <x-heroicon-o-photo
                        class="nav-icon" />
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
    <nav class="nav-bottom-bar" x-data="{ moreOpen: false }">
        <a href="{{ route('dashboard') }}"
            class="nav-bottom-item {{ request()->routeIs('dashboard') ? 'nav-bottom-item--active' : '' }}">
            <x-heroicon-o-home class="nav-icon" />
            <span>Dashboard</span>
        </a>
        <a href="{{ route('chat') }}"
            class="nav-bottom-item {{ request()->routeIs('chat') ? 'nav-bottom-item--active' : '' }}">
            <x-heroicon-o-chat-bubble-left-right class="nav-icon" />
            <span>Chat</span>
        </a>
        <a href="{{ route('agenda') }}"
            class="nav-bottom-item {{ request()->routeIs('agenda') ? 'nav-bottom-item--active' : '' }}">
            <x-heroicon-o-calendar-days class="nav-icon" />
            <span>Agenda</span>
        </a>
        <a href="{{ route('shopping') }}"
            class="nav-bottom-item {{ request()->routeIs('shopping') ? 'nav-bottom-item--active' : '' }}">
            <x-heroicon-o-shopping-cart class="nav-icon" />
            <span>Boodschappen</span>
        </a>
        <button class="nav-bottom-item" @click="moreOpen = !moreOpen">
            <x-heroicon-o-squares-2x2 class="nav-icon" />
            <span>Meer</span>
        </button>

        {{-- Meer menu sheet --}}
        <div class="nav-more-sheet" x-show="moreOpen" @click.outside="moreOpen = false" x-transition>
            <div class="nav-more-sheet__handle"></div>
            <div class="nav-more-grid">
                <a href="{{ route('notes') }}"
                    class="nav-more-item {{ request()->routeIs('notes') ? 'nav-more-item--active' : '' }}"
                    @click="moreOpen = false">
                    <div class="nav-more-item__icon nav-more-item__icon--notities">
                        <x-heroicon-o-document-text />
                    </div>
                    <span>Notities</span>
                </a>
                <a href="{{ route('photos') }}"
                    class="nav-more-item {{ request()->routeIs('photos') ? 'nav-more-item--active' : '' }}"
                    @click="moreOpen = false">
                    <div class="nav-more-item__icon nav-more-item__icon--fotos">
                        <x-heroicon-o-photo />
                    </div>
                    <span>Foto's</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="nav-more-item" @click="moreOpen = false">
                    <div class="nav-more-item__icon nav-more-item__icon--instellingen">
                        <x-heroicon-o-cog-6-tooth />
                    </div>
                    <span>Instellingen</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-more-item nav-more-item--logout">
                        <div class="nav-more-item__icon nav-more-item__icon--logout">
                            <x-heroicon-o-arrow-right-on-rectangle />
                        </div>
                        <span>Uitloggen</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

</nav>
