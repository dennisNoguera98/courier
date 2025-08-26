<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idClientes';
    public $timestamps = false;

    protected $fillable = [
        'Personas_idPersonas',
        'Prioridades_idPrioridades',
        'Ubicaciones_idUbicaciones',
    ];
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Personas_idPersonas');
    }
    
    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'Prioridades_idPrioridades');
    }


      public function ubicacion()
    {
        // FK en clientes: Ubicaciones_idUbicaciones
        return $this->belongsTo(Ubicacion::class, 'Ubicaciones_idUbicaciones', 'idUbicaciones');
    }
}