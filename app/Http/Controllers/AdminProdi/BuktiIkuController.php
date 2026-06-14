<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\BuktiIku;
use App\Models\Iku;
use Illuminate\Http\Request;

class BuktiIkuController extends Controller
{
    public function index()
    {
        $bukti = BuktiIku::with('iku')->orderBy('id', 'asc')->paginate(10);
        return view('adminprodi.bukti.index', compact('bukti'));
    }

    public function create()
    {
        $iku = Iku::all();
        return view('adminprodi.bukti.create', compact('iku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'nama_bukti' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        BuktiIku::create($request->all());

        return redirect()->route('adminprodi.bukti.index')->with('success', 'Jenis Bukti IKU berhasil ditambahkan.');
    }

    public function edit(BuktiIku $bukti)
    {
        $iku = Iku::all();
        return view('adminprodi.bukti.edit', compact('bukti', 'iku'));
    }

    public function update(Request $request, BuktiIku $bukti)
    {
        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'nama_bukti' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $bukti->update($request->all());

        return redirect()->route('adminprodi.bukti.index')->with('success', 'Jenis Bukti IKU berhasil diperbarui.');
    }

    public function destroy(BuktiIku $bukti)
    {
        $bukti->delete();
        return redirect()->route('adminprodi.bukti.index')->with('success', 'Jenis Bukti IKU berhasil dihapus.');
    }
}
