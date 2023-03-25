<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicador';
    public $timestamps = false;
    protected $fillable = ['nombreIndicador','codigoIndicador','unidadMedidaIndicador','valorIndicador','fechaIndicador'];
}
