<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isDosen()) {
            return $this->dosenDashboard($user);
        } else {
            return $this->mahasiswaDashboard($user);
        }
    }

    protected function adminDashboard()
    {
        $totalUsers = User::count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelas = Kelas::count();
        $totalDosen = User::whereHas('role', function($query) {
            $query->where('slug', 'dosen');
        })->count();
        $totalMahasiswa = User::whereHas('role', function($query) {
            $query->where('slug', 'mahasiswa');
        })->count();

        return view('dashboard', compact(
            'totalUsers', 
            'totalMataKuliah', 
            'totalKelas',
            'totalDosen',
            'totalMahasiswa'
        ));
    }

    protected function dosenDashboard($user)
    {
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        
        if (!$tahunAjaranAktif) {
            $kelasAktif = collect();
            $totalKelas = 0;
            $totalMahasiswa = 0;
        } else {
            $kelasAktif = Kelas::with(['mataKuliah', 'mahasiswa'])
                ->where('dosen_id', $user->id)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->get();

            $totalKelas = Kelas::where('dosen_id', $user->id)->count();
            $totalMahasiswa = $kelasAktif->sum(function ($kelas) {
                return $kelas->mahasiswa->count();
            });
        }

        return view('dosen.dashboard', compact('kelasAktif', 'totalKelas', 'totalMahasiswa'));
    }

    protected function mahasiswaDashboard($user)
    {
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        
        if (!$tahunAjaranAktif) {
            $kelasAktif = collect();
            $totalSKS = 0;
            $ipk = 0;
            $totalKelas = 0;
        } else {
            $kelasAktif = Kelas::with(['mataKuliah', 'dosen', 'mahasiswa' => function($query) use ($user) {
                    $query->where('users.id', $user->id);
                }])
                ->whereHas('mahasiswa', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->get();

            // Hitung total SKS
            $totalSKS = $kelasAktif->sum(function ($kelas) {
                return $kelas->mataKuliah->sks;
            });

            // Hitung IPK
            $totalNilai = 0;
            $totalSKSLulus = 0;
            
            foreach ($kelasAktif as $kelas) {
                $nilaiMahasiswa = $kelas->mahasiswa->where('id', $user->id)->first();
                if ($nilaiMahasiswa && 
                    $nilaiMahasiswa->pivot->nilai_akhir !== null && 
                    $nilaiMahasiswa->pivot->nilai_akhir >= 60) {
                    $bobot = $this->getBobotNilai($nilaiMahasiswa->pivot->grade);
                    $sks = $kelas->mataKuliah->sks;
                    $totalNilai += ($bobot * $sks);
                    $totalSKSLulus += $sks;
                }
            }

            $ipk = $totalSKSLulus > 0 ? $totalNilai / $totalSKSLulus : 0;
            $totalKelas = $kelasAktif->count();
        }

        return view('mahasiswa.dashboard', compact(
            'kelasAktif',
            'totalSKS',
            'ipk',
            'totalKelas'
        ));
    }

    protected function getBobotNilai($grade)
    {
        return match($grade) {
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D+' => 1.5,
            'D' => 1.0,
            'E' => 0.0,
            default => 0.0,
        };
    }
}
