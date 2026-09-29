<div class="flex flex-col h-[500px] bg-[var(--surface)] rounded-[32px] border border-zinc-100 dark:border-white/5 shadow-2xl overflow-hidden">
    <!-- Chat Header -->
    <div class="px-6 py-4 border-b border-zinc-100 dark:border-white/5 bg-zinc-50/50 dark:bg-white/[0.02] flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="size-2 rounded-full bg-green-500 animate-pulse"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-900 dark:text-white">Customer Messaging</span>
        </div>
        <flux:icon icon="chat-bubble-left-right" class="size-4 text-zinc-400" />
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4 flex flex-col-reverse">
        <div class="flex flex-col gap-4">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] flex flex-col gap-1">
                        <div class="px-4 py-2 rounded-2xl text-xs {{ $msg->sender_id === auth()->id() ? 'bg-[var(--active-2)] text-white rounded-tr-none' : 'bg-zinc-100 dark:bg-white/5 text-zinc-700 dark:text-zinc-200 rounded-tl-none' }}">
                            {{ $msg->content }}
                        </div>
                        <span class="text-[8px] font-bold text-zinc-400 uppercase {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            {{ $msg->sender->name }} • {{ $msg->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-zinc-50 dark:bg-white/[0.01] border-t border-zinc-100 dark:border-white/5">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input type="text" wire:model="newMessage" placeholder="Write a message to the customer..." class="flex-1 bg-white dark:bg-white/5 border border-zinc-200 dark:border-white/10 rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-[var(--active-2)] focus:border-transparent outline-none text-zinc-900 dark:text-white">
            <button type="submit" class="size-10 rounded-xl bg-[var(--active-2)] text-white flex items-center justify-center hover:opacity-90 transition-all shadow-lg shadow-[var(--active)]/20">
                <flux:icon icon="paper-airplane" class="size-4" />
            </button>
        </form>
    </div>
</div>
