<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenugasanDosen extends Model
{
    use HasFactory;

    protected $table = 'penugasan_dosen';

    protected $fillable = [
        'id_iku',
        'id_user',
        'tahun',
    ];

    //Satu penugasan hanya terkait dengan satu IKU.
    public function iku()
    {
        return $this->belongsTo(Iku::class, 'id_iku');
    }

    //Satu penugasan hanya terkait dengan satu user (dosen).
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
