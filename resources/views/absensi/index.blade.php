@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('header')

@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="flex justify-between items-center px-4 pt-8 pb-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Absensi Kelas: {{ $kelas->nama_kelas }}
            </h2>
            <p class="text-sm text-gray-600">{{ $kelas->mataKuliah->nama_mk }} - {{ $kelas->tahunAjaran->tahun_ajaran }} {{ $kelas->tahunAjaran->semester }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('absensi.create', $kelas) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Absensi
            </a>
            <a href="{{ route('kelas.dosen') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>

    <div class="p-6 bg-white border-b border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absensi as $abs)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $abs->tanggal->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $abs->tanggal->format('l') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $abs->materi }}</div>
                                @if($abs->keterangan)
                                    <div class="text-sm text-gray-500">{{ Str::limit($abs->keterangan, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $abs->jumlah_hadir }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $abs->jumlah_sakit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $abs->jumlah_izin }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $abs->jumlah_alpha }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('absensi.show', [$kelas, $abs]) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                <a href="{{ route('absensi.edit', [$kelas, $abs]) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('absensi.destroy', [$kelas, $abs]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Yakin ingin menghapus absensi ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Belum ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $absensi->links() }}
        </div>
    </div>
</div>
@endsection
