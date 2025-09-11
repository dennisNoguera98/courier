<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{

    protected $primaryKey = 'entrega_id';

    protected $fillable = [
        'nombre_entrega',
        'estado',
        'observaciones',
        'gestor_id',
        'sync_status'
    ];

    public function extractos()
    {
        return $this->hasMany(Extracto::class, 'entrega_id', 'entrega_id');
    }

    public function gestor()
    {
        return $this->belongsTo(Usuario::class, 'Usuarios_usuario_id');
    }
}