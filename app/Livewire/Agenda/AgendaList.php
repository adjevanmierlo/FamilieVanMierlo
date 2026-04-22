<?php

namespace App\Livewire\Agenda;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class AgendaList extends Component
{
  public int $currentMonth;
  public int $currentYear;
  public ?Event $selectedEvent = null;
  public bool $editing = false;

  public string $title = '';
  public string $description = '';
  public string $startAt = '';
  public string $endAt = '';
  public bool $allDay = false;
  public string $color = '#7CB9E8';

  public array $colors = [
    '#E8956A',
    '#7CB9E8',
    '#82C596',
    '#F0C674',
    '#C3A6E8',
    '#E88FA0',
  ];

  public function mount(): void
  {
    $this->currentMonth = now()->month;
    $this->currentYear  = now()->year;
  }

  public function previousMonth(): void
  {
    $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
    $this->currentMonth = $date->month;
    $this->currentYear  = $date->year;
  }

  public function nextMonth(): void
  {
    $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
    $this->currentMonth = $date->month;
    $this->currentYear  = $date->year;
  }

  public function newEvent(string $date = ''): void
  {
    $this->selectedEvent = null;
    $this->title       = '';
    $this->description = '';
    $this->startAt     = $date ? $date . 'T09:00' : now()->format('Y-m-d\TH:i');
    $this->endAt       = $date ? $date . 'T10:00' : now()->format('Y-m-d\TH:i');
    $this->allDay      = false;
    $this->color       = '#7CB9E8';
    $this->editing     = true;
  }

  public function selectEvent(int $id): void
  {
    $this->selectedEvent = Event::findOrFail($id);
    $this->title       = $this->selectedEvent->title;
    $this->description = $this->selectedEvent->description ?? '';
    $this->startAt     = $this->selectedEvent->start_at->format('Y-m-d\TH:i');
    $this->endAt       = $this->selectedEvent->end_at?->format('Y-m-d\TH:i') ?? '';
    $this->allDay      = $this->selectedEvent->all_day;
    $this->color       = $this->selectedEvent->color ?? '#7CB9E8';
    $this->editing     = false;
  }

  public function saveEvent(): void
  {
    $this->validate([
      'title'   => 'required|min:1|max:255',
      'startAt' => 'required',
    ]);

    $data = [
      'user_id'     => Auth::id(),
      'title'       => trim($this->title),
      'description' => trim($this->description) ?: null,
      'start_at'    => $this->startAt,
      'end_at'      => $this->endAt ?: null,
      'all_day'     => $this->allDay,
      'color'       => $this->color,
    ];

    if ($this->selectedEvent) {
      $this->selectedEvent->update($data);
    } else {
      $this->selectedEvent = Event::create($data);
    }

    $this->editing = false;
  }

  public function editEvent(): void
  {
    $this->editing = true;
  }

  public function deleteEvent(int $id): void
  {
    Event::findOrFail($id)->delete();
    $this->selectedEvent = null;
    $this->editing = false;
  }

  public function cancelEdit(): void
  {
    $this->editing = false;
    $this->selectedEvent = null;
  }

  public function render()
  {
    $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
    $lastDay  = $firstDay->copy()->endOfMonth();

    $events = Event::whereBetween('start_at', [$firstDay, $lastDay])
      ->orderBy('start_at')
      ->get()
      ->groupBy(fn($e) => $e->start_at->format('Y-m-d'));

    $upcomingEvents = Event::where('start_at', '>=', now())
      ->orderBy('start_at')
      ->take(5)
      ->get();

    return view('livewire.agenda.agenda-list', compact('events', 'upcomingEvents', 'firstDay'));
  }
}
