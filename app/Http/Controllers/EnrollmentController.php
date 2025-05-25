<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        
        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran yang aktif.');
        }

        // Kelas yang tersedia (belum diambil mahasiswa ini)
        $kelastersedia = Kelas::with(['mataKuliah', 'dosen', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->whereDoesntHave('mahasiswa', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        // Kelas yang sudah diambil
        $kelasDiambil = Kelas::with(['mataKuliah', 'dosen', 'tahunAjaran'])
            ->whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get();

        // Hitung total SKS yang sudah diambil
        $totalSKS = $kelasDiambil->sum(function ($kelas) {
            return $kelas->mataKuliah->sks;
        });

        $batasSKS = 24; // Batas maksimal SKS per semester

        return view('mahasiswa.enrollment', compact(
            'kelastersedia', 
            'kelasDiambil', 
            'totalSKS', 
            'batasSKS',
            'tahunAjaranAktif'
        ));
    }

    public function enroll(Request $request, Kelas $kelas)
    {
        $user = auth()->user();
        
        // Cek apakah sudah terdaftar
        if ($kelas->mahasiswa()->where('users.id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        // Cek kapasitas kelas
        if ($kelas->mahasiswa()->count() >= $kelas->kapasitas) {
            return redirect()->back()->with('error', 'Kelas sudah penuh.');
        }

        // Cek batas SKS
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        $totalSKSSekarang = Kelas::whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->with('mataKuliah')
            ->get()
            ->sum(function ($kelas) {
                return $kelas->mataKuliah->sks;
            });

        $batasSKS = 24;
        if (($totalSKSSekarang + $kelas->mataKuliah->sks) > $batasSKS) {
            return redirect()->back()->with('error', 'Pengambilan kelas ini akan melebihi batas SKS maksimal (' . $batasSKS . ' SKS).');
        }

        // Daftarkan mahasiswa ke kelas
        $kelas->mahasiswa()->attach($user->id);

        return redirect()->back()->with('success', 'Berhasil mendaftar ke kelas ' . $kelas->nama_kelas);
    }

    public function drop(Request $request, Kelas $kelas)
    {
        $user = auth()->user();
        
        // Cek apakah terdaftar
        if (!$kelas->mahasiswa()->where('users.id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        // Hapus dari kelas
        $kelas->mahasiswa()->detach($user->id);

        return redirect()->back()->with('success', 'Berhasil keluar dari kelas ' . $kelas->nama_kelas);
    }
}
