@extends('layouts.app')

@section('title', 'Kelola Nilai - ' . $kelas->nama_kelas)

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Nilai - {{ $kelas->nama_kelas }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $kelas->mataKuliah->nama_mk }} ({{ $kelas->mataKuliah->kode_mk }}) - {{ $kelas->mataKuliah->sks }} SKS
            </p>
        </div>
        <a href="{{ route('kelas.dosen') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas (30%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UTS (30%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UAS (40%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kelas->mahasiswa as $mahasiswa)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $mahasiswa->nim ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->name }}</div>
                                <div class="text-sm text-gray-500">{{ $mahasiswa->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $mahasiswa->pivot->nilai_tugas ? number_format($mahasiswa->pivot->nilai_tugas, 1) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $mahasiswa->pivot->nilai_uts ? number_format($mahasiswa->pivot->nilai_uts, 1) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $mahasiswa->pivot->nilai_uas ? number_format($mahasiswa->pivot->nilai_uas, 1) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $mahasiswa->pivot->nilai_akhir ? number_format($mahasiswa->pivot->nilai_akhir, 1) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($mahasiswa->pivot->grade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($mahasiswa->pivot->grade == 'A') bg-green-100 text-green-800
                                        @elseif(in_array($mahasiswa->pivot->grade, ['B+', 'B'])) bg-blue-100 text-blue-800
                                        @elseif(in_array($mahasiswa->pivot->grade, ['C+', 'C'])) bg-yellow-100 text-yellow-800
                                        @elseif(in_array($mahasiswa->pivot->grade, ['D+', 'D'])) bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $mahasiswa->pivot->grade }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openNilaiModal({{ $mahasiswa->id }}, '{{ $mahasiswa->name }}', {{ $mahasiswa->pivot->nilai_tugas ?? 0 }}, {{ $mahasiswa->pivot->nilai_uts ?? 0 }}, {{ $mahasiswa->pivot->nilai_uas ?? 0 }})"
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Input Nilai
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Belum ada mahasiswa yang terdaftar di kelas ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Nilai -->
<div id="nilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Input Nilai</h3>
            <p class="text-sm text-gray-600 mb-4">Mahasiswa: <span id="mahasiswaName" class="font-medium"></span></p>
            
            <form id="nilaiForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Tugas (30%)</label>
                    <input type="number" name="nilai_tugas" id="nilai_tugas" min="0" max="100" step="0.1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai UTS (30%)</label>
                    <input type="number" name="nilai_uts" id="nilai_uts" min="0" max="100" step="0.1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai UAS (40%)</label>
                    <input type="number" name="nilai_uas" id="nilai_uas" min="0" max="100" step="0.1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNilaiModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNilaiModal(mahasiswaId, mahasiswaName, nilaiTugas, nilaiUts, nilaiUas) {
    document.getElementById('mahasiswaName').textContent = mahasiswaName;
    document.getElementById('nilai_tugas').value = nilaiTugas || '';
    document.getElementById('nilai_uts').value = nilaiUts || '';
    document.getElementById('nilai_uas').value = nilaiUas || '';
    
    const form = document.getElementById('nilaiForm');
    form.action = `{{ route('kelas.nilai.update', [$kelas, ':mahasiswa']) }}`.replace(':mahasiswa', mahasiswaId);
    
    document.getElementById('nilaiModal').classList.remove('hidden');
}

function closeNilaiModal() {
    document.getElementById('nilaiModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('nilaiModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNilaiModal();
    }
});
</script>
@endsection
