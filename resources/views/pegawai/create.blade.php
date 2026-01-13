<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Lengkapi Data Pegawai</h2>

        <!-- ERROR DISPLAY -->
        @if ($errors->any())
            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
                <p class="font-semibold">Data tidak lengkap atau ada kesalahan:</p>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pegawai.store') }}" class="space-y-4">
            @csrf

            <!-- NIP PEGAWAI -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pegawai</label>
                <input 
                    type="text"
                    name="nip_pegawai" 
                    value="{{ old('nip_pegawai') }}"
                    placeholder="Nomor Induk Pegawai"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500
                        @error('nip_pegawai') border-red-500 @enderror"
                    required
                >
                @error('nip_pegawai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NAMA PEGAWAI -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
                <input 
                    type="text"
                    name="nama_pegawai"
                    value="{{ old('nama_pegawai') }}"
                    placeholder="Nama Lengkap"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500
                        @error('nama_pegawai') border-red-500 @enderror"
                    required
                >
                @error('nama_pegawai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BIDANG KERJA -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Kerja</label>
                <input 
                    type="text"
                    name="bidang_kerja"
                    value="{{ old('bidang_kerja') }}"
                    placeholder="Contoh: IT, HR, Keuangan"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500
                        @error('bidang_kerja') border-red-500 @enderror"
                    required
                >
                @error('bidang_kerja')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- JABATAN -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input 
                    type="text"
                    name="jabatan"
                    value="{{ old('jabatan') }}"
                    placeholder="Contoh: Staff, Manager, Supervisor"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500
                        @error('jabatan') border-red-500 @enderror"
                    required
                >
                @error('jabatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SUBMIT BUTTON -->
            <button 
                type="submit"
                class="w-full bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700 transition"
            >
                Simpan Data Pegawai
            </button>
        </form>
    </div>
</x-app-layout>
