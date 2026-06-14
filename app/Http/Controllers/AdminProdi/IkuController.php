<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Iku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class IkuController extends Controller
{
    public function index()
    {
        $iku = Iku::with('kategori')->orderBy('id', 'asc')->paginate(10);
        return view('adminprodi.iku.index', compact('iku'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('adminprodi.iku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'nama_iku' => 'required|string|max:255|unique:iku,nama_iku',
            'deskripsi' => 'nullable|string',
        ]);

        Iku::create($request->all());

        return redirect()->route('adminprodi.iku.index')->with('success', 'Data IKU berhasil ditambahkan.');
    }

    public function edit(Iku $iku)
    {
        $kategori = Kategori::all();
        return view('adminprodi.iku.edit', compact('iku', 'kategori'));
    }

    public function update(Request $request, Iku $iku)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'nama_iku' => 'required|string|max:255|unique:iku,nama_iku,' . $iku->id,
            'deskripsi' => 'nullable|string',
        ]);

        $iku->update($request->all());

        return redirect()->route('adminprodi.iku.index')->with('success', 'Data IKU berhasil diperbarui.');
    }

    public function destroy(Iku $iku)
    {
        $iku->delete();
        return redirect()->route('adminprodi.iku.index')->with('success', 'Data IKU berhasil dihapus.');
    }
}
