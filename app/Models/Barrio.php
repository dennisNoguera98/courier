<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    use HasFactory;

    protected $table = 'barrios';

    protected $fillable = [
        'nombre_barrio',
        'cobertura',
        'sync_status',
        'ciudad_id',
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function barrio()
    {
        return $this->belongsTo(Barrio::class, 'barrio_id', 'id'); // si usas FK barrio_id
    }


}
