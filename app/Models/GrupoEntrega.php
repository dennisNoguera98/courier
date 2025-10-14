<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoEntrega extends Model
{
  /*  protected $table = 'grupos_entrega';
    protected $primaryKey = 'grupo_id';
    public $timestamps = true;

    protected $fillable = ['entrega_id', 'id_courier', 'estado'];

    public function detalles()
{
    return $this->hasMany(\App\Models\GrupoEntregaDetalle::class, 'grupo_id', 'grupo_id');
}*/

  protected $table = 'grupos_entrega';
    protected $primaryKey = 'grupo_id';
    public $timestamps = true;

    protected $fillable = [
        'entrega_id',
        'id_courier',
        'estado'
    ];

    // Relación con los detalles del grupo
    public function detalles()
    {
        return $this->hasMany(GrupoEntregaDetalle::class, 'grupo_id', 'grupo_id');
    }

    public function courier()
{
    return $this->belongsTo(\App\Models\Usuario::class, 'id_courier', 'usuario_id');
}


}