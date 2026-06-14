<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\IkuPencapaian;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
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
            $selectedProdi = \App\Models\Prodi::find($prodiId);
            $prodiName = $selectedProdi ? $selectedProdi->nama_prodi : 'Program Studi';
        } else {
            $prodiId = auth()->user()->prodi_id;
            $prodiName = auth()->user()->prodi ? auth()->user()->prodi->nama_prodi : 'Program Studi';
        }
        
        $settings = null;
        if ($prodiId) {
            $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        }
        
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        
        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        $tahun = $request->query('tahun', $tahunAktif);

        // Sinkronisasikan database terlebih dahulu untuk merefleksikan jumlah realisasi terbaru
        if ($prodiId) {
            IkuPencapaian::calculateAndSync($prodiId, $tahun);
        }

        $laporan = null;
        if ($prodiId) {
            $laporan = IkuPencapaian::with('iku.kategori')
                ->where('id_prodi', $prodiId)
                ->where('tahun', $tahun)
                ->get();
        } else {
            $laporan = collect();
        }

        return view('adminprodi.laporan.index', compact('laporan', 'tahunList', 'tahun', 'prodiName', 'settings', 'prodis', 'selectedProdiId'));
    }
}
