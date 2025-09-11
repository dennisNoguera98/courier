<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Extracto extends Model
{

    protected $fillable = [
        'entrega_id',
        'cliente_id',
        'orden_ruta',
        'estado',
        'gestor_id',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class, 'entrega_id', 'entrega_id');
    }

    public function gestor()
    {
        return $this->belongsTo(Usuarios::class, 'Usuarios_usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idClientes');
    }
}
