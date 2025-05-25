@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelas yang Saya Ambil
        </h2>
    </div>
@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kelas as $k)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $k->nama_kelas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $k->mataKuliah->nama_mk }}</div>
                                <div class="text-sm text-gray-500">{{ $k->mataKuliah->kode_mk }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $k->mataKuliah->sks }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $k->dosen->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $k->tahunAjaran->tahun_ajaran }}</div>
                                <div class="text-sm text-gray-500">{{ $k->tahunAjaran->semester }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $mahasiswa = $k->mahasiswa->where('id', auth()->id())->first();
                                    $grade = $mahasiswa ? $mahasiswa->pivot->grade : null;
                                @endphp
                                @if($grade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($grade == 'A') bg-green-100 text-green-800
                                        @elseif(in_array($grade, ['B+', 'B'])) bg-blue-100 text-blue-800
                                        @elseif(in_array($grade, ['C+', 'C'])) bg-yellow-100 text-yellow-800
                                        @elseif(in_array($grade, ['D+', 'D'])) bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        Grade: {{ $grade }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Anda belum mengambil kelas apapun
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $kelas->links() }}
        </div>
    </div>
</div>
@endsection
