<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'id_prodi',
        'id_user',
        'tahun_mulai',
        'tahun_selesai',
        'tahun_aktif',
        'jml_mahasiswa',
        'jml_dosen',
    ];

    //1 pengaturan hanya terkait dengan 1 prodi.
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    //1 pengaturan hanya terkait dengan 1 user.
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
