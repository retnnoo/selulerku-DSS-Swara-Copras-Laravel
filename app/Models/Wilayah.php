<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    protected $fillable = [
        'kode_wilayah',
        'nama_wilayah',
    ];

    protected $primaryKey = 'kode_wilayah';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function nilaiAlternatif(){
        return $this->hasMany(NilaiAlternatif::class, 'kode_wilayah', 'kode_wilayah');
    }

}
