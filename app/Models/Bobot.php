<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'pembobotan_swara';

    protected $fillable = [
        'kode_kriteria',
        'bobot_kriteria',
    ];
    public $timestamps = false;

    public function kriteria(){
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode_kriteria');
    }
}

