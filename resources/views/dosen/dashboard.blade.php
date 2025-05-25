@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h2 class="text-2xl font-semibold mb-4">Dashboard Dosen</h2>
        
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ $kelas->mahasiswa->count() }} Mahasiswa
                        </p>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('kelas.nilai', $kelas) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Kelola Nilai
                            </a>
                            <a href="{{ route('kelas.edit', $kelas) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Edit Kelas
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <p class="text-gray-500 text-center">Tidak ada kelas aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Total Kelas -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
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

            <!-- Total Mahasiswa -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Mahasiswa</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $totalMahasiswa }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 