<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IkuPencapaian extends Model
{
    use HasFactory;

    protected $table = 'iku_pencapaian';

    protected $fillable = [
        'id_iku',
        'id_prodi',
        'id_user',
        'tahun',
        'target',
        'satuan',
        'realisasi',
        'objek',
        'keterangan',
        'status',
    ];

    //1 iku pencapaian punya 1 iku
    public function iku()
    {
        return $this->belongsTo(Iku::class, 'id_iku');
    }

    //1 iku pencapaian punya 1 prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    //1 iku pencapaian punya 1 user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Hitung realisasi dan sinkronisasikan status berdasarkan bukti yang divalidasi P2MP.
     */
    public static function calculateAndSync($prodiId, $tahun)
    {
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        $jml_mahasiswa = $settings ? $settings->jml_mahasiswa : 0;
        $jml_dosen = $settings ? $settings->jml_dosen : 0;

        $pencapaians = self::where('id_prodi', $prodiId)->where('tahun', $tahun)->get(); 

        foreach ($pencapaians as $pencapaian) {
            // Realisasi adalah jumlah berkas bukti yang diunggah oleh dosen dari prodi ini, di tahun ini, dan berstatus 'valid'
            $realisasi = FileIsiBukti::whereHas('pengisianBukti', function ($query) use ($pencapaian, $tahun, $prodiId) {
                $query->where('id_iku', $pencapaian->id_iku)
                    ->where('tahun', $tahun)
                    ->where('status', 'valid')
                    ->whereHas('user', function ($q) use ($prodiId) {
                        $q->where('prodi_id', $prodiId);
                    });
            })->count();

            $targetVal = floatval($pencapaian->target); 
            if ($pencapaian->satuan === 'persen') {
                if ($pencapaian->objek === 'mahasiswa') {
                    $target_nyata = ($targetVal / 100) * $jml_mahasiswa;
                } elseif ($pencapaian->objek === 'dosen') {
                    $target_nyata = ($targetVal / 100) * $jml_dosen;
                } else {
                    $target_nyata = $targetVal;
                }
            } else {
                $target_nyata = $targetVal;
            }

            // Tentukan status ketercapaian target
            if ($target_nyata > 0) {
                $persentase = ($realisasi / $target_nyata) * 100;
            } else {
                $persentase = $realisasi > 0 ? 100 : 0;
            }

            if ($persentase >= 80) {
                $status = 'Tercapai';
                // Hapus rekomendasi jika ada karena status sudah tercapai/aman
                \App\Models\RekomendasiAi::where('id_iku_pencapaian', $pencapaian->id)->delete();
            } elseif ($persentase >= 60) {
                $status = 'Belum Tercapai';
            } else {
                $status = 'Berisiko Tidak Tercapai';
            }

            $pencapaian->update([
                'realisasi' => $realisasi,
                'status' => $status
            ]);
        }
    }
}
