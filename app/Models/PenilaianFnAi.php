<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianFnAi extends Model
{
    use HasFactory;

    protected $table = 'penilaian_fn_ai';

    protected $fillable = [
        'id_penilaian',
        'fakta_terlewat',
    ];

    public function penilaian()
    {
        return $this->belongsTo(PenilaianRekomendasiAi::class, 'id_penilaian');
    }
}
