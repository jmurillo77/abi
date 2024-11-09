<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continente extends Model
{
    use HasFactory;
    protected $connection = 'matriz';  
    protected $table = 'continentes';
    protected $primaryKey = 'IdContinente';
}
