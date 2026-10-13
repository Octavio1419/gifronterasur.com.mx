<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos_panel';

    protected $fillable = [
        'marca', 'linea', 'modelo', 'efecticard', 'obra_id'
    ];

    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }
}
