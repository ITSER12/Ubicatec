<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartadoAula extends Model
{
    protected $fillable = [
        'aula',
        'dia',
        'hora_inicio',
        'hora_fin',
        'nombre_solicitante',
        'materia_reserva',
        'semana_inicio',
        'tipo_bloqueo',
        'override',
        'usuario_no_control',
    ];

    protected $casts = [
        'tipo_bloqueo' => 'integer',
        'override' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_no_control', 'no_control');
    }
}