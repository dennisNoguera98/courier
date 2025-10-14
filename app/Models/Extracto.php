<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracto extends Model
{
    protected $table = 'extractos';              // <-- nombre de la tabla
    protected $primaryKey = 'extracto_id';       // <-- PK correcta
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;                   // si tu tabla tiene created_at/updated_at

    protected $fillable = [
        'entrega_id',
        'cliente_id',
        'orden_ruta',
        'estado',
        'gestor_id',                             // <-- este es el FK al usuario/gestor
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class, 'entrega_id', 'entrega_id');
    }

    public function gestor()
    {
        // Modelo Usuario y columnas: extractos.gestor_id -> usuarios.usuario_id
        return $this->belongsTo(Usuario::class, 'gestor_id', 'usuario_id');
    }

  public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id', 'idClientes');
    }
}



/*namespace App\Models;

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
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idClientes');
    }
}
*/