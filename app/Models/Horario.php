<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'materia', 'grupo', 'aula', 'dia', 'hora_inicio', 'hora_fin', 'docente'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i'
    ];
}
