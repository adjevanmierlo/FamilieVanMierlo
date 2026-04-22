<div class="agenda">

    <div class="agenda-calendar">
        <div class="agenda-calendar__header">
            <button wire:click="previousMonth" class="agenda-nav-btn">
                <x-heroicon-o-chevron-left />
            </button>
            <h2 class="agenda-month">
                {{ Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('nl')->isoFormat('MMMM YYYY') }}
            </h2>
            <button wire:click="nextMonth" class="agenda-nav-btn">
                <x-heroicon-o-chevron-right />
            </button>
            <button wire:click="newEvent" class="btn btn--primary btn--sm">
                <x-heroicon-o-plus class="btn-icon" />
            </button>
        </div>

        <div class="agenda-weekdays">
            @foreach (['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                <span>{{ $day }}</span>
            @endforeach
        </div>

        <div class="agenda-days">
            @php
                $startOfWeek = $firstDay->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                $endOfCalendar = $firstDay->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                $current = $startOfWeek->copy();
            @endphp

            @while ($current <= $endOfCalendar)
                @php
                    $dateKey = $current->format('Y-m-d');
                    $isToday = $current->isToday();
                    $isCurrentMonth = $current->month === $currentMonth;
                    $dayEvents = $events[$dateKey] ?? collect();
                @endphp

                <div wire:click="newEvent('{{ $dateKey }}')"
                    class="agenda-day {{ $isToday ? 'agenda-day--today' : '' }} {{ !$isCurrentMonth ? 'agenda-day--other' : '' }}">
                    <span class="agenda-day__number">{{ $current->day }}</span>
                    @foreach ($dayEvents->take(2) as $event)
                        <div wire:click.stop="selectEvent({{ $event->id }})" class="agenda-event"
                            style="background-color: {{ $event->color }}20; border-left: 3px solid {{ $event->color }};">
                            {{ $event->title }}
                        </div>
                    @endforeach
                    @if ($dayEvents->count() > 2)
                        <span class="agenda-day__more">+{{ $dayEvents->count() - 2 }}</span>
                    @endif
                </div>

                @php $current->addDay() @endphp
            @endwhile
        </div>
    </div>

    <div class="agenda-sidebar">
        @if ($editing)
            <div class="agenda-form">
                <h3 class="agenda-form__title">
                    {{ $selectedEvent ? 'Afspraak bewerken' : 'Nieuwe afspraak' }}
                </h3>

                <div class="form-group">
                    <label class="form-label">Titel</label>
                    <input type="text" wire:model="title" class="form-input" placeholder="Bijv. Verjaardag mama" />
                    @error('title')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Beschrijving</label>
                    <textarea wire:model="description" class="form-input" rows="3" placeholder="Optioneel..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Start</label>
                    <input type="datetime-local" wire:model="startAt" class="form-input" />
                    @error('startAt')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Einde</label>
                    <input type="datetime-local" wire:model="endAt" class="form-input" />
                </div>

                <label class="form-checkbox">
                    <input type="checkbox" wire:model="allDay" />
                    <span>Hele dag</span>
                </label>

                <div class="form-group">
                    <label class="form-label">Kleur</label>
                    <div class="agenda-colors">
                        @foreach ($colors as $c)
                            <button type="button" wire:click="$set('color', '{{ $c }}')"
                                class="agenda-color {{ $color === $c ? 'agenda-color--active' : '' }}"
                                style="background-color: {{ $c }};"></button>
                        @endforeach
                    </div>
                </div>

                <div class="agenda-form__actions">
                    <button wire:click="cancelEdit" class="btn btn--sm">Annuleren</button>
                    <button wire:click="saveEvent" class="btn btn--primary btn--sm">Opslaan</button>
                </div>
            </div>
        @elseif($selectedEvent)
            <div class="agenda-detail">
                <div class="agenda-detail__color" style="background-color: {{ $selectedEvent->color }};"></div>
                <h3 class="agenda-detail__title">{{ $selectedEvent->title }}</h3>
                <p class="agenda-detail__date">
                    {{ $selectedEvent->start_at->locale('nl')->isoFormat('dddd D MMMM YYYY') }}
                    @if (!$selectedEvent->all_day)
                        {{ $selectedEvent->start_at->format('H:i') }}
                        @if ($selectedEvent->end_at)
                            – {{ $selectedEvent->end_at->format('H:i') }}
                        @endif
                    @endif
                </p>
                @if ($selectedEvent->description)
                    <p class="agenda-detail__description">{{ $selectedEvent->description }}</p>
                @endif
                <p class="agenda-detail__author">Toegevoegd door {{ $selectedEvent->user->name }}</p>
                <div class="agenda-detail__actions">
                    <button wire:click="editEvent" class="btn btn--sm">
                        <x-heroicon-o-pencil-square class="btn-icon" />
                    </button>
                    <button wire:click="deleteEvent({{ $selectedEvent->id }})" class="btn btn--sm btn--danger">
                        <x-heroicon-o-trash class="btn-icon" />
                    </button>
                </div>
            </div>
        @else
            <div class="agenda-upcoming">
                <h3 class="agenda-upcoming__title">Aankomende afspraken</h3>
                @forelse($upcomingEvents as $event)
                    <div wire:click="selectEvent({{ $event->id }})" class="agenda-upcoming-item">
                        <div class="agenda-upcoming-item__color" style="background-color: {{ $event->color }};"></div>
                        <div class="agenda-upcoming-item__content">
                            <span class="agenda-upcoming-item__title">{{ $event->title }}</span>
                            <span class="agenda-upcoming-item__date">
                                {{ $event->start_at->locale('nl')->isoFormat('D MMM') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="agenda-empty">Geen aankomende afspraken.</p>
                @endforelse
            </div>
        @endif
    </div>

</div>
