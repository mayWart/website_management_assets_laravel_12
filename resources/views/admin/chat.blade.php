<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Judul Halaman --}}
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#171717]">
                Live Chat <span class="text-[#fd2800]">Admin</span>
            </h1>
            <span class="text-sm text-slate-500">
                Pantau dan balas pesan user secara real-time.
            </span>
        </div>

        {{-- Panggil Livewire Component --}}
        {{-- Karena file sudah dipindah ke folder livewire, kita panggil seperti ini: --}}
        <livewire:admin-chat />
        
    </div>
</x-app-layout>