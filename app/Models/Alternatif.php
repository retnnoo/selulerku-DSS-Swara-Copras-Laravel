<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';

    protected $fillable = [
        'kode_alternatif',
        'nama_alternatif',
    ];

    protected $primaryKey = 'kode_alternatif';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function nilaiAlternatif(){
        return $this->hasMany(NilaiAlternatif::class, 'kode_alternatif', 'kode_alternatif');
    }
}
