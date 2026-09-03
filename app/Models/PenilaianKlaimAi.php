<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianKlaimAi extends Model
{
    use HasFactory;

    protected $table = 'penilaian_klaim_ai';

    protected $fillable = [
        'id_penilaian',
        'nomor_klaim',
        'teks_klaim',
        'status_penilaian',
    ];

    public function penilaian()
    {
        return $this->belongsTo(PenilaianRekomendasiAi::class, 'id_penilaian');
    }
}
