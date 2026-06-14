<?php

namespace App\Http\Controllers\AdminP2mp;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use App\Models\PengisianBukti;
use App\Models\IkuPencapaian;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard Admin P2MP.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count(); 
        $totalProdi = Prodi::count();
        $pendingValidationCount = PengisianBukti::where('status', 'pending')->count(); 
        
        $prodis = Prodi::all();  
        $prodiId = $request->query('prodi_id'); 

        if ($prodiId) {
            $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        } else {
            $minStart = Pengaturan::min('tahun_mulai');
            $maxEnd = Pengaturan::max('tahun_selesai');
            $activeYear = Pengaturan::value('tahun_aktif');
            if ($minStart && $maxEnd) {
                $settings = (object)[
                    'tahun_mulai' => $minStart,
                    'tahun_selesai' => $maxEnd,
                    'tahun_aktif' => $activeYear ?: date('Y'),
                ];
            } else {
                $settings = null;
            }
        }

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $dbYears = IkuPencapaian::select('tahun')->distinct()->pluck('tahun')->toArray(); 
            $tahunList = array_unique(array_merge($dbYears, [date('Y') - 2, date('Y') - 1, date('Y'), date('Y') + 1]));
            sort($tahunList);
        }

        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        $tahun = $request->query('tahun', $tahunAktif);

        foreach ($prodis as $prodi) {
            IkuPencapaian::calculateAndSync($prodi->id, $tahun);
        }

        $query = IkuPencapaian::with(['prodi', 'iku.kategori'])
            ->where('tahun', $tahun);

        if ($request->filled('prodi_id')) {
            $query->where('id_prodi', $prodiId);
        }

        $laporan = $query->orderBy('id_prodi')
            ->orderBy('id', 'asc')
            ->get();

        $settingsMap = Pengaturan::all()->keyBy('id_prodi');
        $totalReports = IkuPencapaian::where('tahun', $tahun)->count();

        return view('adminp2mp.dashboard', compact(
            'totalUsers',
            'totalProdi',
            'pendingValidationCount',
            'totalReports',
            'laporan',
            'settingsMap',
            'tahun',
            'prodis',
            'tahunList',
            'prodiId'
        ));
    }

    /**
     * Tampilkan halaman Validasi Bukti IKU.
     */
    public function validasi(Request $request)
    {
        $prodis = Prodi::all();
        $prodiId = $request->query('prodi_id');
        $status = $request->query('status');
        $tahun = $request->query('tahun');

        if ($prodiId) {
            $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        } else {
            $minStart = Pengaturan::min('tahun_mulai');
            $maxEnd = Pengaturan::max('tahun_selesai');
            $activeYear = Pengaturan::value('tahun_aktif');
            if ($minStart && $maxEnd) {
                $settings = (object)[
                    'tahun_mulai' => $minStart,
                    'tahun_selesai' => $maxEnd,
                    'tahun_aktif' => $activeYear ?: date('Y'),
                ];
            } else {
                $settings = null;
            }
        }

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = PengisianBukti::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
            if (empty($tahunList)) {
                $tahunList = [date('Y')];
            }
        }

        $query = PengisianBukti::with(['user.prodi', 'iku.kategori', 'buktiIku', 'files']);

        if ($request->filled('prodi_id')) {
            $query->whereHas('user', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $tahun);
        }

        $riwayat = $query->latest()->paginate(10);

        return view('adminp2mp.validasi.index', compact(
            'riwayat',
            'prodis',
            'tahunList',
            'prodiId',
            'status',
            'tahun'
        ));
    }

    /**
     * Perbarui status validasi pengajuan bukti.
     */
    public function updateValidasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:valid,invalid',
            'catatan_validator' => 'nullable|string|max:1000',
        ]);

        $pengisian = PengisianBukti::findOrFail($id);
        
        $pengisian->update([
            'status' => $request->status,
            'catatan_validator' => $request->status === 'invalid' ? $request->catatan_validator : null,
        ]);

        $statusText = $request->status === 'valid' ? 'disetujui (Valid)' : 'ditolak (Perlu Perbaikan)';
        return redirect()->back()->with('success', "Bukti IKU berhasil {$statusText}.");
    }

    /**
     * Tampilkan halaman Monitoring & Laporan.
     */
    public function monitoring(Request $request)
    {
        $prodis = Prodi::all();
        if ($prodis->isEmpty()) {
            return view('adminp2mp.monitoring.index', [
                'laporan' => collect(),
                'prodis' => collect(),
                'tahunList' => [date('Y')],
                'prodiId' => null,
                'prodiName' => '',
                'tahun' => date('Y'),
                'settings' => null
            ]);
        }

        $prodiId = $request->query('prodi_id', $prodis->first()->id);
        $selectedProdi = Prodi::find($prodiId) ?: $prodis->first();
        $prodiId = $selectedProdi->id;
        $prodiName = $selectedProdi->nama_prodi;

        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');

        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $dbYears = IkuPencapaian::select('tahun')->distinct()->pluck('tahun')->toArray();
            $tahunList = array_unique(array_merge($dbYears, [date('Y') - 1, date('Y'), date('Y') + 1]));
            sort($tahunList);
        }

        $tahun = $request->query('tahun', $tahunAktif);

        // Sinkronisasikan dulu untuk memastikan data di database sudah terbaru
        IkuPencapaian::calculateAndSync($prodiId, $tahun);

        $laporan = IkuPencapaian::with('iku.kategori')
            ->where('id_prodi', $prodiId)
            ->where('tahun', $tahun)
            ->orderBy('id', 'asc') 
            ->get();

        return view('adminp2mp.monitoring.index', compact(
            'laporan',
            'prodis',
            'tahunList',
            'prodiId',
            'prodiName',
            'tahun',
            'settings'
        ));
    }
}
