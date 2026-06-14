<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
    ];

    //1 prodi punya banyak user
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
