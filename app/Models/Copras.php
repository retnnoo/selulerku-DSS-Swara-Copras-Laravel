<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Copras extends Model
{
    use HasFactory;

    protected $table = 'perangkingan_copras';

    protected $fillable = [
        'kode_wilayah',
        'kode_alternatif',
        'nilai_copras',
    ];
    public $timestamps = false;

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'kode_alternatif', 'kode_alternatif');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'kode_wilayah', 'kode_wilayah');
    }
}
