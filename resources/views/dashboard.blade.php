<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pegawai
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- SUCCESS MESSAGE -->
            @if (session('success'))
                <div class="mb-4 p-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR MESSAGE (Jika pegawai tidak ada) -->
            @if (!auth()->user()->pegawai)
                <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
                    <p><strong>Data Pegawai Tidak Ditemukan</strong></p>
                    <p>Silakan isi data pegawai terlebih dahulu.</p>
                    <a href="{{ route('pegawai.create') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                        Isi Data Pegawai
                    </a>
                </div>
            @else

            <!-- CARD DATA PEGAWAI -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Data Pegawai Anda
                    </h3>

                    <table class="w-full text-sm border-collapse">
                        <tr class="border-b">
                            <td class="py-2 font-semibold w-1/3">NIP</td>
                            <td class="py-2">
                                {{ auth()->user()->pegawai->nip_pegawai ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr class="border-b">
                            <td class="py-2 font-semibold">Nama Pegawai</td>
                            <td class="py-2">
                                {{ auth()->user()->pegawai->nama_pegawai ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr class="border-b">
                            <td class="py-2 font-semibold">Bidang Kerja</td>
                            <td class="py-2">
                                {{ auth()->user()->pegawai->bidang_kerja ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr class="border-b">
                            <td class="py-2 font-semibold">Jabatan</td>
                            <td class="py-2">
                                {{ auth()->user()->pegawai->jabatan ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="py-2 font-semibold">Status Pegawai</td>
                            <td class="py-2">
                                @if (auth()->user()->pegawai)
                                    <span class="px-3 py-1 text-xs rounded-full
                                        {{ auth()->user()->pegawai->status_pegawai === 'aktif'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst(auth()->user()->pegawai->status_pegawai ?? 'N/A') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <!-- INFO -->
                    <div class="mt-6 text-sm text-gray-500">
                        <p>
                            Data pegawai bersifat <strong>read-only</strong>.
                        </p>
                        <p>
                            Untuk perubahan data, silakan hubungi <strong>Administrator</strong>.
                        </p>
                    </div>

                </div>
            </div>

            @endif

        </div>
    </div>
</x-app-layout>
