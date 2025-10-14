<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idClientes';   // <- PK real
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;             // déjalo en true si tu tabla tiene created_at/updated_at

    protected $fillable = [
        'Personas_idPersonas',
        'Prioridades_idPrioridades',
        'Ubicaciones_idUbicaciones',
    ];

    // Persona (FK en clientes: Personas_idPersonas -> personas.idPersonas)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Personas_idPersonas', 'idPersonas');
    }

    // Prioridad (FK en clientes: Prioridades_idPrioridades -> prioridades.idPrioridades)
    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class, 'Prioridades_idPrioridades', 'idPrioridades');
    }

    // Ubicación (FK en clientes: Ubicaciones_idUbicaciones -> ubicaciones.idUbicaciones)
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'Ubicaciones_idUbicaciones', 'idUbicaciones');
    }
    

    // Inversa: un cliente tiene muchos extractos
    // FK en extractos: cliente_id -> clientes.idClientes
    public function extractos()
    {
        return $this->hasMany(Extracto::class, 'cliente_id', 'idClientes');
    }



}

/* namespace App\Models;

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

    
}*/