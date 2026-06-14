<?php

namespace App\Http\Controllers\AdminP2mp;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $prodis = Prodi::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_prodi', 'like', "%{$search}%")
                      ->orWhere('kode_prodi', 'like', "%{$search}%");
            })
            ->withCount('users')
            ->orderBy('nama_prodi')
            ->paginate(10)
            ->withQueryString();

        return view('adminp2mp.prodi.index', compact('prodis', 'search'));
    }

    public function create()
    {
        return view('adminp2mp.prodi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_prodi' => ['required', 'string', 'max:20', 'unique:prodi,kode_prodi'],
            'nama_prodi' => ['required', 'string', 'max:255'],
        ], [
            'kode_prodi.required' => 'Kode Program Studi wajib diisi.',
            'kode_prodi.unique' => 'Kode Program Studi sudah terdaftar.',
            'nama_prodi.required' => 'Nama Program Studi wajib diisi.',
        ]);

        Prodi::create($validated);

        return redirect()->route('adminp2mp.prodi.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('adminp2mp.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'kode_prodi' => ['required', 'string', 'max:20', "unique:prodi,kode_prodi,{$prodi->id}"],
            'nama_prodi' => ['required', 'string', 'max:255'],
        ], [
            'kode_prodi.required' => 'Kode Program Studi wajib diisi.',
            'kode_prodi.unique' => 'Kode Program Studi sudah terdaftar.',
            'nama_prodi.required' => 'Nama Program Studi wajib diisi.',
        ]);

        $prodi->update($validated);

        return redirect()->route('adminp2mp.prodi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        // Keamanan opsional: batasi penghapusan jika ada user yang terikat ke prodi ini, atau tangani cascade/set null
        // Mari kita hapus langsung, user yang terikat dengannya akan memiliki prodi_id bernilai null karena onDelete('set null') pada migrasi.
        $prodi->delete();

        return redirect()->route('adminp2mp.prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
