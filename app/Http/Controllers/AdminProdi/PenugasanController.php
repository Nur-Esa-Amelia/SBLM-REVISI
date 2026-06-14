<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Iku;
use App\Models\PenugasanDosen;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;

class PenugasanController extends Controller
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

        $penugasan = PenugasanDosen::with(['iku', 'user'])
            ->where('tahun', $tahun)
            ->whereHas('user', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('adminprodi.penugasan.index', compact('penugasan', 'tahunList', 'tahun', 'settings'));
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

        // Ambil hanya IKU yang belum ditugaskan ke dosen mana pun di prodi ini untuk tahun berjalan
        $assignedIkuIds = PenugasanDosen::where('tahun', $tahun)
            ->whereHas('user', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->pluck('id_iku')
            ->toArray();

        $iku = Iku::whereNotIn('id', $assignedIkuIds)->get();
        // Muat hanya dosen dari prodi ini
        $dosen = User::where('role', 'dosen')->where('prodi_id', $prodiId)->get();

        return view('adminprodi.penugasan.create', compact('iku', 'dosen', 'tahunList', 'tahun', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'id_user' => 'required|exists:users,id',
            'tahun' => 'required|integer',
        ]);

        $prodiId = auth()->user()->prodi_id;

        // Validasi keamanan: pastikan dosen yang dipilih berasal dari prodi yang sama dan berrole dosen
        $lecturer = User::where('id', $request->id_user)
            ->where('role', 'dosen')
            ->where('prodi_id', $prodiId)
            ->first();

        if (!$lecturer) {
            return redirect()->back()->withErrors(['id_user' => 'Dosen yang dipilih tidak valid atau bukan dari program studi Anda.'])->withInput();
        }

        // Periksa apakah IKU ini sudah ditugaskan di prodi ini untuk tahun ini
        $exists = PenugasanDosen::where('id_iku', $request->id_iku)
            ->where('tahun', $request->tahun)
            ->whereHas('user', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['id_iku' => 'Indikator IKU ini sudah ditugaskan kepada dosen lain di program studi Anda pada tahun akademik ini.'])->withInput();
        }

        PenugasanDosen::create($request->all());

        return redirect()->route('adminprodi.penugasan.index', ['tahun' => $request->tahun])->with('success', 'Penugasan dosen berhasil dibuat.');
    }

    public function edit(PenugasanDosen $penugasan)
    {
        // Pemeriksaan keamanan
        if ($penugasan->user->prodi_id !== auth()->user()->prodi_id) {
            abort(403);
        }

        $prodiId = auth()->user()->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        // Ambil hanya IKU yang belum ditugaskan ke dosen lain di prodi ini untuk tahun berjalan
        $assignedIkuIds = PenugasanDosen::where('tahun', $penugasan->tahun)
            ->where('id', '!=', $penugasan->id)
            ->whereHas('user', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->pluck('id_iku')
            ->toArray();

        $iku = Iku::whereNotIn('id', $assignedIkuIds)->get();
        $dosen = User::where('role', 'dosen')->where('prodi_id', $prodiId)->get();

        return view('adminprodi.penugasan.edit', compact('penugasan', 'iku', 'dosen', 'tahunList', 'settings'));
    }

    public function update(Request $request, PenugasanDosen $penugasan)
    {
        // Pemeriksaan keamanan
        if ($penugasan->user->prodi_id !== auth()->user()->prodi_id) {
            abort(403);
        }

        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'id_user' => 'required|exists:users,id',
            'tahun' => 'required|integer',
        ]);

        $prodiId = auth()->user()->prodi_id;

        // Validasi keamanan
        $lecturer = User::where('id', $request->id_user)
            ->where('role', 'dosen')
            ->where('prodi_id', $prodiId)
            ->first();

        if (!$lecturer) {
            return redirect()->back()->withErrors(['id_user' => 'Dosen yang dipilih tidak valid atau bukan dari program studi Anda.'])->withInput();
        }

        // Periksa apakah IKU ini sudah ditugaskan ke dosen lain di prodi ini untuk tahun akademik berjalan
        $exists = PenugasanDosen::where('id_iku', $request->id_iku)
            ->where('tahun', $request->tahun)
            ->where('id', '!=', $penugasan->id)
            ->whereHas('user', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['id_iku' => 'Indikator IKU ini sudah ditugaskan kepada dosen lain di program studi Anda pada tahun akademik ini.'])->withInput();
        }

        $penugasan->update($request->all());

        return redirect()->route('adminprodi.penugasan.index', ['tahun' => $penugasan->tahun])->with('success', 'Penugasan dosen berhasil diperbarui.');
    }

    public function destroy(PenugasanDosen $penugasan)
    {
        // Pemeriksaan keamanan
        if ($penugasan->user->prodi_id !== auth()->user()->prodi_id) {
            abort(403);
        }

        $tahun = $penugasan->tahun;
        $penugasan->delete();

        return redirect()->route('adminprodi.penugasan.index', ['tahun' => $tahun])->with('success', 'Penugasan dosen berhasil dihapus.');
    }
}
