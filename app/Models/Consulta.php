<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['nome', 'numero', 'inicio_epoch', 'fim_epoch'];
}
