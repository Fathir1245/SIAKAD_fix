@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Kelas: {{ $kelas->nama_kelas }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('kelas.edit', $kelas) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <a href="{{ route('kelas.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informasi Kelas -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kelas</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->nama_kelas }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->mataKuliah->nama_mk }} ({{ $kelas->mataKuliah->kode_mk }})</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKS</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->mataKuliah->sks }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dosen</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->dosen->name }} ({{ $kelas->dosen->nim_nip }})</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->tahunAjaran->tahun_ajaran }} - {{ $kelas->tahunAjaran->semester }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $kelas->mahasiswa->count() }}/{{ $kelas->kapasitas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Mahasiswa ({{ $kelas->mahasiswa->count() }})</h3>
            
            @if($kelas->mahasiswa->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelas->mahasiswa->sortBy('name') as $index => $mahasiswa)
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $mahasiswa->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $mahasiswa->pivot->nilai_akhir ?? '-' }}
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
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection
