{{-- ================= WRAPPER FIX (HALAMAN INI SAJA) ================= --}}
<div
    style="
        position: fixed;
        top: 5rem;
        left: 1rem;
        right: 1rem;
        bottom: 1rem;
        overflow: hidden;
        z-index: 10;
    "
>
    {{-- ================= CONTAINER UTAMA ================= --}}
    <div class="flex h-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="flex flex-col h-full bg-white border-r border-slate-200
            {{ $selectedUserId ? 'hidden md:flex md:w-[360px] lg:w-[400px]' : 'w-full md:w-[360px] lg:w-[400px]' }}">

            <div class="p-4 border-b shrink-0 bg-slate-50">
                <h2 class="font-bold text-xl text-slate-800 mb-3">Chat</h2>

                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari percakapan..."
                    class="w-full p-2.5 text-sm rounded-xl border border-slate-200
                           focus:ring-2 focus:ring-[#fd2800] outline-none">
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
                @forelse($this->conversations as $user)
                    <button
                        wire:key="user-{{ $user->id }}"
                        wire:click="selectUser({{ $user->id }})"
                        class="w-full p-4 flex items-center gap-3 text-left border-b
                               hover:bg-slate-50 relative
                               {{ $selectedUserId === $user->id ? 'bg-slate-100' : '' }}">

                        <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center font-bold">
                            {{ substr($user->username, 0, 1) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold truncate">{{ $user->username }}</h3>
                            <p class="text-sm text-slate-500 truncate">
                                {{ $user->chats->first()->message ?? 'Mulai percakapan...' }}
                            </p>
                        </div>

                        @if($selectedUserId === $user->id)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#fd2800]"></div>
                        @endif
                    </button>
                @empty
                    <div class="p-6 text-center text-slate-400 text-sm">
                        Tidak ada user.
                    </div>
                @endforelse
            </div>
        </aside>

        {{-- ================= AREA CHAT ================= --}}
        <main class="flex flex-col h-full w-full overflow-hidden bg-[#efeae2]
            {{ $selectedUserId ? 'flex' : 'hidden md:flex flex-1' }}">

            @if($selectedUserId)

                {{-- Header --}}
                <header class="shrink-0 p-4 bg-slate-50 border-b flex items-center gap-3">
                    <button wire:click="$set('selectedUserId', null)"
                            class="md:hidden p-2 rounded-full hover:bg-slate-200">
                        ←
                    </button>

                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold">
                        {{ substr(\App\Models\User::find($selectedUserId)->username ?? 'U', 0, 1) }}
                    </div>

                    <div>
                        <h3 class="font-semibold">
                            {{ \App\Models\User::find($selectedUserId)->username ?? 'User' }}
                        </h3>
                        <p class="text-xs text-slate-500">Online</p>
                    </div>
                </header>

                {{-- ================= CHAT BOX ================= --}}
                <div id="chat-box"
                     class="flex-1 min-h-0 overflow-y-auto p-4 space-y-4 custom-scrollbar"
                     wire:poll.2s>

                    @foreach($this->messages as $msg)
                        @php
                            // ADMIN = MERAH, USER = PUTIH
                            $isAdmin = auth()->user()->role === 'admin'
                                       && $msg->sender_id === auth()->id();
                        @endphp

                        <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%] md:max-w-[60%]">

                                <div class="px-4 py-2 text-sm shadow rounded-2xl
                                    {{ $isAdmin
                                        ? 'bg-[#fd2800] text-white rounded-tr-none'
                                        : 'bg-white rounded-tl-none' }}">
                                    {{ $msg->message }}
                                </div>

                                <div class="text-[10px] text-slate-500 mt-1
                                    {{ $isAdmin ? 'text-right' : 'text-left' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Input --}}
                <div class="shrink-0 p-3 bg-slate-50 border-t">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <input
                            wire:model.defer="messageText"
                            type="text"
                            placeholder="Ketik pesan..."
                            class="flex-1 p-3 text-sm rounded-xl border border-slate-300
                                   focus:ring-1 focus:ring-[#fd2800] outline-none">

                        <button class="bg-[#fd2800] hover:bg-[#e02400] text-white p-3 rounded-xl">
                            Kirim
                        </button>
                    </form>
                </div>

            @else
                <div class="flex-1 flex items-center justify-center text-slate-400">
                    Pilih percakapan untuk mulai chat
                </div>
            @endif
        </main>
    </div>
</div>

{{-- ================= AUTO SCROLL CERDAS (FIXED) ================= --}}
<script>
function initChatScroll() {
    const box = document.getElementById('chat-box');
    if (!box) return false;

    let shouldAutoScroll = true;

    const isAtBottom = () =>
        box.scrollTop + box.clientHeight >= box.scrollHeight - 50;

    box.addEventListener('scroll', () => {
        shouldAutoScroll = isAtBottom();
    });

    Livewire.hook('morph.updated', () => {
        if (shouldAutoScroll) {
            box.scrollTop = box.scrollHeight;
        }
    });

    box.scrollTop = box.scrollHeight;
    return true;
}

document.addEventListener('livewire:initialized', () => {
    const wait = setInterval(() => {
        if (initChatScroll()) clearInterval(wait);
    }, 100);
});
</script>
