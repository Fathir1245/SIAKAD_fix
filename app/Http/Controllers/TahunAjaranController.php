<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->paginate(10);
        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'in:Ganjil,Genap'],
        ]);

        if ($request->aktif) {
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
        }

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function show(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.show', compact('tahunAjaran'));
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'in:Ganjil,Genap'],
        ]);

        if ($request->aktif && !$tahunAjaran->aktif) {
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
        }

        $tahunAjaran->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->aktif) {
            return redirect()->route('tahun-ajaran.index')->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }

        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
