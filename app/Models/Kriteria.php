<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'jenis_kriteria',
    ];

    protected $primaryKey = 'kode_kriteria';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function nilai()
    {
        return $this->hasMany(NilaiAhli::class, 'kode_kriteria', 'kode_kriteria');
    }

    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'kode_kriteria', 'kode_kriteria');
    }

}
