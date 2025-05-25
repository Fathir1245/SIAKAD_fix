<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Kelas $kelas)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $kelas->load(['mataKuliah', 'tahunAjaran']);
        
        $absensi = Absensi::where('kelas_id', $kelas->id)
            ->with('detailAbsensi')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('absensi.index', compact('kelas', 'absensi'));
    }

    public function create(Kelas $kelas)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $kelas->load(['mataKuliah', 'mahasiswa' => function($query) {
            $query->orderBy('name');
        }]);

        return view('absensi.create', compact('kelas'));
    }

    public function store(Request $request, Kelas $kelas)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'materi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan_mahasiswa' => 'array',
            'keterangan_mahasiswa.*' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah ada absensi di tanggal tersebut
        $existingAbsensi = Absensi::where('kelas_id', $kelas->id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tanggal' => 'Absensi untuk tanggal ini sudah ada.']);
        }

        try {
            DB::beginTransaction();

            // Buat absensi
            $absensi = Absensi::create([
                'kelas_id' => $kelas->id,
                'tanggal' => $request->tanggal,
                'materi' => $request->materi,
                'keterangan' => $request->keterangan,
            ]);

            // Simpan detail absensi untuk setiap mahasiswa
            foreach ($request->status as $mahasiswaId => $status) {
                DetailAbsensi::create([
                    'absensi_id' => $absensi->id,
                    'mahasiswa_id' => $mahasiswaId,
                    'status' => $status,
                    'keterangan' => $request->keterangan_mahasiswa[$mahasiswaId] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('absensi.index', $kelas)
                ->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(Kelas $kelas, Absensi $absensi)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        // Cek apakah absensi milik kelas ini
        if ($absensi->kelas_id !== $kelas->id) {
            abort(404);
        }

        $absensi->load(['detailAbsensi.mahasiswa', 'kelas.mataKuliah']);

        return view('absensi.show', compact('kelas', 'absensi'));
    }

    public function edit(Kelas $kelas, Absensi $absensi)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        // Cek apakah absensi milik kelas ini
        if ($absensi->kelas_id !== $kelas->id) {
            abort(404);
        }

        $kelas->load(['mataKuliah', 'mahasiswa' => function($query) {
            $query->orderBy('name');
        }]);

        $absensi->load('detailAbsensi');

        return view('absensi.edit', compact('kelas', 'absensi'));
    }

    public function update(Request $request, Kelas $kelas, Absensi $absensi)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        // Cek apakah absensi milik kelas ini
        if ($absensi->kelas_id !== $kelas->id) {
            abort(404);
        }

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'materi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan_mahasiswa' => 'array',
            'keterangan_mahasiswa.*' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah ada absensi di tanggal tersebut (kecuali absensi ini)
        $existingAbsensi = Absensi::where('kelas_id', $kelas->id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $absensi->id)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tanggal' => 'Absensi untuk tanggal ini sudah ada.']);
        }

        try {
            DB::beginTransaction();

            // Update absensi
            $absensi->update([
                'tanggal' => $request->tanggal,
                'materi' => $request->materi,
                'keterangan' => $request->keterangan,
            ]);

            // Hapus detail absensi lama
            $absensi->detailAbsensi()->delete();

            // Simpan detail absensi baru
            foreach ($request->status as $mahasiswaId => $status) {
                DetailAbsensi::create([
                    'absensi_id' => $absensi->id,
                    'mahasiswa_id' => $mahasiswaId,
                    'status' => $status,
                    'keterangan' => $request->keterangan_mahasiswa[$mahasiswaId] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('absensi.index', $kelas)
                ->with('success', 'Absensi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Kelas $kelas, Absensi $absensi)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        // Cek apakah absensi milik kelas ini
        if ($absensi->kelas_id !== $kelas->id) {
            abort(404);
        }

        try {
            DB::beginTransaction();
            
            // Hapus detail absensi
            $absensi->detailAbsensi()->delete();
            
            // Hapus absensi
            $absensi->delete();
            
            DB::commit();
            
            return redirect()->route('absensi.index', $kelas)
                ->with('success', 'Absensi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('absensi.index', $kelas)
                ->with('error', 'Gagal menghapus absensi: ' . $e->getMessage());
        }
    }

    public function rekap(Kelas $kelas)
    {
        // Cek apakah user adalah dosen dari kelas ini
        if (auth()->user()->id !== $kelas->dosen_id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $kelas->load(['mataKuliah', 'mahasiswa' => function($query) {
            $query->orderBy('name');
        }]);

        // Ambil semua absensi kelas ini
        $absensiList = Absensi::where('kelas_id', $kelas->id)
            ->orderBy('tanggal')
            ->get();

        // Hitung statistik per mahasiswa
        $statistik = [];
        foreach ($kelas->mahasiswa as $mahasiswa) {
            $hadir = DetailAbsensi::whereIn('absensi_id', $absensiList->pluck('id'))
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'hadir')
                ->count();
            
            $sakit = DetailAbsensi::whereIn('absensi_id', $absensiList->pluck('id'))
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'sakit')
                ->count();
            
            $izin = DetailAbsensi::whereIn('absensi_id', $absensiList->pluck('id'))
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'izin')
                ->count();
            
            $alpha = DetailAbsensi::whereIn('absensi_id', $absensiList->pluck('id'))
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'alpha')
                ->count();

            $total = $absensiList->count();
            $persentase = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

            $statistik[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpha' => $alpha,
                'total' => $total,
                'persentase' => $persentase
            ];
        }

        return view('absensi.rekap', compact('kelas', 'absensiList', 'statistik'));
    }
}
