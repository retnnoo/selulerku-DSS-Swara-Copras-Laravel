<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiAlternatif extends Model
{
    use HasFactory;

    protected $table = 'nilai_alternatif';
    protected $fillable = ['kode_wilayah', 'kode_alternatif', 'kode_kriteria', 'nilai'];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'kode_alternatif', 'kode_alternatif');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
}

