<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfiles';
    protected $primaryKey = 'idPerfiles';

    protected $fillable = [
        'nombre_perfil',
        'descripcion_perfil'
    ];

      public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'perfiles_has_usuarios', 'Perfiles_idPerfiles', 'Usuarios_usuario_id');
    }
}
