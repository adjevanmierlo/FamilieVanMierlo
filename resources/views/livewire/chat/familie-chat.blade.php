<div class="chat" x-data
    x-on:scroll-to-bottom.window="$nextTick(() => { $refs.messages.scrollTop = $refs.messages.scrollHeight })">

    <div class="chat-header">
        <div class="chat-header__icon">
            <x-heroicon-o-user-group />
        </div>
        <div class="chat-header__info">
            <h1 class="chat-header__title">Familie Van Mierlo</h1>
            <span class="chat-header__subtitle">
                @php
                    $onlineUsers = App\Models\User::where('id', '!=', Auth::id())
                        ->whereNotNull('last_seen_at')
                        ->where('last_seen_at', '>=', now()->subMinutes(2))
                        ->get();
                @endphp
                @if ($onlineUsers->count() > 0)
                    <span class="chat-online">
                        {{ $onlineUsers->pluck('name')->join(', ') }} online
                    </span>
                @else
                    Familiechat
                @endif
            </span>
        </div>
    </div>

    <div class="chat-messages" x-ref="messages">
        @forelse($messages as $msg)
            @php $isOwn = $msg->user_id === auth()->id(); @endphp
            <div class="chat-message {{ $isOwn ? 'chat-message--own' : '' }}"
                @if ($isOwn) style="--bubble-color: {{ Auth::user()->color ?? '#7CB9E8' }};" @endif>

                @if (!$isOwn)
                    <div class="chat-message__avatar">
                        @if ($msg->user->avatar)
                            <img src="{{ Storage::url($msg->user->avatar) }}" alt="{{ $msg->user->name }}" />
                        @else
                            <span>{{ substr($msg->user->name, 0, 1) }}</span>
                        @endif
                        @if ($msg->user->isOnline())
                            <span class="chat-message__online-dot"></span>
                        @endif
                    </div>
                @endif

                <div class="chat-message__content">
                    @if (!$isOwn)
                        <span class="chat-message__name" style="color: {{ $msg->user->color ?? '#7CB9E8' }};">
                            {{ $msg->user->name }}
                        </span>
                    @endif

                    @if ($msg->isDeleted())
                        <div class="chat-message__bubble chat-message__bubble--deleted">
                            <x-heroicon-o-no-symbol class="chat-message__deleted-icon" />
                            Dit bericht is verwijderd
                        </div>
                    @elseif($editingId === $msg->id)
                        <div class="chat-message__edit">
                            <input type="text" wire:model="editingMessage" wire:keydown.enter="saveEdit"
                                wire:keydown.escape="cancelEdit" class="chat-message__edit-input" />
                            <div class="chat-message__edit-actions">
                                <button wire:click="saveEdit" class="btn btn--primary btn--sm">Opslaan</button>
                                <button wire:click="cancelEdit" class="btn btn--sm">Annuleren</button>
                            </div>
                        </div>
                    @else
                        @if ($msg->attachment)
                            <img src="{{ Storage::url($msg->attachment) }}" alt="Bijlage"
                                class="chat-message__attachment" />
                        @endif
                        @if ($msg->message)
                            <div class="chat-message__bubble-wrapper" x-data="{ showMenu: false, touchTimer: null }"
                                @contextmenu.prevent="showMenu = true"
                                @touchstart="touchTimer = setTimeout(() => showMenu = true, 500)"
                                @touchend="clearTimeout(touchTimer)" @touchmove="clearTimeout(touchTimer)">

                                <div class="chat-message__bubble" @click.away="showMenu = false">
                                    {{ $msg->message }}
                                    <div class="chat-message__bubble-meta">
                                        <span class="chat-message__time">{{ $msg->created_at->format('H:i') }}</span>
                                        @if ($msg->is_edited && !$msg->isDeleted())
                                            <span class="chat-message__edited">bewerkt</span>
                                        @endif
                                        @if ($isOwn && !$msg->isDeleted())
                                            <div class="chat-message__reads">
                                                @foreach ($msg->reads->where('user_id', '!=', Auth::id()) as $read)
<div class="chat-message__read-avatar" title="{{ $read->user->name }}">
                                                        @if ($read->user->avatar)
<img src="{{ Storage::url($read->user->avatar) }}" alt="{{ $read->user->name }}" />
@else
<span>{{ substr($read->user->name, 0, 1) }}</span>
@endif
                                                    </div>
@endforeach
                                            </div>
@endif
                                    </div>
                                </div>

                                @if ($isOwn && !$msg->isDeleted())
<div class="chat-context-menu" x-show="showMenu" x-transition @click.outside="showMenu = false">
                                        <button wire:click="startEdit({{ $msg->id }})" @click="showMenu = false" class="chat-context-menu__item">
                                            <x-heroicon-o-pencil-square />
                                            <span>Bewerken</span>
                                        </button>
                                        <button wire:click="deleteMessage({{ $msg->id }})" @click="showMenu = false" class="chat-context-menu__item chat-context-menu__item--danger">
                                            <x-heroicon-o-trash />
                                            <span>Verwijderen</span>
                                        </button>
                                    </div>
@endif
                            </div>
@endif
@endif
                </div>
            </div>
        @empty
            <div class="chat-empty">
                <p>Nog geen berichten. Stuur het eerste bericht!</p>
            </div>
        @endforelse
    </div>

    <div class="chat-input">
        @if ($attachment)
<div class="chat-input__preview">
                <span>📎 Bijlage geselecteerd</span>
                <button wire:click="$set('attachment', null)" class="chat-input__remove">
                    <x-heroicon-o-x-mark />
                </button>
            </div>
 @endif
                                                    <div class="chat-input__bar">
                                                    <label class="chat-input__attach">
                                                    <input type="file" wire:model="attachment" accept="image/*"
                                                    class="hidden" />
                                                    <x-heroicon-o-paper-clip />
                                                    </label>
                                                    <input type="text" wire:model="message"
                                                    wire:keydown.enter="sendMessage"
                                                    placeholder="Typ een bericht..." class="chat-input__field" />
                                                    <button wire:click="sendMessage" class="chat-input__send">
                                                    <x-heroicon-o-paper-airplane />
                                                    </button>
                                                    </div>
                                                    </div>

                                                    </div>)
