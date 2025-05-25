@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Absensi - {{ $kelas->nama_kelas }}
            </h2>
            <p class="text-sm text-gray-600">{{ $kelas->mataKuliah->nama_mk }}</p>
        </div>
        <a href="{{ route('absensi.index', $kelas) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('absensi.store', $kelas) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" 
                           name="tanggal" 
                           id="tanggal"
                           value="{{ old('tanggal', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                </div>
                
                <div>
                    <label for="materi" class="block text-sm font-medium text-gray-700">Materi Pembelajaran</label>
                    <input type="text" 
                           name="materi" 
                           id="materi"
                           value="{{ old('materi') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Contoh: Pengenalan Laravel"
                           required>
                </div>
            </div>

            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                <textarea name="keterangan" 
                          id="keterangan"
                          rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Mahasiswa ({{ $kelas->mahasiswa->count() }})</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelas->mahasiswa as $index => $mahasiswa)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $mahasiswa->nim_nip }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="status[{{ $mahasiswa->id }}]" 
                                                       value="hadir"
                                                       class="form-radio text-green-600"
                                                       {{ old("status.{$mahasiswa->id}") == 'hadir' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-green-600">Hadir</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="status[{{ $mahasiswa->id }}]" 
                                                       value="sakit"
                                                       class="form-radio text-yellow-600"
                                                       {{ old("status.{$mahasiswa->id}") == 'sakit' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-yellow-600">Sakit</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="status[{{ $mahasiswa->id }}]" 
                                                       value="izin"
                                                       class="form-radio text-blue-600"
                                                       {{ old("status.{$mahasiswa->id}") == 'izin' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-blue-600">Izin</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                       name="status[{{ $mahasiswa->id }}]" 
                                                       value="alpha"
                                                       class="form-radio text-red-600"
                                                       {{ old("status.{$mahasiswa->id}", 'alpha') == 'alpha' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-red-600">Alpha</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" 
                                               name="keterangan_mahasiswa[{{ $mahasiswa->id }}]"
                                               value="{{ old("keterangan_mahasiswa.{$mahasiswa->id}") }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                               placeholder="Keterangan...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('absensi.index', $kelas) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto select "Hadir" untuk semua mahasiswa
document.addEventListener('DOMContentLoaded', function() {
    const hadirAllBtn = document.createElement('button');
    hadirAllBtn.type = 'button';
    hadirAllBtn.className = 'mb-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm';
    hadirAllBtn.textContent = 'Tandai Semua Hadir';
    hadirAllBtn.onclick = function() {
        document.querySelectorAll('input[type="radio"][value="hadir"]').forEach(radio => {
            radio.checked = true;
        });
    };
    
    const table = document.querySelector('table');
    table.parentNode.insertBefore(hadirAllBtn, table);
});
</script>
@endsection
