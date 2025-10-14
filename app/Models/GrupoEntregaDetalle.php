<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoEntregaDetalle extends Model
{
    protected $table = 'grupos_entrega_detalles';
    protected $primaryKey = 'detalle_id';
    public $timestamps = true;

    protected $fillable = [
        'grupo_id',
        'extracto_id',
        'orden',
        'distancia_km'
    ];

    public function grupo()
    {
        return $this->belongsTo(GrupoEntrega::class, 'grupo_id', 'grupo_id');
    }

    // 👇 Relación correcta con la tabla extractos
  public function extracto()
{
    return $this->belongsTo(\App\Models\Extracto::class, 'extracto_id', 'extracto_id');
}
}