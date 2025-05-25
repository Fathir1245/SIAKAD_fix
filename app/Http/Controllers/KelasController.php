<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KelasController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen', 'tahunAjaran'])->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::all();
        $dosen = User::whereHas('role', function($query) {
            $query->where('slug', 'dosen');
        })->get();
        $tahunAjaran = TahunAjaran::all();
        $mahasiswa = User::whereHas('role', function($query) {
            $query->where('slug', 'mahasiswa');
        })->get();
        
        return view('kelas.create', compact('mataKuliah', 'dosen', 'tahunAjaran', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kelas' => 'required|string|max:255',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'dosen_id' => 'required|exists:users,id',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
                'kapasitas' => 'required|integer|min:1|max:100',
                'mahasiswa' => 'array',
                'mahasiswa.*' => 'exists:users,id',
            ]);

            // Cek duplikasi nama kelas di tahun ajaran yang sama
            $existingKelas = Kelas::where('nama_kelas', $request->nama_kelas)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->first();

            if ($existingKelas) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nama_kelas' => 'Nama kelas sudah ada di tahun ajaran ini.']);
            }

            DB::beginTransaction();

            $kelas = Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $request->dosen_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'kapasitas' => $request->kapasitas,
            ]);

            // Attach mahasiswa jika ada dan tidak melebihi kapasitas
            if ($request->has('mahasiswa') && is_array($request->mahasiswa)) {
                $mahasiswaIds = $request->mahasiswa;
                
                if (count($mahasiswaIds) > $request->kapasitas) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['mahasiswa' => 'Jumlah mahasiswa melebihi kapasitas kelas.']);
                }
                
                $kelas->mahasiswa()->attach($mahasiswaIds);
            }

            DB::commit();

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['mataKuliah', 'dosen', 'tahunAjaran', 'mahasiswa']);
        return view('kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        $mataKuliah = MataKuliah::all();
        $dosen = User::whereHas('role', function($query) {
            $query->where('slug', 'dosen');
        })->get();
        $tahunAjaran = TahunAjaran::all();
        $mahasiswa = User::whereHas('role', function($query) {
            $query->where('slug', 'mahasiswa');
        })->get();
        
        // Load mahasiswa yang sudah terdaftar di kelas ini
        $kelas->load('mahasiswa');
        
        return view('kelas.edit', compact('kelas', 'mataKuliah', 'dosen', 'tahunAjaran', 'mahasiswa'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        try {
            $request->validate([
                'nama_kelas' => 'required|string|max:255',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'dosen_id' => 'required|exists:users,id',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
                'kapasitas' => 'required|integer|min:1|max:100',
                'mahasiswa' => 'array',
                'mahasiswa.*' => 'exists:users,id',
            ]);

            // Cek duplikasi nama kelas di tahun ajaran yang sama (kecuali kelas ini sendiri)
            $existingKelas = Kelas::where('nama_kelas', $request->nama_kelas)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('id', '!=', $kelas->id)
                ->first();

            if ($existingKelas) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nama_kelas' => 'Nama kelas sudah ada di tahun ajaran ini.']);
            }

            DB::beginTransaction();

            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $request->dosen_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'kapasitas' => $request->kapasitas,
            ]);

            // Sync mahasiswa dan cek kapasitas
            $mahasiswaIds = $request->mahasiswa ?? [];
            
            if (count($mahasiswaIds) > $request->kapasitas) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['mahasiswa' => 'Jumlah mahasiswa melebihi kapasitas kelas.']);
            }
            
            $kelas->mahasiswa()->sync($mahasiswaIds);

            DB::commit();

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Kelas $kelas)
    {
        try {
            DB::beginTransaction();
            
            // Hapus relasi mahasiswa terlebih dahulu
            $kelas->mahasiswa()->detach();
            
            // Hapus kelas
            $kelas->delete();
            
            DB::commit();
            
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kelas.index')->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

    public function kelasDosen()
    {
        $user = auth()->user();
        $kelas = Kelas::with(['mataKuliah', 'tahunAjaran', 'mahasiswa'])
            ->where('dosen_id', $user->id)
            ->paginate(10);
            
        return view('dosen.kelas', compact('kelas'));
    }

    public function kelasMahasiswa()
    {
        $user = auth()->user();
        $kelas = Kelas::with(['mataKuliah', 'dosen', 'tahunAjaran'])
            ->whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->paginate(10);
            
        return view('mahasiswa.kelas', compact('kelas'));
    }

    public function nilaiMahasiswa(Kelas $kelas)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
        
        $kelas->load(['mataKuliah', 'mahasiswa' => function($query) {
            $query->orderBy('name');
        }]);
        
        return view('kelas.nilai', compact('kelas'));
    }

    public function updateNilai(Request $request, Kelas $kelas, User $mahasiswa)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah nilai di kelas ini.');
        }
        
        $request->validate([
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung nilai akhir (30% tugas, 30% UTS, 40% UAS)
        $nilaiAkhir = ($request->nilai_tugas * 0.3) + 
                     ($request->nilai_uts * 0.3) + 
                     ($request->nilai_uas * 0.4);

        // Tentukan grade
        $grade = $this->hitungGrade($nilaiAkhir);

        // Update nilai di pivot table
        $kelas->mahasiswa()->updateExistingPivot($mahasiswa->id, [
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_akhir' => $nilaiAkhir,
            'grade' => $grade,
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function nilaiSaya()
    {
        $user = auth()->user();
        $kelas = Kelas::with(['mataKuliah', 'dosen', 'tahunAjaran'])
            ->whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();
            
        return view('mahasiswa.nilai', compact('kelas'));
    }

    private function hitungGrade($nilaiAkhir)
    {
        if ($nilaiAkhir >= 85) return 'A';
        if ($nilaiAkhir >= 80) return 'B+';
        if ($nilaiAkhir >= 75) return 'B';
        if ($nilaiAkhir >= 70) return 'C+';
        if ($nilaiAkhir >= 65) return 'C';
        if ($nilaiAkhir >= 60) return 'D+';
        if ($nilaiAkhir >= 55) return 'D';
        return 'E';
    }
}
