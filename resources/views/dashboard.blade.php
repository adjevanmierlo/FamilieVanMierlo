<x-app-layout>
    <div class="dashboard">

        <div class="dashboard-header">
            <div>
                <p class="dashboard-greeting">Goedendag,</p>
                <h1 class="dashboard-name">{{ Auth::user()->name }}</h1>
            </div>
            <div class="dashboard-date">
                {{ now()->locale('nl')->isoFormat('dddd D MMMM') }}
            </div>
        </div>

        <div class="dashboard-grid">

            <a href="#" class="dashboard-card dashboard-card--chat">
                <div class="dashboard-card__icon">
                    <x-heroicon-o-chat-bubble-left-right />
                </div>
                <div class="dashboard-card__content">
                    <h2>Chat</h2>
                    <p>Berichten van de familie</p>
                </div>
            </a>

            <a href="#" class="dashboard-card dashboard-card--agenda">
                <div class="dashboard-card__icon">
                    <x-heroicon-o-calendar-days />
                </div>
                <div class="dashboard-card__content">
                    <h2>Agenda</h2>
                    <p>Afspraken en gebeurtenissen</p>
                </div>
            </a>

            <a href="#" class="dashboard-card dashboard-card--notities">
                <div class="dashboard-card__icon">
                    <x-heroicon-o-document-text />
                </div>
                <div class="dashboard-card__content">
                    <h2>Notities</h2>
                    <p>Memo's en aantekeningen</p>
                </div>
            </a>

            <a href="#" class="dashboard-card dashboard-card--boodschappen">
                <div class="dashboard-card__icon">
                    <x-heroicon-o-shopping-cart />
                </div>
                <div class="dashboard-card__content">
                    <h2>Boodschappen</h2>
                    <p>De boodschappenlijst</p>
                </div>
            </a>

            <a href="#" class="dashboard-card dashboard-card--fotos">
                <div class="dashboard-card__icon">
                    <x-heroicon-o-photo />
                </div>
                <div class="dashboard-card__content">
                    <h2>Foto's</h2>
                    <p>Het familiefotoboek</p>
                </div>
            </a>

        </div>

    </div>
</x-app-layout>
