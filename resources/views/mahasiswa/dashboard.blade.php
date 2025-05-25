@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h2 class="text-2xl font-semibold mb-4">Dashboard Mahasiswa</h2>
        
        <!-- Kelas Aktif -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Kelas Aktif</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($kelasAktif as $kelas)
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h4 class="font-semibold text-lg text-gray-800">{{ $kelas->nama_kelas }}</h4>
                        <p class="text-gray-600">{{ $kelas->mataKuliah->nama_mk }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $kelas->dosen->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            {{ $kelas->mataKuliah->sks }} SKS
                        </p>
                        @if($kelas->mahasiswa->where('id', auth()->id())->first() && 
                            $kelas->mahasiswa->where('id', auth()->id())->first()->pivot->nilai_tugas !== null && 
                            $kelas->mahasiswa->where('id', auth()->id())->first()->pivot->nilai_uts !== null && 
                            $kelas->mahasiswa->where('id', auth()->id())->first()->pivot->nilai_uas !== null)
                            @php
                                $mahasiswaNilai = $kelas->mahasiswa->where('id', auth()->id())->first();
                            @endphp
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Nilai</h5>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <div>
                                        <p class="text-gray-500">Tugas</p>
                                        <p class="font-medium">{{ $mahasiswaNilai->pivot->nilai_tugas ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">UTS</p>
                                        <p class="font-medium">{{ $mahasiswaNilai->pivot->nilai_uts ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">UAS</p>
                                        <p class="font-medium">{{ $mahasiswaNilai->pivot->nilai_uas ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <p class="text-gray-500">Nilai Akhir</p>
                                    <p class="font-medium text-lg">{{ $mahasiswaNilai->pivot->nilai_akhir ?? '-' }} 
                                        @if($mahasiswaNilai->pivot->grade)
                                            ({{ $mahasiswaNilai->pivot->grade }})
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-gray-500">Nilai belum tersedia</p>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full">
                        <p class="text-gray-500 text-center">Tidak ada kelas aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total SKS -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total SKS</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $totalSKS }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IPK -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">IPK</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($ipk, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Kelas</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $totalKelas }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
