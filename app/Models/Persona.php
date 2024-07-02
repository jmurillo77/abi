<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'Personas';
    protected $primaryKey = 'IdPersona';

    protected function casts(): array{
        return [
            'deleted_at' => 'datetime',
            'Eliminado' => 'boolean'
        ];
    }
}
