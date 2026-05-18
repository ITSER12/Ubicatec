<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriaFoto extends Model
{
    protected $table = 'galeria_fotos';

    protected $fillable = [
        'area',
        'titulo',
        'descripcion',
        'imagen',
    ];
}