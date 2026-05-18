<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudEvento extends Model
{
    protected $table = 'solicitudes_evento'; 
    
    protected $fillable = [
        'usuario_id', 'titulo', 'descripcion',
        'fechaevento', 'poster', 'estado',
    ];

    public function usuario() {
    return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id', 'no_control');
}
}