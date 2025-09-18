<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ahli extends Model
{
    use HasFactory;

    protected $table = 'ahli';
    protected $fillable = ['kode_ahli'];

    protected $primaryKey = 'kode_ahli';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

      public function nilai()
    {
        return $this->hasMany(NilaiAhli::class, 'kode_ahli', 'kode_ahli');
    }

}

