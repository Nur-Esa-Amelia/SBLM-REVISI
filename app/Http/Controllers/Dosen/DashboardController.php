<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PenugasanDosen;
use App\Models\Pengaturan;
use App\Models\PengisianBukti;
use App\Models\IkuPencapaian;
use App\Models\FileIsiBukti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard Dosen.
     */
    public function index()
    {
        $user = auth()->user();
        $prodiId = $user->prodi_id;
        $prodiName = $user->prodi ? $user->prodi->nama_prodi : 'Program Studi';
        
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');

        // Ambil IKU yang ditugaskan untuk tahun ini
        $assignments = PenugasanDosen::with(['iku.kategori'])
            ->where('id_user', $user->id)
            ->where('tahun', $tahunAktif)
            ->get();

        // Hitung statistik untuk tahun akademik aktif
        $totalAssignments = $assignments->count();
        $totalProofs = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($user, $tahunAktif) {
            $query->where('id_user', $user->id)->where('tahun', $tahunAktif);
        })->count();

        $validProofs = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($user, $tahunAktif) {
            $query->where('id_user', $user->id)->where('tahun', $tahunAktif)->where('status', 'valid');
        })->count();

        $pendingProofs = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($user, $tahunAktif) {
            $query->where('id_user', $user->id)->where('tahun', $tahunAktif)->where('status', 'pending');
        })->count();

        $invalidProofs = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($user, $tahunAktif) {
            $query->where('id_user', $user->id)->where('tahun', $tahunAktif)->where('status', 'invalid');
        })->count();

        // Hitung kemajuan individu dosen
        $totalPercentage = 0;
        $countWithTarget = 0;

        foreach ($assignments as $assignment) {
            $pencapaian = IkuPencapaian::where('id_iku', $assignment->id_iku)
                ->where('id_prodi', $prodiId)
                ->where('tahun', $tahunAktif)
                ->first();

            $targetNyata = 0;
            $realisasi = 0;
            $persentase = 0;

            if ($pencapaian) {
                // 1. Nilai target
                $targetVal = floatval($pencapaian->target);
                
                // 2. Konversi jika satuannya persen
                if ($pencapaian->satuan === 'persen') {
                    if ($pencapaian->objek === 'mahasiswa') {
                        $targetNyata = ($targetVal / 100) * ($settings ? $settings->jml_mahasiswa : 0);
                    } elseif ($pencapaian->objek === 'dosen') {
                        $targetNyata = ($targetVal / 100) * ($settings ? $settings->jml_dosen : 0);
                    } else {
                        $targetNyata = $targetVal;
                    }
                } else {
                    $targetNyata = $targetVal;
                }

                // 3. Realisasi per IKU = HITUNG berkas bukti yang valid untuk user ini
                $realisasi = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($assignment, $user, $tahunAktif) {
                    $query->where('id_iku', $assignment->id_iku)
                        ->where('id_user', $user->id)
                        ->where('tahun', $tahunAktif)
                        ->where('status', 'valid');
                })->count();

                // 4. Persentase per IKU = (realisasi / target_nyata) * 100
                $persentase = $targetNyata > 0 ? ($realisasi / $targetNyata) * 100 : 0;

                $totalPercentage += $persentase;
                $countWithTarget++;
            }

            // Lampirkan variabel ke model penugasan untuk dikirim ke view
            $assignment->target_nyata = $targetNyata;
            $assignment->realisasi = $realisasi;
            $assignment->persentase = $persentase;
            $assignment->satuan = $pencapaian ? $pencapaian->satuan : '';
            $assignment->objek = $pencapaian ? $pencapaian->objek : '';
        }

        // Kemajuan keseluruhan adalah rata-rata persentase IKU yang ditugaskan
        $achievementPercentage = $countWithTarget > 0 
            ? round($totalPercentage / $countWithTarget) 
            : 0;

        return view('dosen.dashboard', compact(
            'prodiName',
            'tahunAktif',
            'assignments',
            'totalAssignments',
            'totalProofs',
            'validProofs',
            'pendingProofs',
            'invalidProofs',
            'achievementPercentage',
            'settings'
        ));
    }

    /**
     * Tampilkan target IKU prodi, status pencapaian, dan berkas bukti yang diunggah oleh dosen itu sendiri.
     */
    public function pencapaian(Request $request)
    {
        $user = auth()->user();
        $prodiId = $user->prodi_id;
        $prodiName = $user->prodi ? $user->prodi->nama_prodi : 'Program Studi';
        
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        
        if ($settings) {
            $tahunList = range($settings->tahun_mulai, $settings->tahun_selesai);
        } else {
            $tahunList = range(date('Y') - 2, date('Y') + 5);
        }

        $tahun = $request->query('tahun', $tahunAktif);

        // Sinkronisasikan dulu untuk memastikan data pencapaian di database terbaru
        IkuPencapaian::calculateAndSync($prodiId, $tahun);

        // Ambil semua target/pencapaian untuk program studi ini
        $pencapaianList = IkuPencapaian::with('iku.kategori')
            ->where('id_prodi', $prodiId)
            ->where('tahun', $tahun)
            ->orderBy('id', 'asc')
            ->get();

        // Lampirkan bukti unggahan milik dosen itu sendiri untuk masing-masing IKU
        foreach ($pencapaianList as $item) {
            $item->my_proofs = PengisianBukti::with(['buktiIku', 'files'])
                ->where('id_user', $user->id)
                ->where('id_iku', $item->id_iku)
                ->where('tahun', $tahun)
                ->get();
        }

        // Dapatkan daftar ID IKU yang ditugaskan ke dosen ini untuk tahun akademik berjalan
        $assignedIkuIds = PenugasanDosen::where('id_user', $user->id)
            ->where('tahun', $tahun)
            ->pluck('id_iku')
            ->toArray();

        return view('dosen.pencapaian.index', compact(
            'pencapaianList',
            'assignedIkuIds',
            'tahunList',
            'tahun',
            'prodiName',
            'settings'
        ));
    }
}
