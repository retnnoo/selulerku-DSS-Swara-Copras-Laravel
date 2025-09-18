<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiAhli extends Model
{
    use HasFactory;

    protected $table = 'nilai_ahli';
    protected $fillable = ['kode_ahli', 'kode_kriteria', 'nilai'];

    public function ahli()
    {
        return $this->belongsTo(Ahli::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
}

