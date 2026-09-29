<div class="chat-center flex h-[calc(100vh-120px)] min-h-[560px] overflow-hidden rounded-2xl border border-slate-200 bg-[var(--surface)] shadow-[var(--shadow-card)] dark:border-slate-800">
    <!-- Sidebar: Conversations List -->
    <div class="{{ $selectedConversation ? 'hidden md:flex' : 'flex' }} w-full flex-col border-r border-slate-200 dark:border-slate-800 md:w-[360px] md:shrink-0">
        <div class="border-b border-slate-200 p-5 dark:border-slate-800">
            <div class="flex items-center justify-between"><div><p class="label-premium">Piiston inbox</p><h2 class="mt-1 text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white">Messages</h2></div><span class="rounded-full bg-[var(--primary-light)] px-2.5 py-1 text-[10px] font-black text-[var(--active)]">{{ $conversations->count() }}</span></div>
            <div class="relative mt-5">
                <flux:icon icon="magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-zinc-400" />
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher une discussion..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-xs text-slate-900 outline-none transition focus:border-[var(--active)] focus:ring-2 focus:ring-[var(--active)]/20 dark:border-slate-700 dark:bg-white/5 dark:text-white">
            </div>
            <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                @foreach(['All' => 'Toutes', 'Unread' => 'Non lues', 'Personal' => 'Direct', 'Groups' => 'Groupes'] as $key => $label)
                    <button type="button" wire:click="setFilter('{{ $key }}')" class="shrink-0 rounded-xl px-3 py-1.5 text-[10px] font-black uppercase tracking-wider transition {{ $filter === $key ? 'bg-[var(--active)] text-white' : 'bg-slate-100 text-slate-500 dark:bg-white/5 dark:text-slate-400' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $conv)
                @php
                    $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
                    $name = $conv->name ?? ($otherParticipant ? $otherParticipant->name : 'Chat');
                    $isActive = $selectedConversationId == $conv->id;
                @endphp
                <button wire:key="conversation-{{ $conv->id }}" wire:click="selectConversation({{ $conv->id }})" class="flex w-full items-center gap-3 border-b border-slate-100 p-4 text-left transition-colors hover:bg-slate-50 dark:border-slate-800/70 dark:hover:bg-white/[0.03] {{ $isActive ? 'bg-[var(--primary-light)]/60 dark:bg-white/[0.06]' : '' }}">
                    <div class="relative flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[var(--primary-light)] text-[var(--active)] font-black">
                        {{ strtoupper(substr($name, 0, 1)) }}
                        @if($conv->lastMessage && $conv->lastMessage->sender_id !== auth()->id())<span class="absolute -right-0.5 -top-0.5 size-2.5 rounded-full border-2 border-[var(--surface)] bg-[var(--active)]"></span>@endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $name }}</h3>
                            <span class="text-[10px] font-bold uppercase text-slate-400">{{ $conv->updated_at->diffForHumans(null, true) }}</span>
                        </div>
                        <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                            @if($conv->lastMessage)
                                {{ $conv->lastMessage->sender_id === auth()->id() ? 'Moi: ' : '' }}{{ $conv->lastMessage->content }}
                            @else
                                <span class="italic text-zinc-400">Aucun message</span>
                            @endif
                        </p>
                    </div>
                </button>
            @empty
                <div class="p-10 text-center">
                    <flux:icon icon="chat-bubble-left-right" class="size-10 text-zinc-200 mx-auto mb-4" />
                    <p class="text-sm text-zinc-400 font-bold uppercase tracking-widest">Aucune discussion</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="{{ $selectedConversation ? 'flex' : 'hidden md:flex' }} min-w-0 flex-1 flex-col bg-slate-50/70 dark:bg-slate-950/30">
        @if($selectedConversation)
            <!-- Chat Header -->
            <div class="flex items-center justify-between border-b border-slate-200 bg-[var(--surface)] px-4 py-3 shadow-sm md:px-6 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <button type="button" wire:click="clearConversation" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 md:hidden dark:hover:bg-white/5"><flux:icon icon="arrow-left" class="size-5" /></button>
                    <div class="size-10 rounded-xl bg-[var(--active)]/10 flex items-center justify-center text-[var(--active)] font-black text-xs">
                        @php
                            $otherParticipant = $selectedConversation->participants->where('id', '!=', auth()->id())->first();
                            $name = $selectedConversation->name ?? ($otherParticipant ? $otherParticipant->name : 'Chat');
                        @endphp
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $name }}</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="size-1.5 rounded-full bg-green-500"></div>
                            <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">En ligne</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <button type="button" title="Appel audio" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-[var(--active)] dark:hover:bg-white/5">
                        <flux:icon icon="phone" class="size-5" />
                    </button>
                    <button type="button" title="Appel vidéo" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-[var(--active)] dark:hover:bg-white/5">
                        <flux:icon icon="video-camera" class="size-5" />
                    </button>
                    <button type="button" title="Détails" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-[var(--active)] dark:hover:bg-white/5">
                        <flux:icon icon="information-circle" class="size-5" />
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex flex-1 flex-col overflow-y-auto p-4 md:p-7">
                <div class="mt-auto flex flex-col gap-4">
                    @foreach($messages as $msg)
                        <div wire:key="message-{{ $msg->id }}" class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex max-w-[82%] flex-col gap-1.5 md:max-w-[70%]">
                                <div class="rounded-2xl px-4 py-2.5 text-sm shadow-sm {{ $msg->sender_id === auth()->id() ? 'rounded-br-sm bg-[var(--active)] text-white' : 'rounded-bl-sm border border-slate-200 bg-[var(--surface)] text-slate-700 dark:border-slate-800 dark:text-slate-200' }}">
                                    {{ $msg->content }}
                                </div>
                                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    {{ $msg->sender->name }} • {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white dark:bg-zinc-900 border-t border-zinc-100 dark:border-white/5">
                <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                    <button type="button" title="Ajouter une pièce jointe" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-[var(--active)] dark:hover:bg-white/5">
                        <flux:icon icon="paper-clip" class="size-5" />
                    </button>
                    <input type="text" wire:model="newMessage" placeholder="Message..." class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[var(--active)] focus:ring-2 focus:ring-[var(--active)]/20 dark:border-slate-700 dark:bg-white/5 dark:text-white">
                    <button type="submit" title="Envoyer" class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[var(--active)] text-white shadow-lg shadow-[var(--active)]/25 transition hover:-translate-y-0.5">
                        <flux:icon icon="paper-airplane" class="size-5" />
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                <div class="size-24 rounded-[40px] bg-[var(--active)]/5 flex items-center justify-center mb-6">
                    <flux:icon icon="chat-bubble-left-right" class="size-10 text-[var(--active)]/40" />
                </div>
                <h3 class="text-xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Sélectionnez une discussion</h3>
                <p class="text-sm text-zinc-400 font-bold uppercase tracking-widest mt-2 max-w-xs">Choisissez un contact ou une réparation pour commencer à discuter en temps réel.</p>
            </div>
        @endif
    </div>
</div>
