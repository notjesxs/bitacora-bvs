<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id',
        'tipo_caso',
        'proceso',
        'descripcion',
        'prioridad',
        'solucion',
        'fecha_registro',
        'tiempo_resolucion',
        'estado',
        'area',
        'personal',
    ];

    public function encargado()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
    'fecha_registro' => 'datetime'
    ];
}