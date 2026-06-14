<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iku extends Model
{
    use HasFactory;

    protected $table = 'iku';

    protected $fillable = [
        'id_kategori',
        'nama_iku',
        'deskripsi',
    ];

    //1 iku punya 1 kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    //1 iku punya banyak bukti iku
    public function buktiIku()
    {
        return $this->hasMany(BuktiIku::class, 'id_iku');
    }

    //1 iku punya banyak penugasan dosen
    public function penugasanDosen()
    {
        return $this->hasMany(PenugasanDosen::class, 'id_iku');
    }

    //1 iku punya banyak pengisian bukti
    public function pengisianBukti()
    {
        return $this->hasMany(PengisianBukti::class, 'id_iku');
    }

    //1 iku punya banyak iku pencapaian
    public function ikuPencapaian()
    {
        return $this->hasMany(IkuPencapaian::class, 'id_iku');
    }
}
