{{-- ========================================================= --}}
{{-- CHAT BANTUAN USER – FULL SCREEN ADMIN STYLE --}}
{{-- ========================================================= --}}

{{-- ================= WRAPPER FIX (HALAMAN INI SAJA) ================= --}}
<div
    style="
        position: fixed;
        top: 5rem;        /* tinggi navbar */
        left: 1rem;
        right: 1rem;
        bottom: 1rem;
        z-index: 40;
    "
>
    {{-- ================= CONTAINER UTAMA ================= --}}
    <div class="flex flex-col h-full bg-slate-900 border border-slate-700
                rounded-2xl shadow-2xl overflow-hidden">

        {{-- ================= HEADER (FIXED) ================= --}}
        <div class="shrink-0 px-6 py-4 bg-slate-800 border-b border-slate-700
                    flex justify-between items-center">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#fd2800]/20
                            flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#fd2800]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-100 text-sm md:text-base">
                        Layanan Bantuan
                    </h3>
                    <p class="text-xs text-slate-400">
                        Admin siap membantu Anda
                    </p>
                </div>
            </div>

            <span class="flex items-center gap-1.5 bg-[#fd2800]/15 text-[#fd2800]
                         px-3 py-1 rounded-full text-[10px] font-semibold
                         border border-[#fd2800]/30">
                <span class="w-1.5 h-1.5 rounded-full bg-[#fd2800] animate-pulse"></span>
                ONLINE
            </span>
        </div>

        {{-- ================= CHAT BODY (SCROLL SATU-SATUNYA) ================= --}}
        <div id="chat-container"
             class="flex-1 min-h-0 overflow-y-auto
                    p-4 md:p-6 space-y-5
                    bg-slate-900 scroll-smooth"
             wire:poll.2s>

            @forelse($this->messages as $msg)
                @php $isMe = $msg->sender_id === auth()->id(); @endphp

                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] md:max-w-[60%] flex flex-col
                                {{ $isMe ? 'items-end' : 'items-start' }}">

                        {{-- Nama --}}
                        <span class="text-[10px] text-slate-400 mb-1 px-1">
                            {{ $isMe ? 'Anda' : $msg->sender->username }}
                        </span>

                        {{-- Bubble --}}
                        <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow
                            {{ $isMe
                                ? 'bg-[#fd2800] text-white rounded-br-none'
                                : 'bg-slate-100 text-slate-800 rounded-bl-none' }}">
                            {{ $msg->message }}
                        </div>

                        {{-- Waktu --}}
                        <span class="text-[9px] text-slate-500 mt-1">
                            {{ $msg->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full
                            text-slate-400 text-sm">
                    Belum ada pesan
                </div>
            @endforelse
        </div>

        {{-- ================= INPUT (FIXED BAWAH) ================= --}}
        <div class="shrink-0 p-4 bg-slate-800 border-t border-slate-700">
            <form wire:submit.prevent="sendMessage"
                  class="flex gap-2 items-center">

                <input
                    wire:model.defer="messageText"
                    type="text"
                    placeholder="Tulis pesan..."
                    class="flex-1 bg-slate-900 text-slate-100 rounded-xl
                           ring-1 ring-slate-600
                           focus:ring-2 focus:ring-[#fd2800]
                           placeholder:text-slate-400
                           text-sm py-3 px-4">

                <button type="submit"
                        class="bg-[#fd2800] hover:bg-[#e02400]
                               text-white px-4 py-3 rounded-xl
                               shadow-md transition">
                    Kirim
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ================= AUTO SCROLL CERDAS ================= --}}
<script>
document.addEventListener('livewire:initialized', () => {
    const container = document.getElementById('chat-container');
    if (!container) return;

    let shouldAutoScroll = true;

    // cek apakah posisi scroll di bawah
    const isAtBottom = () => {
        return (
            container.scrollTop + container.clientHeight
            >= container.scrollHeight - 50
        );
    };

    // ketika user scroll manual
    container.addEventListener('scroll', () => {
        shouldAutoScroll = isAtBottom();
    });

    // ketika Livewire update (polling / pesan baru)
    Livewire.hook('morph.updated', () => {
        if (shouldAutoScroll) {
            container.scrollTop = container.scrollHeight;
        }
    });

    // scroll awal saat halaman pertama dibuka
    requestAnimationFrame(() => {
        container.scrollTop = container.scrollHeight;
    });
});
</script>
