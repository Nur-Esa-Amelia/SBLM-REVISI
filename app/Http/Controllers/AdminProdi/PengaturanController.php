<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     */
    public function index(Request $request)
    {
        $prodis = null;
        $selectedProdiId = null;

        if (auth()->user()->role === 'admin_p2mp') {
            $prodis = \App\Models\Prodi::orderBy('nama_prodi')->get();
            $prodiId = $request->query('prodi_id');
            if (!$prodiId && $prodis->isNotEmpty()) {
                $prodiId = $prodis->first()->id;
            }
            $selectedProdiId = $prodiId;
        } else {
            $prodiId = auth()->user()->prodi_id;
        }

        $pengaturan = null;
        if ($prodiId) {
            $pengaturan = Pengaturan::where('id_prodi', $prodiId)->first();
        }

        // Nilai bawaan jika belum diatur
        if (!$pengaturan) {
            $pengaturan = new Pengaturan([
                'id_prodi' => $prodiId,
                'tahun_mulai' => date('Y'),
                'tahun_selesai' => date('Y') + 4,
                'tahun_aktif' => date('Y'),
                'jml_mahasiswa' => 0,
                'jml_dosen' => 0,
            ]);
        }

        if ($request->ajax()) {
            return view('adminprodi.pengaturan.modal_content', compact('pengaturan', 'prodis', 'selectedProdiId'));
        }

        return view('adminprodi.pengaturan.index', compact('pengaturan', 'prodis', 'selectedProdiId'));
    }

    /**
     * Simpan atau perbarui pengaturan.
     */
    public function store(Request $request)
    {
        $rules = [
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'required|integer|gte:tahun_mulai|max:2100',
            'tahun_aktif' => 'required|integer',
            'jml_mahasiswa' => 'required|integer|min:0',
            'jml_dosen' => 'required|integer|min:0',
        ];

        // rentang (thn aktif gabole kecil dari thn mulai)
        if ($request->filled('tahun_mulai')) {
            $rules['tahun_aktif'] .= '|min:' . $request->tahun_mulai;
        }
        if ($request->filled('tahun_selesai')) {
            $rules['tahun_aktif'] .= '|max:' . $request->tahun_selesai;
        }

        $request->validate($rules, [
            'tahun_selesai.gte' => 'Tahun Selesai harus lebih besar atau sama dengan Tahun Mulai.',
            'tahun_aktif.min' => 'Tahun Aktif harus lebih besar atau sama dengan Tahun Mulai.',
            'tahun_aktif.max' => 'Tahun Aktif harus lebih kecil atau sama dengan Tahun Selesai.',
        ]);

        if (auth()->user()->role === 'admin_p2mp') {
            $request->validate([
                'prodi_id' => 'required|exists:prodi,id'
            ], [
                'prodi_id.required' => 'Program Studi wajib dipilih.'
            ]);
            $prodiId = $request->prodi_id;
        } else {
            $prodiId = auth()->user()->prodi_id;
        }

        $userId = auth()->id();

        Pengaturan::updateOrCreate(
            ['id_prodi' => $prodiId],
            [
                'id_user' => $userId,
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_selesai' => $request->tahun_selesai,
                'tahun_aktif' => $request->tahun_aktif,
                'jml_mahasiswa' => $request->jml_mahasiswa,
                'jml_dosen' => $request->jml_dosen,
            ]
        );

        $prodiName = \App\Models\Prodi::find($prodiId)->nama_prodi ?? 'Prodi';
        ActivityLog::log('Mengubah pengaturan sistem', 'Pengaturan Prodi', 'Mengubah pengaturan untuk: ' . $prodiName);

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
