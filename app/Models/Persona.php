<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'idPersonas';
    public $timestamps = false;

    protected $fillable = [
        'nombre_persona',
        'apellido_persona',
        'direccion_persona',
        'celular_principal_persona',
        'celular_secundario_persona',
        'observacion',
        'cedula',
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'Personas_idPersonas');
    }
}