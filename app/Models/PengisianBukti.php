<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengisianBukti extends Model
{
    use HasFactory;

    protected $table = 'pengisian_bukti';

    protected $fillable = [
        'id_iku',
        'id_bukti_iku',
        'id_user',
        'tahun',
        'keterangan',
        'status',
        'catatan_validator',
    ];

    //Satu pengisian bukti hanya terkait dengan satu IKU.
    public function iku()
    {
        return $this->belongsTo(Iku::class, 'id_iku');
    }

    //Satu pengisian bukti hanya terkait dengan satu jenis bukti iku.
    public function buktiIku()
    {
        return $this->belongsTo(BuktiIku::class, 'id_bukti_iku');
    }

    //Satu pengisian bukti hanya terkait dengan satu user (dosen).
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    //Satu pengisian bukti bisa memiliki banyak file.
    public function files()
    {
        return $this->hasMany(FileIsiBukti::class, 'id_pengisian_bukti');
    }
}
