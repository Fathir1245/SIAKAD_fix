@extends('layouts.app')

@section('title', 'Pengambilan Kelas')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengambilan Kelas - {{ $tahunAjaranAktif->tahun_ajaran }} {{ $tahunAjaranAktif->semester }}
        </h2>
        <div class="text-sm text-gray-600">
            Total SKS: <span class="font-semibold {{ $totalSKS >= $batasSKS ? 'text-red-600' : 'text-green-600' }}">{{ $totalSKS }}/{{ $batasSKS }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Kelas yang Sudah Diambil -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kelas yang Sudah Diambil</h3>
            
            @if($kelasDiambil->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelasDiambil as $kelas)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $kelas->nama_kelas }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->mataKuliah->nama_mk }}</div>
                                        <div class="text-sm text-gray-500">{{ $kelas->mataKuliah->kode_mk }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->mataKuliah->sks }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->dosen->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('enrollment.drop', $kelas) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                onclick="return confirm('Yakin ingin keluar dari kelas ini?')"
                                                class="text-red-600 hover:text-red-900">
                                                Keluar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Anda belum mengambil kelas apapun.</p>
            @endif
        </div>
    </div>

    <!-- Kelas yang Tersedia -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kelas yang Tersedia</h3>
            
            @if($kelastersedia->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelastersedia as $kelas)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $kelas->nama_kelas }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->mataKuliah->nama_mk }}</div>
                                        <div class="text-sm text-gray-500">{{ $kelas->mataKuliah->kode_mk }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->mataKuliah->sks }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $kelas->dosen->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $kelas->mahasiswa->count() }}/{{ $kelas->kapasitas }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @php
                                            $willExceedSKS = ($totalSKS + $kelas->mataKuliah->sks) > $batasSKS;
                                            $isFull = $kelas->mahasiswa->count() >= $kelas->kapasitas;
                                        @endphp
                                        
                                        @if($isFull)
                                            <span class="text-gray-400">Penuh</span>
                                        @elseif($willExceedSKS)
                                            <span class="text-red-400" title="Akan melebihi batas SKS">Melebihi SKS</span>
                                        @else
                                            <form action="{{ route('enrollment.enroll', $kelas) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    Ambil
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Tidak ada kelas yang tersedia.</p>
            @endif
        </div>
    </div>
</div>
@endsection
