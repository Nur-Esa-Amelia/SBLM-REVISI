<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Iku;
use App\Models\IkuPencapaian;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class IkuPencapaianController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = auth()->user()->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        
        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        $tahun = $request->query('tahun', $tahunAktif);

        // Sinkronisasikan dulu untuk memastikan data di database sudah terbaru
        IkuPencapaian::calculateAndSync($prodiId, $tahun);

        $pencapaian = IkuPencapaian::with('iku.kategori')
            ->where('id_prodi', $prodiId)
            ->where('tahun', $tahun)
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('adminprodi.pencapaian.index', compact('pencapaian', 'tahunList', 'tahun', 'settings'));
    }

    public function create(Request $request)
    {
        $prodiId = auth()->user()->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        $tahun = $request->query('tahun', $tahunAktif);

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        // Hanya tampilkan IKU yang belum memiliki target di tahun ini
        $existingIkuIds = IkuPencapaian::where('id_prodi', $prodiId)
            ->where('tahun', $tahun)
            ->pluck('id_iku')
            ->toArray();

        $iku = Iku::whereNotIn('id', $existingIkuIds)->get();

        return view('adminprodi.pencapaian.create', compact('iku', 'tahunList', 'tahun', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'tahun' => 'required|integer',
            'target' => 'required|string',
            'satuan' => 'required|in:persen,angka',
            'objek' => 'required|in:mahasiswa,dosen,lainnya',
            'keterangan' => 'nullable|string',
        ]);

        $prodiId = auth()->user()->prodi_id;

        // Periksa duplikasi target untuk keamanan
        $exists = IkuPencapaian::where('id_prodi', $prodiId)
            ->where('tahun', $request->tahun)
            ->where('id_iku', $request->id_iku)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['id_iku' => 'Target untuk IKU ini pada tahun tersebut sudah diatur.'])->withInput();
        }

        $pencapaian = IkuPencapaian::create([
            'id_iku' => $request->id_iku,
            'id_prodi' => $prodiId,
            'id_user' => auth()->id(),
            'tahun' => $request->tahun,
            'target' => $request->target,
            'satuan' => $request->satuan,
            'objek' => $request->objek,
            'keterangan' => $request->keterangan,
            'status' => 'Belum Tercapai',
        ]);

        // Sinkronisasikan segera
        IkuPencapaian::calculateAndSync($prodiId, $request->tahun);

        return redirect()->route('adminprodi.pencapaian.index', ['tahun' => $request->tahun])->with('success', 'Target IKU berhasil ditambahkan.');
    }

    public function edit(IkuPencapaian $pencapaian)
    {
        // Pemeriksaan keamanan
        if ($pencapaian->id_prodi !== auth()->user()->prodi_id) {
            abort(403);
        }

        $prodiId = auth()->user()->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        $iku = Iku::all();

        return view('adminprodi.pencapaian.edit', compact('pencapaian', 'iku', 'tahunList', 'settings'));
    }

    public function update(Request $request, IkuPencapaian $pencapaian)
    {
        // Pemeriksaan keamanan
        if ($pencapaian->id_prodi !== auth()->user()->prodi_id) {
            abort(403);
        }

        $request->validate([
            'target' => 'required|string',
            'satuan' => 'required|in:persen,angka',
            'objek' => 'required|in:mahasiswa,dosen,lainnya',
            'keterangan' => 'nullable|string',
        ]);

        $pencapaian->update([
            'target' => $request->target,
            'satuan' => $request->satuan,
            'objek' => $request->objek,
            'keterangan' => $request->keterangan,
        ]);

        // Sinkronisasikan segera
        IkuPencapaian::calculateAndSync($pencapaian->id_prodi, $pencapaian->tahun);

        return redirect()->route('adminprodi.pencapaian.index', ['tahun' => $pencapaian->tahun])->with('success', 'Target IKU berhasil diperbarui.');
    }

    public function destroy(IkuPencapaian $pencapaian)
    {
        // Pemeriksaan keamanan
        if ($pencapaian->id_prodi !== auth()->user()->prodi_id) {
            abort(403);
        }

        $tahun = $pencapaian->tahun;
        $pencapaian->delete();

        return redirect()->route('adminprodi.pencapaian.index', ['tahun' => $tahun])->with('success', 'Target IKU berhasil dihapus.');
    }
}
