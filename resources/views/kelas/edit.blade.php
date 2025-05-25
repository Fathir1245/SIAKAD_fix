@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Kelas
    </h2>
@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <form action="{{ route('kelas.update', $kelas) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kelas -->
                <div>
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('nama_kelas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Kuliah -->
                <div>
                    <label for="mata_kuliah_id" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                    <select name="mata_kuliah_id" id="mata_kuliah_id" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $kelas->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dosen -->
                <div>
                    <label for="dosen_id" class="block text-sm font-medium text-gray-700">Dosen</label>
                    <select name="dosen_id" id="dosen_id" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosen as $d)
                            <option value="{{ $d->id }}" {{ old('dosen_id', $kelas->dosen_id) == $d->id ? 'selected' : '' }}>
                                {{ $d->name }} ({{ $d->nim_nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($tahunAjaran as $ta)
                            <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $kelas->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                                {{ $ta->tahun_ajaran }} - {{ $ta->semester }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mahasiswa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($mahasiswa as $m)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="mahasiswa[]" value="{{ $m->id }}"
                                    {{ in_array($m->id, old('mahasiswa', $kelas->mahasiswa->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $m->name }} ({{ $m->nim_nip }})
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('mahasiswa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('kelas.index') }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 