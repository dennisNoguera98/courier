<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas'; // si tu tabla se llama distinto, ajusta
    protected $primaryKey = 'id';  // o 'idEntregas' si así es tu PK

    public function cliente()
    {
        // FK en entregas: Clientes_idClientes (ajusta si es otro)
        return $this->belongsTo(Cliente::class, 'Clientes_idClientes', 'idClientes');
    }
}