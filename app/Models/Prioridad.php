<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    protected $table = 'prioridades';
    protected $primaryKey = 'idPrioridades';
    public $timestamps = false;

    protected $fillable = [
        'nombre_perfil',
        'descripcion_perfil',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'Prioridades_idPrioridades');
    }
}