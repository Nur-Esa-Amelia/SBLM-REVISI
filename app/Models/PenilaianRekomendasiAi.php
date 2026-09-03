<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianRekomendasiAi extends Model
{
    use HasFactory;

    protected $table = 'penilaian_rekomendasi_ai';

    protected $fillable = [
        'id_rekomendasi_ai',
        'id_user',
        'total_klaim',
        'tp',
        'fp',
        'fn',
        'precision',
        'recall',
        'f1_score',
        'has_hallucination',
    ];

    protected $casts = [
        'precision' => 'float',
        'recall' => 'float',
        'f1_score' => 'float',
        'has_hallucination' => 'boolean',
    ];

    public function rekomendasiAi()
    {
        return $this->belongsTo(RekomendasiAi::class, 'id_rekomendasi_ai');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function klaimList()
    {
        return $this->hasMany(PenilaianKlaimAi::class, 'id_penilaian');
    }

    public function fnList()
    {
        return $this->hasMany(PenilaianFnAi::class, 'id_penilaian');
    }
}
