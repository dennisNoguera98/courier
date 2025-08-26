<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $primaryKey = 'idUbicaciones';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'coordenadas',
        'estado',
        'fecha_hora',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'Ubicaciones_idUbicaciones');
    }
}